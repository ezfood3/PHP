<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/common.php"; ?>
<?php

function makeInsertUsersSql($db, $_user_options) {
    $txtSql = 'INSERT INTO' . $_user_options["tableName"];
    $txtSqlSub1 = join(',', $_user_options['insert_columns']);
    $txtSqlSub2 = ':' . join(', :', $_user_options['insert_columns']);
    $txtSql = 'INSERT INTO ' . $_user_options["tableName"] . '('.$txtSqlSub1.') VALUES ('.$txtSqlSub2.')';
    //echo $txtSql;
    $stmt = $db->prepare($txtSql);
    for($i=0; $i<count($_user_options['insert_columns']); $i++) {
        $temp = $_user_options['insert_columns'][$i];
        $stmt->bindValue(':'.$temp, post($temp));
    }
    $stmt->execute();
}

// 이메일
$email = post('email');
// 비밀번호
$pwd = post('pwd');
// 비밀번호 확인
$pwd2 = post('pwd2');
// 이름
$user_name = post('user_name');
// 전화번호
$hp = post('hp');

//이메일 중복 체크
$stmt = $db->prepare("SELECT * FROM " . $_user_options["tableName"] . " WHERE email=:email;");
$stmt->bindValue(':email', $email);
$stmt->execute();

if($row = $stmt->fetch(PDO::FETCH_BOTH)) {// 등록된 이메일
?>
<script>alert('이미 등록된 이메일 입니다.');history.back();</script>
<?php
    return;
}


// 데이터베이스 등록
makeInsertUsersSql($db, $_user_options);

$db = null;
?>

<script>
    location.href='<?=$_user_options["loginPage"]?>';
</script>


