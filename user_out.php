<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/login_user.php"; ?>
<?php
// 비밀번호 체크를 먼저 해주고 틀리면 다시 비밀번호를 입력하도록 페이지 이동
// 비밀번호확인을 위해 회원정보 가져오기
$stmt = $db->prepare("SELECT * FROM " . $_user_options["tableName"] . " WHERE email=:email;");
$stmt->bindValue(':email', session(SESSION_NAME_LOGIN_ID));
$stmt->execute();
if($row = $stmt->fetch(PDO::FETCH_BOTH)) {
    if($row['pwd'] != post('pwd')) {
        ?>
        <script>alert('비밀번호가 일치하지 않습니다.');history.back();</script>
        <?php
        return;
    } else {
        // 탈퇴처리
        $stmt = $db->prepare("UPDATE " . $_user_options["tableName"] . " SET out_at=now() WHERE email=:email;");
        $stmt->bindValue(':email', session(SESSION_NAME_LOGIN_ID));
        $stmt->execute();
        ?>
        <script>alert('그동안 이용해 주셔서 감사합니다.');location.href='<?=$_user_options["logoutPage"]?>';</script>
        <?php
        return;
    }
} else {
    ?>
    <script>alert('잘못된 접근입니다.');location.href='<?=$_user_options["logoutPage"]?>';</script>
    <?php
    return;
}
?>