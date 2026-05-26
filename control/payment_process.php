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

$errors  = array();
$orderId = null;

if(!isset($_SESSION["checkout_confirmed"]) || $_SESSION["checkout_confirmed"] != 1){
    header("Location: ../view/checkout.php");
    exit();
}

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

if(isset($_POST["confirm_payment"])){
    requireCsrfPost();
    $paymentMethod = trim($_POST["payment_method"] ?? "");
    $validMethods  = array("Credit Card", "bKash", "Nagad", "Bank Transfer", "Cash on Delivery");

    $deliveryAddress = trim($_SESSION["delivery_address"] ?? $user["address"]);
    if($deliveryAddress == ""){
        $errors["address"] = "Delivery address is required. Go back to checkout.";
    }
    if(!in_array($paymentMethod, $validMethods)){
        $errors["payment_method"] = "Please select a valid payment method";
    }

    foreach($itemsArray as $item){
        if($item["quantity"] > $item["stock"]){
            $errors["stock"] = $item["name"] . " has only " . $item["stock"] . " units in stock";
            break;
        }
    }

    if(empty($errors)){
        $orderId = $mydb->createOrder($_SESSION["user_id"], $totalAmount, $deliveryAddress, $conn);
        if($orderId){
            foreach($itemsArray as $item){
                $mydb->createOrderItem($orderId, $item["product_id"], $item["quantity"], $item["price"], $conn);
                $mydb->decreaseStock($item["product_id"], $item["quantity"], $conn);
            }
            $transactionId = "TXN" . time() . rand(1000, 9999);
            $mydb->createPayment($orderId, $totalAmount, $paymentMethod, $transactionId, $conn);
            $mydb->clearCart($_SESSION["user_id"], $conn);
            unset($_SESSION["checkout_confirmed"]);
            unset($_SESSION["delivery_address"]);
            header("Location: ../view/order_success.php?order_id=" . $orderId);
            exit();
        } else {
            $errors["database"] = "Failed to create order. Please try again.";
        }
    }
}
?>
