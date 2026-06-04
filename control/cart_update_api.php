<?php
include_once '../model/mydb.php';
include_once 'auth.php';
include_once 'customer_gate.php';

if(session_status() == PHP_SESSION_NONE){ session_start(); }

requireCustomerApi();

if(!isset($_POST["cart_id"]) || !isset($_POST["quantity"])){
    echo json_encode(array("success" => false, "message" => "Missing parameters"));
    exit();
}

$cartId   = (int)$_POST["cart_id"];
$quantity = (int)$_POST["quantity"];

if($cartId <= 0){
    echo json_encode(array("success" => false, "message" => "Invalid cart item"));
    exit();
}
if($quantity <= 0){
    echo json_encode(array("success" => false, "message" => "Quantity must be at least 1"));
    exit();
}

$mydb = new MyDB();
$conn = $mydb->createConn();

$cartResult = $mydb->getCartItemById($cartId, $_SESSION["user_id"], $conn);
if($cartResult->num_rows == 0){
    $mydb->closeConn($conn);
    echo json_encode(array("success" => false, "message" => "Cart item not found"));
    exit();
}

$cartItem = $cartResult->fetch_assoc();
if($quantity > $cartItem["stock"]){
    $mydb->closeConn($conn);
    echo json_encode(array("success" => false, "message" => "Only " . $cartItem["stock"] . " units in stock"));
    exit();
}

if($mydb->updateCartQuantity($cartId, $_SESSION["user_id"], $quantity, $conn)){
    $cartCount = $mydb->getCartCount($_SESSION["user_id"], $conn);
    $subtotal  = $quantity * $cartItem["price"];

    $cartItems = $mydb->getCartItems($_SESSION["user_id"], $conn);
    $total = 0;
    while($row = $cartItems->fetch_assoc()){
        $total += $row["quantity"] * $row["price"];
    }

    $mydb->closeConn($conn);
    echo json_encode(array(
        "success"    => true,
        "message"    => "Cart updated",
        "cart_count" => $cartCount,
        "subtotal"   => number_format($subtotal, 2),
        "total"      => number_format($total, 2)
    ));
} else {
    $mydb->closeConn($conn);
    echo json_encode(array("success" => false, "message" => "Failed to update cart"));
}
?>
