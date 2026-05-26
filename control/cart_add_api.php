<?php
include_once '../model/mydb.php';
include_once 'auth.php';
include_once 'customer_gate.php';

if(session_status() == PHP_SESSION_NONE){ session_start(); }
requireCustomerApi();

if(!isset($_POST["product_id"]) || !isset($_POST["quantity"])){
    echo json_encode(array("success" => false, "message" => "Missing parameters"));
    exit();
}

$productId = (int)$_POST["product_id"];
$quantity  = (int)$_POST["quantity"];

if($productId <= 0){
    echo json_encode(array("success" => false, "message" => "Invalid product"));
    exit();
}
if($quantity <= 0){
    echo json_encode(array("success" => false, "message" => "Quantity must be at least 1"));
    exit();
}

$mydb = new MyDB();
$conn = $mydb->createConn();

$result = $mydb->getProductById($productId, $conn);
if($result->num_rows == 0){
    $mydb->closeConn($conn);
    echo json_encode(array("success" => false, "message" => "Product not found"));
    exit();
}

$product = $result->fetch_assoc();
if($quantity > $product["stock"]){
    $mydb->closeConn($conn);
    echo json_encode(array("success" => false, "message" => "Only " . $product["stock"] . " units available"));
    exit();
}

if($mydb->addToCart($_SESSION["user_id"], $productId, $quantity, $conn)){
    $cartCount = $mydb->getCartCount($_SESSION["user_id"], $conn);
    $mydb->closeConn($conn);
    echo json_encode(array("success" => true, "message" => "Added to cart", "cart_count" => $cartCount));
} else {
    $mydb->closeConn($conn);
    echo json_encode(array("success" => false, "message" => "Failed to add to cart"));
}
?>
