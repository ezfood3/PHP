<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/common.php"; ?>
<?php
// 페이지별 고유한 작업 처리 영역
$page_title = '로그인';
?>

<!doctype html>
<html>
    <head>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/_inc_page_head.php"; ?>
        <!-- 페이지별 헤더 작성 영역 -->
        <style>
        html,
        body {
        height: 100%;
        }

        body {
        display: flex;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
        }

        .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
        }

        .form-signin .form-floating:focus-within {
        z-index: 2;
        }

        .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        }

        </style>
    </head>
    <body>
        <div id="main" class="container-fluid h-100 px-0">
            <!-- start: global navigation bar -->
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/_inc_page_gnb.php"; ?>
            <!-- end: global navigation bar -->

            <!-- start: 페이지별 콘텐츠 영역 -->
            <div class="form-signin">
                <form action="login_ok.php" method="post" onsubmit="return chkForm();">
                    <h1 class="h3 mb-3 fw-normal">LOGIN</h1>

                    <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                    <label for="email">Email address</label>
                    </div>
                    <div class="form-floating">
                    <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Password">
                    <label for="pwd">Password</label>
                    </div>

                    <button class="w-100 btn btn-lg btn-primary" type="submit">LOGIN</button>
                </form>
            </div>
            <!-- end: 페이지별 콘텐츠 영역 -->

            <!-- start: footer -->
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_inc/_inc_page_footer.php"; ?>
            <!-- end: footer -->
            <script>
            // 페이지별 스크립트 작성 영역
            function chkForm() {
                let options = [];
                options.push({type: 'text', selector : '#email', regex: /^\s*$/, errorMsg: '아이디를 바르게 입력하세요.'});
                options.push({type: 'text', selector : '#pwd', regex: /^$/, errorMsg: '비밀번호를 바르게 입력하세요.'});
                return hoyaaCheckForm(options);
            }
            </script>
        </div>
    </body>
</html>