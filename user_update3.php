<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/login_user.php"; ?>
<?php
// 이메일
$email = session(SESSION_NAME_LOGIN_ID);
// 비밀번호
$pwd = post('pwd');
// 이름
$user_name = post('user_name');
// 전화번호
$hp = post('hp');

//회원정보수정
if($pwd == '') {
    $stmt = $db->prepare("UPDATE " . $_user_options["tableName"] . " SET user_name=:user_name, hp=:hp WHERE email=:email;");
    $stmt->bindValue(':user_name', $user_name);
    $stmt->bindValue(':hp', $hp);
    $stmt->bindValue(':email', $email);
} else {
    $stmt = $db->prepare("UPDATE " . $_user_options["tableName"] . " SET pwd=:pwd, user_name=:user_name, hp=:hp WHERE email=:email;");
    $stmt->bindValue(':pwd', $pwd);
    $stmt->bindValue(':user_name', $user_name);
    $stmt->bindValue(':hp', $hp);
    $stmt->bindValue(':email', $email);
}
$stmt->execute();
$db = null;
?>

<script>
    alert('회원정보가 수정되었습니다. 변경된 정보는 회원정보 수정 페이지에서 확인 해 주세요.');
    location.href='<?=$_board_options["listPage"]?>';
</script>


