<?php
    $host = "localhost";
    $db = "ecommerce";
    $user = "root";
    $pass = "";

    try{
        $conn = new PDO(
            "mysql:host=$host;dbname=$db;charset=utf8", 
            $user, 
            $pass
        );

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        echo $e->getMessage();
    }
    
