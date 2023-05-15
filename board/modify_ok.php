<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/common.php"; ?>
<?php
$idx = post('idx', '0');
if($idx == '0') {
?>
<script>alert('잘못된 접근 입니다.');location.href='/board/';</script>
<?php
return;
}

$subject = post('subject');
$writer = post('writer');
$pwd = post('pwd');
$content = post('content');

// 비밀번호확인을 위해 게시물 가져오기
$stmt = $db->prepare("SELECT * FROM " . $_board_options["tableName"] . " WHERE idx=:idx;");
$stmt->bindValue(':idx', $idx);
$stmt->execute();
if($row = $stmt->fetch(PDO::FETCH_BOTH)) {
    if($row['pwd'] != $pwd) {
        ?>
        <script>alert('비밀번호가 일치하지 않습니다.');history.back();</script>
        <?php
        return;
    } else {
        $old_attach_file = $row['attach_file'];
    }
} else {
    ?>
    <script>alert('잘못된 접근 입니다.');location.href='/board/';</script>
    <?php
    return;
}

// 첨부파일 저장 경로(폴더의 풀네임) 설정
$upload_folder = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
$attachFiles = [];
foreach($_FILES['file']['error'] as $key => $error) {
    if($error == UPLOAD_ERR_OK && file_exists($_FILES['file']['tmp_name'][$key])) {
        // 각각의 파일명
        $file_name = basename($_FILES['file']['name'][$key]);
        // 각각의 임시파일 전체 경로
        $tmp_name = $_FILES['file']['tmp_name'][$key];
        // 각각의 확장자
        //$file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_ext = strtolower(pathinfo($file_name)['extension']);
        //각각의 확장자를 제외한 파일명
        $file_without_ext = pathinfo($file_name)['filename'];
        
        // 올바른 유형의 파일 체크(확장자 체크)
        // 허용된 파일 크기 체크
        // 중복 파일명 체크
        $save_file_path = $upload_folder . $file_name;
        $file_label = 1;
        while(file_exists($save_file_path)) {
            $save_file_path = $upload_folder . $file_without_ext . '[' . $file_label . '].' . $file_ext;
            $file_label += 1;
        }
        $save_file_name = basename($save_file_path);
        move_uploaded_file($tmp_name, $save_file_path);
        $attachFiles[] = [
            'ori_file_name'=>$file_name, 
            'save_file_name'=>$save_file_name, 
            'file_size'=>round($_FILES['file']['size'][$key] / 1024, 2), 
            'mimetype'=>$_FILES['file']['type'][$key]
        ];        
    } else {
        echo '* 에러 발생 : ' . $error . '<br>';
    }
}
// var_dump($_FILES['file']);
$stmt = $db->prepare("INSERT INTO attach_files (board_id, ori_file_name, save_file_name, file_size, mimetype) VALUES (:board_id, :ori_file_name, :save_file_name, :file_size, :mimetype)");
$stmt->bindParam(':board_id', $idx);
$stmt->bindParam(':ori_file_name', $db_ori_file_name);
$stmt->bindParam(':save_file_name', $db_save_file_name);
$stmt->bindParam(':file_size', $db_file_size);
$stmt->bindParam(':mimetype', $db_mimetype);
foreach($attachFiles as $index => $val) {
    $db_ori_file_name = $val['ori_file_name'];
    $db_save_file_name = $val['save_file_name'];
    $db_file_size = $val['file_size'];
    $db_mimetype = $val['mimetype'];
    $stmt->execute();
}

$del_files = post("del_file", false);
if($del_files !== false) {        
    // 첨부파일 삭제하기
    $stmt = $db->prepare("SELECT * FROM attach_files WHERE id IN (" . join(",", $del_files) . ");");
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_BOTH)) {
        unlink($upload_folder . $row['save_file_name']);
    }
    $stmt = $db->prepare("DELETE FROM attach_files WHERE id IN (" . join(",", $del_files) . ");");
    $stmt->execute();
}

$stmt = $db->prepare("SELECT COUNT(*) FROM attach_files WHERE board_id=:board_id;");
$stmt->bindValue(':board_id', $idx);
$stmt->execute();
$attach_file_count = $stmt->fetchColumn();


$stmt = $db->prepare("UPDATE " . $_board_options["tableName"] . " SET subject=:subject, writer=:writer, attach_file=:attach_file, content=:content WHERE idx=:idx;");
$stmt->bindValue(':subject', $subject);
$stmt->bindValue(':writer', $writer);
$stmt->bindValue(':attach_file', $attach_file_count);
$stmt->bindValue(':content', $content);
$stmt->bindValue(':idx', $idx);
$stmt->execute();

$q = get('q');

$db = null;
?>

<script>
    location.href='<?=$_board_options["viewPage"]?>?idx=<?=$idx?>&q=<?=$q?>';
</script>


