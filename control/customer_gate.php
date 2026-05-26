<?php
function requireCustomer(){
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION["user_id"])){
        header("Location: ../view/login.php");
        exit();
    }
    if($_SESSION["role"] !== "customer"){
        header("Location: ../view/Home.php");
        exit();
    }
}

function requireCustomerApi(){
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    header("Content-Type: application/json");
    if(!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "customer"){
        echo json_encode(array("success" => false, "message" => "Customer login required"));
        exit();
    }
}
?>
