<?php
include_once '../model/mydb.php';
include_once 'auth.php';
include_once 'security.php';

initSecureSession();
sendSecurityHeaders();

if(!isset($_SESSION["user_id"])){
    header("Location: ../view/login.php");
    exit();
}

$mydb = new MyDB();
$conn = $mydb->createConn();
tryRememberLogin($mydb, $conn);

$result = $mydb->getUserById($_SESSION["user_id"], $conn);
if($result->num_rows == 0){
    header("Location: ../control/logout_process.php");
    exit();
}

$user           = $result->fetch_assoc();
$name           = $user["name"];
$email          = $user["email"];
$role           = $user["role"];
$address        = $user["address"];
$phone          = $user["phone"];
$profilePicture = $user["profile_picture"];

$orderList     = array();
$orderDetails  = array();

if($role === "customer"){
    $ordersResult = $mydb->getCustomerOrdersWithPayment($_SESSION["user_id"], $conn);
    while($row = $ordersResult->fetch_assoc()){
        $orderList[] = $row;
    }

    if(isset($_GET["order_id"])){
        $oid = (int)$_GET["order_id"];
        $orderResult = $mydb->getOrderWithItems($oid, $_SESSION["user_id"], $conn);
        if($orderResult->num_rows > 0){
            $orderDetails["order"] = $orderResult->fetch_assoc();
            $orderDetails["items"] = $mydb->getOrderItems($oid, $conn);
            $payResult = $mydb->getPaymentByOrderId($oid, $conn);
            $orderDetails["payment"] = $payResult->num_rows > 0 ? $payResult->fetch_assoc() : null;
        }
    }
}
?>
