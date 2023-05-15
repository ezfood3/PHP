<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/common.php"; ?><?php

// 이메일
$email = get('email');

//이메일 중복 체크
$stmt = $db->prepare("SELECT * FROM " . $_user_options["tableName"] . " WHERE email=:email;");
$stmt->bindValue(':email', $email);
$stmt->execute();

if($row = $stmt->fetch(PDO::FETCH_BOTH)) {// 등록된 이메일
    echo 'false';
} else {
    echo 'true';
}
$db = null;
?>