<?php
include_once '../model/mydb.php';
include_once 'auth.php';
include_once 'admin_gate.php';

if(session_status() == PHP_SESSION_NONE){ session_start(); }
requireAdmin();

$mydb    = new MyDB();
$conn    = $mydb->createConn();
$errors  = array();
$success = "";


if(isset($_POST["delete_customer"])){
    $delId = (int)($_POST["user_id"] ?? 0);

    $mydb->deleteUserCart($delId, $conn);
    $mydb->deleteUserPayments($delId, $conn);
    $mydb->deleteUserOrderItems($delId, $conn);
    $mydb->deleteUserOrders($delId, $conn);

    if($mydb->deleteUser($delId, $conn)){
        $success = "Customer deleted successfully";
    } else {
        $errors["delete"] = "Failed to delete customer. Please try again.";
    }
}

$customers = $mydb->getAllCustomers($conn);
?>
