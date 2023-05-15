<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/common.php"; ?>
<?php
if(strpos(take($_SERVER["HTTP_REFERER"]), "" . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/board/") === false) {
    header('HTTP/1.0 404 Not Found');
    die(); //exit(); return;
}

$id = get("id", 0);
if(!is_numeric($id) || !$id) {
    header('HTTP/1.0 404 Not Found');
    die(); //exit(); return;
}

// 게시물의 첨부파일 가져오기
$upload_folder = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
$stmt = $db->prepare("SELECT * FROM attach_files WHERE id=:id;");
$stmt->bindValue(':id', $id);
$stmt->execute();
if($row = $stmt->fetch(PDO::FETCH_BOTH)) {
    $attach_file = [
        "id" => $row["id"],
        "ori_file_name" => $row["ori_file_name"],
        "save_file_name" => $row["save_file_name"],
        "file_size" => $row["file_size"],
        "mimetype" => $row["mimetype"],
        "download_count" => $row["download_count"]
    ];
} else {
    header('HTTP/1.0 404 Not Found');
    die(); //exit(); return;
}

try {
    $fp = fopen($upload_folder . $attach_file["save_file_name"], "rb");
} catch(Exception $e) {
    header('HTTP/1.0 404 Not Found');
    die(); //exit(); return;
}

// 다운로드 카운팅
$stmt = $db->prepare("UPDATE attach_files SET download_count=download_count+1 WHERE id=:id;");
$stmt->bindValue(':id', $id);
$stmt->execute();

header("Content-type: file/unknown");
header("Content-Length: " . filesize($upload_folder . $attach_file["save_file_name"]));
header("Content-Disposition: attachment; filename=" . $attach_file["ori_file_name"]);
header("Pragma: no-cache");
header("Expires: 0");

fpassthru($fp);
fclose($fp);
?>