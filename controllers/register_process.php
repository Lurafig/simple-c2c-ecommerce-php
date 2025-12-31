<?php
include_once "../config/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === "POST"){
    session_regenerate_id(true);
    
    $_SESSION["name"] = $_SESSION["pending_name"];
    $_SESSION["email"] = $_SESSION["pending_email"];
    $_SESSION["password_hash"] = $_SESSION["pending_password_hash"];

    unset(
        $_SESSION["code"], $_SESSION["pending_name"],
        $_SESSION["attempts"], $_SESSION["pending_email"],
        $_SESSION["verify_expires"], $_SESSION["pending_password_hash"]
    );

    if(checkIfExists($_SESSION["email"])){
        unset($_SESSION["password_hash"]);
        exit;
    } else {
        insertIntoDB(
            $_SESSION["name"],
            $_SESSION["password_hash"],
            $_SESSION["email"],
        );
    }

    unset($_SESSION["password_hash"]);

    $_SESSION["logged"] = true;
}

function checkIfExists($email){
    global $conn;
    try{
        $query = "
            SELECT email FROM users WHERE email = :email
        ";

        $pdo = $conn->prepare($query);

        $pdo->bindParam(":email", $email, PDO::PARAM_STR);

        $pdo->execute();

        $results = $pdo->fetchAll(PDO::FETCH_ASSOC);

        if(count($results) == 1){
            return true;
        }
    }
    catch(PDOException $e){
        echo $e->getMessage();
        return true;
    }

    return false;
}

function insertIntoDB($name, $pass_hash, $email){
    global $conn;
    try{
        $query = "
            INSERT INTO users(username, senha, email)
            values(:name, :pass_hash, :email)
        ";

        $pdo = $conn->prepare($query);

        $pdo->execute([
            ':name' => $name,
            ':pass_hash' => $pass_hash,
            ':email' => $email,
        ]);

        $user_id = $conn->lastInsertId();

        $_SESSION["user_id"] = $user_id;
    }
    catch(PDOException $e){
        echo $e->getMessage();
        return false;
    }

    return true;
}