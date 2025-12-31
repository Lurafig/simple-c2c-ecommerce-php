<?php
include_once __DIR__ . "/../config/db_connect.php";

if($_SERVER['REQUEST_METHOD'] === "POST"){
    session_start();
    
    $reqBody = json_decode(file_get_contents("php://input"), true);

    $adId = $reqBody["adid"];
    $cmntContent = $reqBody["content"];

    print_r(json_encode(
        postComment($adId, $cmntContent)
    ));
}

function postComment($adId, $content) {
    global $conn;
    try{
        $query = "
            INSERT INTO comments(user_id, ad_id, username, content)
            VALUES (:userId, :adId, :username, :content)
        ";

        $stmt = $conn->prepare($query);

        $stmt->execute([
            ":userId" => $_SESSION["user_id"],
            ":username" => $_SESSION["name"],
            ":adId" => $adId,
            ":content" => $content
        ]);

        // $stmt->debugDumpParams();

        $last_id = $conn->lastInsertId();

        $stmt = $conn->prepare("SELECT * FROM comments WHERE id = :id");
        $stmt->execute([':id' => $last_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
}