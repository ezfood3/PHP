<?php
    session_start();
?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/_inc_global_const.php"; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/func.php"; ?>
<?php 
if(!IS_LOGIN_USER) {
?><script>alert('로그인을 해주세요.');location.href='<?=$_user_options['loginPage']?>';</script><?php
return;
} 
?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/db.php"; ?>