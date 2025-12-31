<header>
        <nav>
            <div id = "main-page-menu">
                <?php if(basename($_SERVER['PHP_SELF']) != "post.php"): ?>
                    <a <?php echo $post_bttn_href ?> id = <?php echo $post_bttn_id ?> title = "Post Ad" class = "short_button">
                        <img src="/assets/AddA.png" alt="Add ad" id = "post-img" draggable="false">
                    </a>
                <?php endif; ?>
            </div>
            <div id = <?php echo $profile_ctn_id ?> >
                <div id = "profile-page-link">
                    <p id = "profile-name"><?php echo htmlspecialchars($username) ?></p>
                    <a id = "profile-img-ctn" class = "short_button" <?php echo $profile_href ?> title = "ir para o perfil">
                        <img src = "/assets/no-profile-pic.svg" id = "profile-img" alt = "profile-img" draggable="false">
                    </a>
                    <?php if($is_logged): ?>
                        <div id = "configs-ctn">
                            <div id = "account-configs">
                                <div id = "configs-arrow"></div>
                            </div>
                            <div id = "configs">
                                <div id = "logout" class = "config-option">
                                    <img src = "/assets/logout.svg" width="25" height="25" id = "logout-svg">
                                    Sair
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div id = "home-link-ctn">
                <a id = "logo-ctn" href = "/home">
                    <div id = "logo-img">Home</div>
                </a>
            </div>
        </nav>
    </header>
    <script src = "/src/scripts/header.js"></script>
