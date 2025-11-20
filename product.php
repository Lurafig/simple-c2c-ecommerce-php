<?php
include_once "includes/auth_check.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Anúncio | Connte</title>
        <link rel = "stylesheet" href = "/src/styles/ProductStyle.css">
        <link rel = "icon" href = "/assets/connteic.png" type = "image/x-icon">
    </head>
    <body>
        <template id = "comment_ctn_template">
            <div class = "comment">
                <a class = "username"></a>
                <p class = "content"></p>
            </div>
        </template>
        <?php include_once "includes/header.php" ?>
        <main>
            <div id = "product-infos">
                <div id = "images">
                </div>
                <h1 id = "title"></h1>
                <h2 id = "price"></h2>
            </div>
            <div id = "ad-owner-infos">
                <a id = "owner-user-profile-link">
                    <div id = "ad-owner-pic-border">
                        <img id = "ad-owner-pic" src = "/assets/no-profile-pic.svg" width = "35" height="35" >
                    </div>
                    <p id = "ad-owner-username">abcd</p>
                </a>
            </div>
            <div id = "comments-section">
                <h3 id = "comments-section-start">Comentários</h3>
                <div id = "comment-form-ctn">
                    <form id = "comment-form" action = "/controllers/post_comment.php" method = "POST">
                        <input type = "text" id = "comment-input" placeholder="Adicione seu comentário" autocomplete="off">
                        <button type = "submit" class = "wide_button" id = "send-comment-bttn">
                            Comment
                        </button>
                    </form>
                </div>
                <div id = "comments">
                </div>
            </div>
        </main>
        <?php
            if(!$is_logged){
                include "includes/login_ctn.php";
            }
        ?>
        <script type = "module" src = "/src/scripts/productPage.js"></script>
        <script type = "module">
            import getAdInfo, { putInfos, putComments, postComment, InsertComment, insertUserInfos } from "../src/scripts/productPage.js";

            const adId = <?php echo $_GET["id"] ?>;

            const response = await getAdInfo(adId)
                .then(resp => {
                    putInfos(resp.infos.adInfos);
                    insertUserInfos(resp.infos.ownerInfos)
                    putComments(resp.comments);
                    return "Carregado";
                });

            const comment_form = document.getElementById("comment-form");
            
            comment_form.addEventListener("submit", (event) => {
                postComment(adId)
                    .then(response => {
                        console.log(response);
                        document.getElementById("comment-input").value = "";
                        InsertComment(response);
                    })

                event.preventDefault();
            });

            console.log(response);
        </script>
    </body>
</html>
<?php 
// $host = "localhost";
// $db = "ecommerce";
// $user = "root";
// $pass = "7q6b1achz6qxLDtmxSo5";

// $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

// $id = $_GET["id"] ?? 0;

// $query = "SELECT * FROM ads WHERE id = :id";
// $stmt = $pdo->prepare($query);
// $stmt->bindParam(":id",$id);
// $stmt->execute();
// $produto = $stmt->fetch();

// if ($produto) {
//     echo $produto["id"] . $produto["title"];
// }