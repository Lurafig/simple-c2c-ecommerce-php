<?php
include_once "includes/auth_check.php";
$search = $_GET["search"] ?? 0;
$sortby = $_GET["sortby"] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel = "stylesheet" href = "./src/styles/HomeStyle.css?v=1.0">
        <link rel = "icon" href = "assets/connteic.png" type = "image/x-icon">
        <title>Home | Connte</title>
    </head>
    <template><a href = "" class = "ad-ctn"><img src = "" class = "image-uploaded" alt = "ad image" draggable="false" ><div class = "ad-info"><h2></h2><h4></h3></div></a></template>
    <body>
        <?php include_once "includes/header.php" ?>
        <main>
            <div id = "search_options_ctn">
                <form id = "search-for-titles" method="GET">
                    <input type = "text" name = "search" id = "search_ads" placeholder = "Busque anúncios" title = "Buscar por anúncios" autocomplete="off">
                    <button type = "submit" id = "search-button">
                        <img src = "assets/icons8-search.svg" style = "color: green">
                    </button>
                </form>
                <div id = "select-order-ctn">
                    <form id = "selection-order-form" method = "GET">
                        <input type="hidden"
                        <?php
                            switch($search){
                                case 0: echo ""; break;
                                default: echo "name = 'search' value = '$search'";
                            } ?>
                        >
                        <select id = "select-ads-order" name = "sortby">
                            <option hidden>Ordenar Por:</option>
                            <option value = "1">Aleatório</option>
                            <option value = "2">Mais Caro</option>
                            <option value = "3">Mais Barato</option>
                            <option value = "4">Mais recente</option>
                        </select>
                    </form>
                </div>
            </div>
            <div id = "ads-home-ctn">
                <div id = "not-found">
                    <img src = "assets\icons8-limpar-pesquisa-30.png" height = "30" width = "30">
                    <h4>Nenhum anúncio encontrado!</h4>
                </div>
                <div class = "loading-running" id = "loading-home"></div>
                <noscript>
                    <p style = "text-align:center">
                        Please enable JavaScript to view this content.
                    </p>
                </noscript>
            </div>
        </main>
        <?php
            if(!$is_logged){
                include "includes/login_ctn.php";
            }
        ?>
        <footer id = "creator_mention">
            <p>
                &copy; <?= date("Y") ?>
                Made by
            </p>
            <a href = "https://github.com/Lurafig"
            style = "color: var(--secondary-color)">
                Lucas Ramos
            </a>
        </footer>
        <a id = "up" class = "short_button">
            <img src = "assets/up-arrow.png" alt = "up">
        </a>
        <script type = "module" src = "../src/scripts/OrganizeHome.js"></script>
        <script type = "module" src = "../src/scripts/script.js"></script>
        <script type = "module">
            import StartHome from "../src/scripts/OrganizeHome.js";
            const [ search, sort ] = [
                "<?php echo $search ?>",
                Number("<?php echo $sortby ?>")
            ];
            const [ haveSearch, haveSort ] = [ search != "0", sort != 0 ];

            if (haveSort) document.getElementById("select-ads-order").value = sort;

            document.getElementById("search_ads").value = haveSearch ? search : "";
            
            const y = [
                "g", 
                [ haveSearch 
                ? search : null, 
                haveSort
                ? sort : null ]
            ];

            const loading = document.getElementById("loading-home");
            const conteiner = document.getElementById("ads-home-ctn");
            
            let repeated = false;
            let no_more_repeat = false;

            let totalScroll = document.documentElement.scrollHeight - window.innerHeight;
            let loadPoint = totalScroll - 5;
            
            let more = true;

            while(!totalScroll && more){
                const a = await new Promise((resolve) => {
                    StartHome(...y)
                        .then((re) => {
                            loading.className = "loading-paused";
                            conteiner.append(loading);
                            resolve(re);
                        });
                });

                totalScroll = document.documentElement.scrollHeight - window.innerHeight;
                loadPoint = totalScroll - 5;

                if(a == "[]") more = false;
            }
            
            window.onresize = () => {
                totalScroll = document.documentElement.scrollHeight - window.innerHeight;
                console.log(totalScroll);
                loadPoint = totalScroll - 5;
                if (!totalScroll && !repeated){
                    repeated = true;
                    StartHome(...y)
                        .then((re) => {
                            loading.className = "loading-paused";
                            
                            if(re === "[]"){
                                console.log("não vai mais repetir")
                                no_more_repeat = true;
                            }

                            conteiner.append(loading);
                            totalScroll = document.documentElement.scrollHeight - window.innerHeight;
                            loadPoint = totalScroll - 5;
                           
                            repeated = false;
                    });
                }
            }

            window.onscroll = (event) => {
                if(
                    (window.scrollY).toFixed(1) > loadPoint
                    && !repeated
                    && !no_more_repeat
                ){
                    repeated = true;
                    StartHome(...y)
                        .then((re) => {
                            loading.className = "loading-paused";
                            
                            if(re === "[]"){
                                console.log("não vai mais repetir")
                                no_more_repeat = true;
                            }

                            conteiner.append(loading);
                            totalScroll = document.documentElement.scrollHeight - window.innerHeight;
                            loadPoint = totalScroll - 5;
                           
                            repeated = false;
                    });
                }
            }
        </script>
    </body>
</html>