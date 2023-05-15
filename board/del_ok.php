<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/common.php"; ?>
<?php
$idx = get('idx', '0');
if($idx == '0') {
?>
<script>alert('잘못된 접근 입니다.');location.href='<?=$_board_options["listPage"]?>';</script>
<?php
return;
}

$pwd = post('pwd');

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
        $group_id = $row['group_id'];
        $level = $row['level'];
    }
} else {
    ?>
    <script>alert('잘못된 접근 입니다.');location.href='/board/';</script>
    <?php
    return;
}
// 첨부파일 삭제하기
$upload_folder = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
$stmt = $db->prepare("SELECT * FROM attach_files WHERE board_id=:board_id;");
$stmt->bindValue(':board_id', $idx);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_BOTH)) {
    unlink($upload_folder . $row['save_file_name']);
}
$stmt = $db->prepare("DELETE FROM attach_files WHERE board_id=:board_id);");
$stmt->bindValue(':board_id', $idx);
$stmt->execute();
// 게시물 삭제하기
$stmt = $db->prepare("DELETE FROM " . $_board_options["tableName"] . " WHERE idx=:idx;");
$stmt->bindValue(':idx', $idx);
$stmt->execute();
// 순서 재정렬
$stmt = $db->prepare("UPDATE " . $_board_options["tableName"] . " SET level=level-1 WHERE group_id=:group_id AND level>=:level;");
$stmt->bindValue(':group_id', $group_id);
$stmt->bindValue(':level', $level);
$stmt->execute();

$q = get('q');
?>
<script>alert('게시물이 삭제되었습니다.');location.href='<?=$_board_options["listPage"]?>?q=<?=$q?>';</script>
<?php
$db = null;
?>