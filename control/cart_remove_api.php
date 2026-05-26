<?php
include_once '../model/mydb.php';
include_once 'auth.php';
include_once 'customer_gate.php';

if(session_status() == PHP_SESSION_NONE){ session_start(); }

requireCustomerApi();

if(!isset($_POST["cart_id"])){
    echo json_encode(array("success" => false, "message" => "Missing parameters"));
    exit();
}

$cartId = (int)$_POST["cart_id"];

if($cartId <= 0){
    echo json_encode(array("success" => false, "message" => "Invalid cart item"));
    exit();
}

$mydb = new MyDB();
$conn = $mydb->createConn();

if($mydb->removeCartItem($cartId, $_SESSION["user_id"], $conn)){
    $cartCount = $mydb->getCartCount($_SESSION["user_id"], $conn);

    $cartItems = $mydb->getCartItems($_SESSION["user_id"], $conn);
    $total = 0;
    while($row = $cartItems->fetch_assoc()){
        $total += $row["quantity"] * $row["price"];
    }

    $mydb->closeConn($conn);
    echo json_encode(array(
        "success"    => true,
        "message"    => "Item removed",
        "cart_count" => $cartCount,
        "total"      => number_format($total, 2)
    ));
} else {
    $mydb->closeConn($conn);
    echo json_encode(array("success" => false, "message" => "Failed to remove item"));
}
?>
