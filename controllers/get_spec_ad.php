<?php
include_once "../config/db_connect.php";

if($_SERVER['REQUEST_METHOD'] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    print_r(
        json_encode([
            "infos" => getAdInfos($data["id"]),
            "comments" => getAdComments($data["id"])
            ]
        )
    );
}

function getAdInfos($id){
    global $conn;
    try {
        $query = "
            select user_id,title, product_value, group_concat(image_path separator ', ') as image_path from ads
            join images on images.produto_id = ads.id and ads.id = $id
            group by ads.id
        ";

        $prep = $conn->prepare($query);

        $prep->execute();

        $data_output_ad_data = $prep->fetch(pdo::FETCH_ASSOC);

        $query2 = "
            SELECT id, username from users
            where id = :userId
        ";

        $prep = $conn->prepare($query2);
        $prep->execute([
            ":userId" => $data_output_ad_data["user_id"]
        ]);
        
        $data_output_ad_owner = $prep->fetch(pdo::FETCH_ASSOC);
        
        return [
            "adInfos" => $data_output_ad_data,
            "ownerInfos" => $data_output_ad_owner
        ];

    } catch(PDOException $e){
        return [
            "err_code" => 1,
            "err_message" => $e
        ];
    }
}

function getAdComments($id){
    global $conn;
    try{
        $query = "
            SELECT user_id, ad_id, username, content FROM comments
            WHERE ad_id = :id
        ";

        $stmt = $conn->prepare($query);
        $stmt->execute([
            ":id" => $id
        ]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }
}