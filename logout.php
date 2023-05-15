<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/common.php"; ?>
<?php
$db = null;
session_destroy();
?>
<script>location.href='<?=$_board_options["listPage"]?>';</script>