<?php
include_once "../ejsConfig.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    session_start();

    $post_data = file_get_contents("php://input");
    $post_data_decoded = json_decode($post_data, true);

    $max_attempts = 3;

    if(isset($_SESSION["code"]) && isset($post_data_decoded["code"])) {
        $_SESSION["attempts"]++;
        $attempts_remaining = $max_attempts - $_SESSION["attempts"];
        $expired = time() > $_SESSION["verify_expires"];
        $is_equal = $_SESSION["code"] == $post_data_decoded["code"];
        
        $response = [
            "err_code" => 0,
            "err_message" => 0,
            "is_equal" => $is_equal,
            "attempts_remaining" => $attempts_remaining,
            "expired" => $expired
        ];

        print_r(json_encode($response));

        if($expired){
            unset(
                $_SESSION["code"], $_SESSION["pending_name"],
                $_SESSION["attempts"], $_SESSION["pending_email"],
                $_SESSION["verify_expires"], $_SESSION["pending_password_hash"]
            );
            exit;
        }

        if(!$attempts_remaining) {
            // echo "sem tentativas restantes";
            unset(
                $_SESSION["code"], $_SESSION["pending_name"],
                $_SESSION["attempts"], $_SESSION["pending_email"],
                $_SESSION["verify_expires"], $_SESSION["pending_password_hash"]
            );
            exit;
        }

        if($is_equal){
            // echo "é igual";
            include "register_process.php";
            exit;
        }

        exit;
    }

    if(
        !isset($post_data_decoded["name"])
        || !isset($post_data_decoded["email"])){
            print_r (json_encode([
                "err_code" => 1,
                "err_message" => "Nome e/ou email não fornecidos"
            ]));
            exit;
    }

    if(json_last_error() === JSON_ERROR_NONE){
        $randomNum = random_int(10, 99);
        
        $_SESSION["code"] = $randomNum;
        $_SESSION["attempts"] = 0;
        $_SESSION["verify_expires"] = time() + 60;

        $_SESSION["pending_name"] = $post_data_decoded["name"];
        $_SESSION["pending_email"] = $post_data_decoded["email"];
        $_SESSION["pending_password_hash"] = password_hash($post_data_decoded["pass"], PASSWORD_DEFAULT);

        $name = $post_data_decoded["name"];
        $email = $post_data_decoded["email"];

        $templateParams = [
            "to_name" => $name,
            "email" => $email,
            "message" => "Olá, $name, aqui está o seu código: $randomNum"
        ];

        $url = "https://api.emailjs.com/api/v1.0/email/send";

        $data = [
            "service_id" => $serviceID,
            "template_id" => $templateID,
            "user_id" => $publicKey,
            "accessToken" => $privateKey,
            "template_params" => $templateParams
        ];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        unset($templateParams["message"]);
        unset($data["template_params"]["message"]);
        
        // print_r(curl_getinfo($ch)["http_code"]);
        // print_r($data);

        if(curl_errno($ch)){
            print_r(json_encode(
                [
                        "err_code" => 1,
                        "err_message" => curl_error($ch)
                    ]
            ));
        } else {
            print_r(json_encode([
                "err_code" => 0,
                "expiration_time" => $_SESSION["verify_expires"],
                "err_message" => "Sucesso ao enviar email"
            ]));
        }

        curl_close($ch);
    }
}
