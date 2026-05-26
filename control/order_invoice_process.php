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

$orderId = (int)($_GET["order_id"] ?? 0);
if($orderId <= 0){
    header("Location: ../view/profile.php");
    exit();
}

$orderResult = $mydb->getOrderWithItems($orderId, $_SESSION["user_id"], $conn);
if($orderResult->num_rows == 0){
    header("Location: ../view/profile.php");
    exit();
}

$order      = $orderResult->fetch_assoc();
$orderItems = $mydb->getOrderItems($orderId, $conn);
$payResult  = $mydb->getPaymentByOrderId($orderId, $conn);
$payment    = $payResult->num_rows > 0 ? $payResult->fetch_assoc() : null;
$userResult = $mydb->getUserById($_SESSION["user_id"], $conn);
$customer   = $userResult->fetch_assoc();
?>
