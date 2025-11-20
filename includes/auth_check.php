<?php
session_start();

$is_logged = isset($_SESSION["logged"]) ? true : false;

if(!$is_logged){
    $post_bttn_href = "";
    $post_bttn_id = "post-img-ctn-login";
    $profile_ctn_id = "do-login";
    $profile_href = "";
    $username = "Logar";
} else {
    $post_bttn_href = "href=/post";
    $post_bttn_id = "post-img-ctn";
    $profile_ctn_id = "profile-home-ctn";
    $profile_href = "href=/profile";
    $username = $_SESSION["name"];
}