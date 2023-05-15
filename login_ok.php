<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/common.php"; ?>
<?php
$email = post('email');
$pwd = post('pwd');
if($email == '' || $pwd == '') {
?>
<script>alert('잘못된 접근 입니다.');location.href='<?=$_board_options["listPage"]?>';</script>
<?php
return;
}

// 비밀번호확인을 위해 게시물 가져오기
$stmt = $db->prepare("SELECT * FROM " . $_user_options["tableName"] . " WHERE out_at IS NULL AND email=:email;");
$stmt->bindValue(':email', $email);
$stmt->execute();
if($row = $stmt->fetch(PDO::FETCH_BOTH)) {
    if($row['pwd'] != $pwd) {
        ?>
        <script>alert('입력된 정보와 일치하는 회원정보가 없습니다.');location.href='<?=$_user_options["loginPage"]?>';</script>
        <?php
        return;
    }
} else {
    ?>
    <script>alert('입력된 정보와 일치하는 회원정보가 없습니다.');location.href='<?=$_user_options["loginPage"]?>';</script>
    <?php
    return;
}
// 로그인 성공
session(SESSION_NAME_LOGIN_ID, $email);
?>
<script>location.href='<?=$_board_options["listPage"]?>';</script>
<?php
$db = null;
?>