<?php
include_once '../model/mydb.php';
include_once 'auth.php';
include_once 'customer_gate.php';
include_once 'security.php';

initSecureSession();
sendSecurityHeaders();

$mydb = new MyDB();
$conn = $mydb->createConn();
tryRememberLogin($mydb, $conn);
requireCustomer();

$errors = array();

$cartItems = $mydb->getCartItems($_SESSION["user_id"], $conn);
$cartCount = $mydb->getCartCount($_SESSION["user_id"], $conn);

if($cartCount == 0){
    header("Location: ../view/cart.php");
    exit();
}

$totalAmount = 0;
$itemsArray  = array();
while($item = $cartItems->fetch_assoc()){
    $item["subtotal"] = $item["quantity"] * $item["price"];
    $totalAmount += $item["subtotal"];
    $itemsArray[] = $item;
}

$userResult = $mydb->getUserById($_SESSION["user_id"], $conn);
$user       = $userResult->fetch_assoc();

$deliveryAddress = isset($_SESSION["delivery_address"]) ? $_SESSION["delivery_address"] : $user["address"];

if(isset($_POST["confirm_checkout"])){
    requireCsrfPost();
    $deliveryAddress = sanitizeText($_POST["delivery_address"] ?? "", 500);

    if($deliveryAddress == ""){
        $errors["address"] = "Delivery address is required";
    }

    foreach($itemsArray as $item){
        if($item["quantity"] > $item["stock"]){
            $errors["stock"] = $item["name"] . " has only " . $item["stock"] . " units in stock";
            break;
        }
    }

    if(empty($errors)){
        $_SESSION["delivery_address"] = $deliveryAddress;
        $_SESSION["checkout_confirmed"] = 1;
        header("Location: ../view/payment.php");
        exit();
    }
}
?>
