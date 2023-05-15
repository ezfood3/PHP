            <header class="p-3 bg-dark text-white">
                <div class="container-fluid">
                    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                        <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
                    </a>

                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                        <li><a href="#" class="nav-link px-2 text-white">사이트소개</a></li>
                        <li><a href="#" class="nav-link px-2 text-white">Features</a></li>
                        <li><a href="#" class="nav-link px-2 text-white">Pricing</a></li>
                        <li><a href="<?=$_board_options['listPage']?>" class="nav-link px-2 text-white"><?=$_board_options["name"]?></a></li>
                        <li><a href="#" class="nav-link px-2 text-white">About</a></li>
                    </ul>

                    <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                        <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
                    </form>

                    <div class="text-end">
                        <?php if(IS_LOGIN_USER) { ?>
                            <button type="button" class="btn btn-outline-light me-2" onclick="location.href='/user_update1.php';"><?=session(SESSION_NAME_LOGIN_ID)?></button>
                            <button type="button" class="btn btn-warning" onclick="location.href='/logout.php';">LOG-OUT</button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-outline-light me-2" onclick="location.href='/login.php';">Login</button>
                            <button type="button" class="btn btn-warning" onclick="location.href='/join.php';">Sign-up</button>
                        <?php }  ?>
                    </div>
                    </div>
                </div>
            </header>