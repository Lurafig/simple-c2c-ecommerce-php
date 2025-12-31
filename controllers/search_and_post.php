<?php ob_start(); ?>
<!-- 

L

-->
<?php ob_end_clean(); ?>
<?php
    require_once "../config/db_connect.php";

    function handlePostreq(){
        $func = [
            "g" => fn($a, $b, $c) => getAdsToHome($a, $b, $c),
            "p" => fn($a, $b, $c) => postAd($a, $b, $c)
        ];
        
        $jsonBody = file_get_contents("php://input");
        $data = json_decode($jsonBody, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            print_r(json_encode($func[$data["e"]](...json_decode($data["a"]))));
        } else {
            echo "Invalid JSON received.";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        session_start();
        handlePostreq();
    };
    
    function getAdsToHome(string|null $title, int|null $order, string|null $ids){
        $output = [];
        global $conn;
        try {
            $and_var = isset($order) || isset($title) ? "AND " : "WHERE ";

            $not_include_list = match(isset($ids)){
                true =>
                $and_var . "ads.id NOT IN " . "(" . str_replace(["[","]"], "", $ids) . ")",
                default => ""
            };
            
            $sort_str = match ($order) {
                2 => "product_value DESC",
                3 => "product_value ASC",
                4 => "insertion_date DESC",
                default => "RAND()"
            };

            $title_search = match ($title === null) {
                false => "where title like '%$title%'",
                default => ""
            };

            $query = "
                select ads.id, title, product_value, substring_index(group_concat(image_path separator ', '), ', ', 1) as image_path
                from ads
                join images on images.produto_id = ads.id
                $title_search $not_include_list
                group by ads.id
                order by $sort_str
                LIMIT 12;";
            
            $stmt = $conn->prepare($query);

            // if(isset($ids)){
            //     print $query;
            // }

            $stmt->execute();

            // if(isset($ids)){
            //     $stmt->debugDumpParams();
            // }

            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if($usuarios) {
                foreach ($usuarios as $usuario) {
                    $output[] = 
                    '{"id":' . $usuario["id"] .
                    ',"titulo": ' .
                    '"'. addcslashes(
                        str_replace("\n", "<br>", $usuario["title"]),
                        "\""
                        ) .
                    '"' . ',"preco":' . $usuario["product_value"] .
                    ',"image_path": ' . '"' . $usuario["image_path"] . '"' ."}";
                }
            } else {
                echo "";
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
        
        $output_str = json_encode($output);
        
        return $output_str;
    }

    function postAd(string $images, string $title, float $price){
        global $conn;
        $images_path = json_decode($images);
        if(count($images_path) > 5){
            return [
                "err_code" => 1,
                "err_message" => "Mais de 5 imagens enviadas"
            ];
        }
        try {
            $userId = $_SESSION["user_id"];

            $post_ad_query = "
                insert into ads(user_id, title, product_value)
                values($userId, :title, $price);
            ";

            $prep = $conn->prepare($post_ad_query);
            $prep->bindParam(":title", $title);
            $prep->execute();
            // $prep->debugDumpParams();
            
            $prep2 = $conn->prepare("select last_insert_id();");
            $prep2->execute();

            $id = $prep2->fetchAll(PDO::FETCH_ASSOC)[0]["last_insert_id()"];

            foreach($images_path as $path){
                $post_images_query = "
                    insert into images (produto_id, image_path)
                    values ($id, :path)
                ";

                $prep3 = $conn->prepare($post_images_query);
                $prep3->bindParam(":path", $path);
                // $prep3->debugDumpParams();
                $prep3->execute();
            }
        } catch (PDOException $e) {
            return [
                "err_code" => 1,
                "err_message" => "Houve algum erro ao postar: " . $e->getCode()
            ];
        }

        return [
            "err_code" => 0,
            "imagens" => $images,
            "titulo" => $title,
            "preco" => $price
        ];
    }
?>
