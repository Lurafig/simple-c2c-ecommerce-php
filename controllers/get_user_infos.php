<?php
require_once __DIR__ . '/../config/db_connect.php';

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $req_data = json_decode(file_get_contents("php://input"), true);
    
    $id = $req_data["id"];

    $userInfos = getUserInfos($id);
    $userAds = getUserAds($id);

    print_r(json_encode([
            "infos" => [...$userInfos],
            "ads" => $userAds
        ]
    ));
}

function getUserInfos($id){
    global $conn;
    try {
        $query = "
            SELECT username from users
            where id = :id
        ";

        $pdo = $conn->prepare($query);

        $pdo->execute([
            ":id" => $id
        ]);

        $result = $pdo->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo $e->getMessage();
    }

    return $result;
}
function getUserAds($id){
    global $conn;

    try{
        $query = "
            SELECT ads.id, title, product_value, user_id, substring_index(group_concat(image_path separator ' ,'), ' ,', 1) as image from ads
            join images on images.produto_id = ads.id and user_id = :id
            group by ads.id
        ";

        $pdo = $conn->prepare($query);
        $pdo->execute([
            ":id" => $id
        ]);

        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }

    return $result;
};

// print_r(json_encode([
//     "err_code" => 0,
//     "err_message" => "bruh"
// ]));