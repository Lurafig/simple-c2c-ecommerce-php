<?php
include_once "includes/auth_check.php";

$profile_id = $_GET["id"] ?? 0;

if(!$is_logged) {
    if(!$profile_id){
        header("Location: http://localhost/home");
        exit;
    }

    include_once "controllers/get_user_infos.php";

    $profilename = "";
}

if($is_logged) {
    if(!$profile_id){
        $profile_id = $_SESSION["user_id"];
        $profilename = $_SESSION["name"];
        $removeBttn = true;
    } else {
        $profilename = "";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "/src/styles/ProfilePage.css?v=1.0">
    <link rel = "icon" href = "/assets/connteic.png" type = "image/x-icon" >
    <title>Profile | Connte</title>
</head>
<body>
    <template id = "ad_ctn_template">
        <div class = "main-ad-ctn">
            <a class = "ad-ctn">
                <img class = "img-ctn">
                <div class = "ad-infos">
                    <h2 class = "ad_title">
                    </h2>
                    <h3 class = "ad-price">
                    </h3>
                </div>
            </a>
            <?php if(isset($removeBttn)): ?>
                <div class = "delete" title = "deletar anúncio">X</div>
            <?php endif;?>
        </div>
    </template>
    <?php include_once "includes/header.php"?>
    <main>
        <div id = "profile-infos">
            <div id = "profile-user-img-ctn">
                <img id = "user-profile-img" src = "/assets/no-profile-pic.svg" draggable="false">
            </div>
            <div id = "">
                <h2 id = "user-name"><?php echo $profilename ?></h2>
            </div>
        </div>
        <div id = "profile-ads">
            <h1 id = "ads-ctn-title">Anúncios</h1>
            <div id = "ads-list">
                <div id = "not-found">
                    <img src = "\assets\icons8-limpar-pesquisa-30.png" height = "30" width = "30">
                    <h4>Nenhum anúncio encontrado!</h4>
                </div>
            </div>
        </div>
    </main>
    <?php 
        if(!$is_logged){
            include_once "includes/login_ctn.php";
        }
    ?>
</body>
<script type = "application/javascript" src = "/src/scripts/profilePage.js"></script>
<script>
    ( async() => {
        const response = await getUserInfos(<?php echo $profile_id ?>)
            .then(response => {
                console.log(response);
                return response;
        })

        insertUserInfos(response.infos);

        if(response.ads.length == 0){
            document.getElementById("not-found").style.display = "flex";
        } else {
            insertAds(response.ads);
        }
    })();
</script>
</html>