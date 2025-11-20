<?php
include_once "../config/db_connect.php";

if($_SERVER['REQUEST_METHOD'] === "POST") {
    $reqBody = json_decode(file_get_contents("php://input"), true);

    $reqEmail = $reqBody["email"];
    $reqPass = $reqBody["password"];

    if(checkAccount($reqEmail, $reqPass)){
        print_r(json_encode([
            "success" => true,
        ]));

        exit;
    }

    print_r(json_encode([
        "success" => false,
        "message" => "Houve algum erro ao logar"
    ]));
}

function checkAccount($email, $reqPass){
    global $conn;
    try {
        $query = "
            SELECT id, username, email, senha FROM users
            WHERE email = :email
        ";

        $pdo = $conn->prepare($query);
        $pdo->execute([
            ":email" => $email,
        ]);

        $result = $pdo->fetch(PDO::FETCH_ASSOC);

        if($result && password_verify($reqPass, $result["senha"])){
            session_start();

            $_SESSION = [
                "logged" => true,
                "user_id" => $result["id"],
                "name" => $result["username"],
            ];

            return 1;
        }

        return 0;
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
}