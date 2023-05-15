<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/common.php"; ?>
<?php
// 페이지별 고유한 작업 처리 영역
$page_title = $_board_options["name"];
$idx = get('idx', '0');
if($idx == '0') {
?>
<script>alert('잘못된 접근 입니다.');location.href='/board/';</script>
<?php
return;
}
// 게시물 가져오기
$stmt = $db->prepare("SELECT * FROM " . $_board_options["tableName"] . " WHERE idx=:idx;");
$stmt->bindValue(':idx', $idx);
$stmt->execute();

if($row = $stmt->fetch(PDO::FETCH_BOTH)) {
    // 게시물이 있을때는 할일 없음.....
} else {
?>
    <script>alert('잘못된 접근 입니다.');history.back();</script>
<?php
    return;
}

$q = get('q');

$db = null;
?>

<!doctype html>
<html>
    <head>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/_inc_page_head.php"; ?>
        <!-- 페이지별 헤더 작성 영역 -->
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="me-2" viewBox="0 0 118 94" role="img"><title>Bootstrap</title><path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z" fill="currentColor"></path></svg>
                    <span class="fs-4"><?=$_board_options["name"]?> 삭제 확인</span>
                    </a>
                </header>
            </div>

            <div>
                <form class="px-5" action="del_ok.php?idx=<?=$idx?>&q=<?=$q?>" method="post" onsubmit="return chkForm();">
                <input type="hidden" name="idx" value="<?=$idx?>" />
                    
                    <div class="col-12 mb-3">
                        <label for="pwd" class="form-label">비밀번호</label>
                        <input type="password" class="form-control" id="pwd" name="pwd" placeholder="비밀번호를 입력하세요" required>
                        <div class="invalid-feedback"></div>
                    </div>  
                    <div>
                        <button class="btn btn-secondary" onclick="history.back();">취소</button>
                        <input class="btn btn-primary" type="submit" value="전송" />
                    </div>
                </form>
            </div>
            <!-- end: 페이지별 콘텐츠 영역 -->

            <!-- start: footer -->
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/_inc_page_footer.php"; ?>
            <!-- end: footer -->
            <script>
            // 페이지별 스크립트 작성 영역
            function chkInputTypeText(selector, regex, errorMsg) {
                let ele = document.querySelector(selector);
                let value = ele.value;
                if(regex.test(value)) {
                    alert(errorMsg);
                    ele.focus();
                    return false;
                }
                return true;
            }
            function chkForm() {
                // 비밀번호 체크
                if(!chkInputTypeText('#pwd', /^\D*$/, '비밀번호는 숫자만 입력하세요.')) {
                    return false;
                }
                return true;
            }
            </script>
        </div>
    </body>
</html>