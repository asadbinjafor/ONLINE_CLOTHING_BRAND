<?php
include_once '../model/mydb.php';
include_once 'auth.php';
include_once 'admin_gate.php';
include_once 'upload.php';

if(session_status() == PHP_SESSION_NONE){ session_start(); }
requireAdmin();

$mydb    = new MyDB();
$conn    = $mydb->createConn();
$errors  = array();
$success = "";

if(isset($_POST["delete_product"])){
    $delId = (int)($_POST["product_id"] ?? 0);
    if($mydb->productInOrder($delId, $conn)){
        $errors["delete"] = "Cannot delete: product exists in order history";
    } else {
        $result = $mydb->getProductById($delId, $conn);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            if($mydb->deleteProduct($delId, $conn)){
                deleteProductImageFile($row["image_path"]);
                $success = "Product deleted successfully";
            } else {
                $errors["delete"] = "Failed to delete product";
            }
        } else {
            $errors["delete"] = "Product not found";
        }
    }
}

$products = $mydb->getAllProducts($conn);
?>
