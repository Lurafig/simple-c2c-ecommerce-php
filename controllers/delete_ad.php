<?php
include_once __DIR__ . "/../config/db_connect.php";

if($_SERVER['REQUEST_METHOD'] === "POST"){
    session_start();
    $reqBody = json_decode(file_get_contents("php://input"), true);

    $id = $reqBody["id"];

    print_r(
        json_encode(removeAd($id))
    );
}

function removeAd($id){
    global $conn;
    try {
        $query = "
            DELETE from ads
            where id = :id and user_id = :userId
        ";

        $stmt = $conn->prepare($query);

        $stmt->execute([
            ":id" => $id,
            ":userId" => $_SESSION["user_id"]
        ]);

        $rowAffected = $stmt->rowCount();

        if(!$rowAffected){
            return [
                "success" => false
            ];
        }

        return [
            "success" => true,
            "result" => $rowAffected
        ];
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
}