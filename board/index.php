<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/common.php"; ?>
<?php
// 페이지별 고유한 작업 처리 영역
$page_title = $_board_options["name"];

// 페이지당 게시물 수
$page_max_rows = 15;
// 현재 페이지 번호
$page_no = (int)(get('page_no', 1));

// 쿼리에서 시작 인덱스
$sql_start_index = ($page_no - 1) * $page_max_rows;

// 검색어 처리
$q = get('q');
$sql_where = '';
$bindValue = '';
if ($q != '') {
    $sql_where = ' WHERE (subject LIKE :subject) OR (writer LIKE :writer) OR (content LIKE :content)';
    $bindValue = "%" . $q . "%";
}

// 전체 게시물 수
$sql = "SELECT COUNT(*) FROM " . $_board_options["tableName"] . ";";
$stmt1 = $db->prepare($sql);
$stmt1->execute();
$total_count = $stmt1->fetchColumn();

// 검색된 게시물 수
$sql = "SELECT COUNT(*) FROM " . $_board_options["tableName"] . $sql_where . ";";
$stmt1 = $db->prepare($sql);
if ($q != '') {
    $stmt1->bindParam(':subject', $bindValue, PDO::PARAM_STR);
    $stmt1->bindParam(':writer', $bindValue, PDO::PARAM_STR);
    $stmt1->bindParam(':content', $bindValue, PDO::PARAM_STR);
}
$stmt1->execute();
$search_count = $stmt1->fetchColumn();

// 검색된 게시물 목록
$stmt2 = $db->prepare("SELECT * FROM " . $_board_options["tableName"] . $sql_where . " ORDER BY group_id DESC, level ASC LIMIT " . $sql_start_index . ", " . $page_max_rows . ";");
if ($q != '') {
    $stmt2->bindParam(':subject', $bindValue, PDO::PARAM_STR);
    $stmt2->bindParam(':writer', $bindValue, PDO::PARAM_STR);
    $stmt2->bindParam(':content', $bindValue, PDO::PARAM_STR);
}
$stmt2->execute();
$count = $stmt2->rowCount();

$article_list = [];
while ($row = $stmt2->fetch(PDO::FETCH_BOTH)) {
    $article_row = [
        "idx" => $row["idx"],
        "group_id" => $row["group_id"],
        "depth" => $row["depth"],
        "level" => $row["level"],
        "subject" => $row["subject"],
        "writer" => $row["writer"],
        "pwd" => $row["pwd"],
        "viewCount" => $row["viewCount"],
        "attach_file" => $row["attach_file"],
        "created_at" => $row["created_at"],
        "content" => $row["content"],
    ];
    $article_list[] = $article_row;
}

// 페이징에 필요한 계산
$prev_page_no = max($page_no - 1, 1);
$max_page_no = 1;
if ($search_count > $page_max_rows) {
    $max_page_no = floor(($search_count - 1) / $page_max_rows) + 1;
}
$next_page_no = min($page_no + 1, $max_page_no);

$page_block = 10;
$start_page_block = floor(($page_no - 1) / $page_block) * $page_block + 1;
$end_page_block = min($start_page_block + $page_block - 1, $max_page_no);

$db = null;
?>

<!doctype html>
<html>

