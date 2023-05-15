<?php
// 데이터베이스 연결정보
const DB_DRIVER = 'mysql';
const DB_HOST = 'localhost';
const DB_NAME = 'myhome';
const DB_LOGIN_ID = 'myhome';
const DB_LOGIN_PWD = 'myhome1234';

// 세션명에 관련된 상수
const SESSION_NAME_LOGIN_ID = 'login_email';
define("IS_LOGIN_USER", isset($_SESSION[SESSION_NAME_LOGIN_ID]) && $_SESSION[SESSION_NAME_LOGIN_ID] !='');

// 기타 상수

// DB 테이블 관련 변수들
$_site_options = [
    "board" => [
        "name" => "자유게시판",
        "tableName" => "board1",
        "listPage" => "/board/index.php",
        "writePage" => "/board/write.php",
        "writeOkPage" => "/board/write_ok.php",
        "viewPage" => "/board/view.php",
        "modifyPage" => "/board/modify.php",
        "modifyOkPage" => "/board/modify_ok.php",
        "delPage" => "/board/del.php",
    ],
    "faq" => [
        "name" => "자주하는 질문",
        "tableName" => "faq1",
    ],
    "user" => [
        "name" => "회원정보",
        "tableName" => "users",
        "joinPage" => "/join.php",
        "loginPage" => "/login.php",
        "logoutPage" => "/logout.php",
        "insert_columns" => ['email', 'pwd', 'user_name', 'hp'],
    ],
];
$_board_options = $_site_options['board'];
$_user_options = $_site_options['user'];
?>