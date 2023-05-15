<?php 
$dbConnectionString = DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
$db = new PDO($dbConnectionString, DB_LOGIN_ID, DB_LOGIN_PWD);
?>