<head>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/_inc_page_head.php"; ?>
    <!-- 페이지별 헤더 작성 영역 -->
    <style>
        .td-no {
            width: 60px;
            text-align: center;
        }

        .td-writer {
            width: 120px;
            text-align: center;
        }

        .td-attach-file {
            width: 60px;
            text-align: center;
        }

        .td-view-count {
            width: 80px;
            text-align: center;
        }

        .td-created-at {
            width: 200px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div id="main" class="container-fluid h-100 px-0">
        <!-- start: global navigation bar -->
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/_inc_page_gnb.php"; ?>
        <!-- end: global navigation bar -->

        <!-- start: 페이지별 콘텐츠 영역 -->
        <div class="px-5 pt-3">
            <header class="d-flex align-items-center pb-3 mb-1">
                <a href="/" class="d-flex align-items-center text-dark text-decoration-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="me-2" viewBox="0 0 118 94" role="img">
                        <title>Bootstrap</title>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z" fill="currentColor"></path>
                    </svg>
                    <span class="fs-4"><?= $_board_options["name"] ?> (페이지: <?= $page_no ?> / <?= $max_page_no ?>) (게시물수: <?= count($article_list) ?>/<?= $search_count ?>/<?= $total_count ?>건)</span>
                </a>
            </header>
        </div>

        <div class="w-100 px-3">
            <div class="col-3 offset-9">
                <div class="input-group input-group-sm mb-3">
                    <button class="btn btn-secondary" type="button" onclick="golist();">전체보기</button>
                    <input type="text" id="query" class="form-control" value="<?= $q ?>" placeholder="검색어 입력">
                    <button class="btn btn-outline-secondary" type="button" onclick="search();">찾기</button>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="td-no">#</th>
                        <th class="td-subject">제목</th>
                        <th class="td-writer">작성자</th>
                        <th class="td-attach-file">첨부</th>
                        <th class="td-view-count">조회수</th>
                        <th class="td-created-at">등록일시</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($article_list); $i++) {
                    ?>
                        <tr>
                            <td class="td-no"><?= ($sql_start_index + $i + 1) ?></td>
                            <td class="td-subject">
                                <?php for ($j = 0; $j < $article_list[$i]["depth"]; $j++) {
                                    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                } ?>
                                <?php if ($article_list[$i]["depth"] > 0) {
                                    echo "└&gt;&nbsp;";
                                } ?>
                                <a href="<?= $_board_options["viewPage"] ?>?idx=<?= $article_list[$i]["idx"] ?>&q=<?= $q ?>">
                                    <?= $article_list[$i]["subject"] ?>
                                </a>
                            </td>
                            <td class="td-writer"><?= $article_list[$i]["writer"] ?></td>
                            <td class="td-attach-file"><?= $article_list[$i]["attach_file"] ?></td>
                            <td class="td-view-count"><?= $article_list[$i]["viewCount"] ?></td>
                            <td class="td-created-at"><?= $article_list[$i]["created_at"] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <div>
                <a href="?page_no=1&q=<?= $q ?>" class="btn btn-sm btn-secondary">첫페이지</a>
                <a href="?page_no=<?= $prev_page_no ?>&q=<?= $q ?>" class="btn btn-sm btn-secondary">이전</a>
                <?php for ($i = $start_page_block; $i <= $end_page_block; $i++) { ?>
                    <a href="?page_no=<?= $i ?>&q=<?= $q ?>" class="btn btn-sm btn<?php if ($page_no != $i) {
                                                                                        echo '-outline';
                                                                                    } ?>-secondary"><?= $i ?></a>
                <?php } ?>
                <a href="?page_no=<?= $next_page_no ?>&q=<?= $q ?>" class="btn btn-sm btn-secondary">다음</a>
                <a href="?page_no=<?= $max_page_no ?>&q=<?= $q ?>" class="btn btn-sm btn-secondary">끝페이지</a>
            </div>
            <div>
                <a class="btn btn-primary btn-sm" href="<?= $_board_options["writePage"] ?>">새글</a>
            </div>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <div id="aaa">여기에요.</div>

        </div>
        <!-- end: 페이지별 콘텐츠 영역 -->

        <!-- start: footer -->
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/_inc_page_footer.php"; ?>
        <!-- end: footer -->
        <script>
            // 페이지별 스크립트 작성 영역
            function golist() {
                location.href = `<?= $_board_options['listPage'] ?>`;
            }

            function search() {
                let options = [];
                options.push({
                    type: 'text',
                    selector: '#query',
                    regex: /^.{0,1}$/,
                    isFocus: true,
                    errorMsg: '검색어를 2글자 이상 입력하세요.'
                });
                let result = hoyaaCheckForm(options);
                let q = document.querySelector('#query').value;
                q = encodeURIComponent(q);
                location.href = `?q=${q}`;
            }
        </script>
    </div>
</body>

</html>