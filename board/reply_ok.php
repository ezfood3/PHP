<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/common.php"; ?>
<?php
$q = get('q');
$page_no = get('page_no', 1);
$ori_idx = get('idx', 0);
if($ori_idx == '0') {
?>
<script>alert('잘못된 접근 입니다.');location.href='/board/';</script>
<?php
return;
}
// 원본글 게시물 가져오기
$stmt = $db->prepare("SELECT * FROM " . $_board_options["tableName"] . " WHERE idx=:idx;");
$stmt->bindValue(':idx', $ori_idx);
$stmt->execute();
if($row = $stmt->fetch(PDO::FETCH_BOTH)) {
    $group_id = $row['group_id'];
    $level = $row['level'];
    $depth = $row['depth'];
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

$subject = post('subject', '제목 없음');
$writer = post('writer', '작성자 없음');
$pwd = post('pwd');
$content = post('content');

// 순서 정렬을 먼저 해야합니다.
$stmt = $db->prepare("SELECT min(level) AS level FROM " . $_board_options["tableName"] . " WHERE group_id=:group_id AND depth<=:depth AND level>:level;");
$stmt->bindValue(':group_id', $group_id);
$stmt->bindValue(':depth', $depth);
$stmt->bindValue(':level', $level);
$stmt->execute();
if($row = $stmt->fetch(PDO::FETCH_BOTH)) {
    $level = $row['level'];
}
if($level === null) {
    $stmt = $db->prepare("SELECT MAX(level) AS level FROM " . $_board_options["tableName"] . " WHERE group_id=:group_id;");
    $stmt->bindValue(':group_id', $group_id);
    $stmt->execute();
    if($row = $stmt->fetch(PDO::FETCH_BOTH)) {
        $level = $row['level'];
    }
    $level += 1;
}
////////////////////////////////////
/// 레벨 밀어내기
$stmt = $db->prepare("UPDATE " . $_board_options["tableName"] . " SET level=level+1 WHERE group_id=:group_id AND level>=:level;");
$stmt->bindValue(':group_id', $group_id);
$stmt->bindValue(':level', $level);
$stmt->execute();
//////////////////////////////////////////////


$stmt = $db->prepare("INSERT INTO " . $_board_options["tableName"] . " (group_id, depth, level, subject, writer, pwd, attach_file, content) VALUES (:group_id, :depth, :level, :subject, :writer, :pwd, :attach_file, :content)");
$stmt->bindValue(':group_id', $group_id);
$stmt->bindValue(':depth', ($depth + 1));
$stmt->bindValue(':level', $level);
$stmt->bindValue(':subject', $subject);
$stmt->bindValue(':writer', $writer);
$stmt->bindValue(':pwd', $pwd);
$stmt->bindValue(':attach_file', count($attachFiles));
$stmt->bindValue(':content', $content);
$stmt->execute();

$lastInsertId = $db->lastInsertId();
$stmt = $db->prepare("INSERT INTO attach_files (board_id, ori_file_name, save_file_name, file_size, mimetype) VALUES (:board_id, :ori_file_name, :save_file_name, :file_size, :mimetype)");
$stmt->bindParam(':board_id', $lastInsertId);
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

$db = null;
?>

<script>
    location.href='<?=$_board_options["listPage"]?>';
</script>


