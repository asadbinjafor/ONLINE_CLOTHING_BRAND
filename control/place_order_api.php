<?php
include_once '../model/mydb.php';
include_once 'auth.php';
include_once 'customer_gate.php';
include_once 'security.php';

initSecureSession();
sendSecurityHeaders();

requireCustomerApi();

if(!verifyCsrf($_POST["csrf_token"] ?? "")){
    echo json_encode(array("success" => false, "message" => "Invalid security token"));
    exit();
}

if(!isset($_SESSION["checkout_confirmed"]) || $_SESSION["checkout_confirmed"] != 1){
    echo json_encode(array("success" => false, "message" => "Complete checkout invoice first"));
    exit();
}

$paymentMethod = trim($_POST["payment_method"] ?? "");
$validMethods  = array("Credit Card", "bKash", "Nagad", "Bank Transfer", "Cash on Delivery");

if(!in_array($paymentMethod, $validMethods)){
    echo json_encode(array("success" => false, "message" => "Invalid payment method"));
    exit();
}

$mydb = new MyDB();
$conn = $mydb->createConn();

$userResult = $mydb->getUserById($_SESSION["user_id"], $conn);
$user       = $userResult->fetch_assoc();

$deliveryAddress = sanitizeText($_SESSION["delivery_address"] ?? $user["address"] ?? "", 500);
if($deliveryAddress == ""){
    $mydb->closeConn($conn);
    echo json_encode(array("success" => false, "message" => "Delivery address is required. Complete checkout first."));
    exit();
}

$cartItems = $mydb->getCartItems($_SESSION["user_id"], $conn);
$cartCount = $mydb->getCartCount($_SESSION["user_id"], $conn);

if($cartCount == 0){
    $mydb->closeConn($conn);
    echo json_encode(array("success" => false, "message" => "Cart is empty"));
    exit();
}

$totalAmount = 0;
$itemsArray  = array();
while($item = $cartItems->fetch_assoc()){
    if($item["quantity"] > $item["stock"]){
        $mydb->closeConn($conn);
        echo json_encode(array("success" => false, "message" => $item["name"] . " has only " . $item["stock"] . " in stock"));
        exit();
    }
    $totalAmount += $item["quantity"] * $item["price"];
    $itemsArray[] = $item;
}

$orderId = $mydb->createOrder($_SESSION["user_id"], $totalAmount, $deliveryAddress, $conn);

if(!$orderId){
    $mydb->closeConn($conn);
    echo json_encode(array("success" => false, "message" => "Failed to create order"));
    exit();
}

foreach($itemsArray as $item){
    $mydb->createOrderItem($orderId, $item["product_id"], $item["quantity"], $item["price"], $conn);
    $mydb->decreaseStock($item["product_id"], $item["quantity"], $conn);
}

$transactionId = "TXN" . time() . rand(1000, 9999);
$mydb->createPayment($orderId, $totalAmount, $paymentMethod, $transactionId, $conn);
$mydb->clearCart($_SESSION["user_id"], $conn);
unset($_SESSION["checkout_confirmed"]);
unset($_SESSION["delivery_address"]);

$mydb->closeConn($conn);
echo json_encode(array(
    "success"  => true,
    "message"  => "Order placed successfully",
    "order_id" => $orderId
));
?>
