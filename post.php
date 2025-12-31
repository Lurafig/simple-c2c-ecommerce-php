<?php
include_once "includes/auth_check.php";
if(!$is_logged){
    header("Location: http://localhost/home");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel = "stylesheet" href = "./src/styles/PostAd.css?v=1.0">
        <link rel = "icon" href = "assets/connteic.png" type="image/x-icon">
        <title>Post | Connte</title>
    </head>
    <body>
        <?php include_once "includes/header.php" ?>
        <main>
            <div id = "send-ad">
                <div id = "show-imgs-arrows-ctn">
                    <div id = "show-images-ctn">
                        <div id = "upload-images-ctn">
                            <div class = "upload-image-ctn">
                                <form 
                                style = "z-index:1"
                                id = "file"
                                action = "/controllers/upload.php"
                                method = "post"
                                enctype="multipart/form-data">
                                    <input
                                    type="file"
                                    id="file1"
                                    name="file1[]"
                                    accept=".jpg, .jpeg, .png"
                                    hidden
                                    multiple>
                                    <button type = "submit" id = "submitBttn" hidden></button>
                                    <button type = "button" id = "upload-image-bttn" class = "wide_button" title="Escolher imagens">Escolher imagens</button>
                                </form>
                                <img class = "uploaded-image-to-post">
                            </div>
                            <div class = "upload-image-ctn">
                                <img class = "uploaded-image-to-post">
                            </div>
                            <div class = "upload-image-ctn">
                                <img class = "uploaded-image-to-post">
                            </div>
                            <div class = "upload-image-ctn">
                                <img class = "uploaded-image-to-post">
                            </div>
                            <div class = "upload-image-ctn">
                                <img class = "uploaded-image-to-post">
                            </div>
                        </div>
                    </div>
                </div>
                <div id = "info-and-submit-ctn">
                    <div id = "info-ctn">
                        <form id = "form-ctn" action = "db.php" method = "POST">
                            <div id = "title_and_maxChars" style = "position:relative; width: 100%">
                                <textarea id = "title" name = "title" title = "Insira o titulo" placeholder = "Titulo" autocomplete="off" maxlength="250" required></textarea>
                                <span id = "char-count">
                                    <span id = "curr-text-len">0</span>
                                    /
                                    <span id = "maxlen"></span>
                                </span>
                            </div>
                            <input id = "price" name = "price" title = "Insira o preço" placeholder = "Preço" type = "text" autocomplete="off" required>
                        </form>
                    </div>
                    <div id = "post-or-cancel">
                        <button class = "wide_button" id = "send_bttn">Enviar</button>
                        <button class = "wide_button" id = "cancel_bttn">Cancelar</button>
                    </div>
                    <p id = "advice">a</p>
                </div>
            </div>
        </main>
        <?php
            if(!$is_logged){
                include_once "includes/login_ctn.php";
            }
        ?>
    </body>
    <script src = "../src/scripts/Loginctn.js"></script>
    <script src = "../src/scripts/OrganizePostAd.js"></script>
</html>