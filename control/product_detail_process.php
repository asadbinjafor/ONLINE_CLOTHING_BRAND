<?php
include_once '../model/mydb.php';
include_once 'auth.php';

if(session_status() == PHP_SESSION_NONE){ session_start(); }

$productId = (int)($_GET["id"] ?? 0);
if($productId <= 0){
    header("Location: ../view/Home.php");
    exit();
}

$mydb = new MyDB();
$conn = $mydb->createConn();
tryRememberLogin($mydb, $conn);

$result = $mydb->getProductById($productId, $conn);
if($result->num_rows == 0){
    $mydb->closeConn($conn);
    header("Location: ../view/Home.php");
    exit();
}

$product   = $result->fetch_assoc();
$cartCount = 0;
if(isset($_SESSION["user_id"]) && $_SESSION["role"] === "customer"){
    $cartCount = $mydb->getCartCount($_SESSION["user_id"], $conn);
}
?>
