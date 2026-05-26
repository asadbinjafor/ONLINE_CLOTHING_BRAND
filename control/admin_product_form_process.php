<?php
include_once '../model/mydb.php';
include_once 'auth.php';
include_once 'admin_gate.php';
include_once 'upload.php';

if(session_status() == PHP_SESSION_NONE){ session_start(); }
requireAdmin();

$mydb   = new MyDB();
$conn   = $mydb->createConn();
$errors = array();
$editId = isset($_GET["id"]) ? (int)$_GET["id"] : (int)($_GET["edit"] ?? 0);
$isEdit = $editId > 0;

$old = array(
    "name" => "", "description" => "", "size_chart" => "",
    "price" => "", "category_id" => "", "gender" => "Men", "stock" => ""
);

$menCategories   = $mydb->getCategoriesByGender("Men", $conn);
$womenCategories = $mydb->getCategoriesByGender("Women", $conn);

if($isEdit){
    $result = $mydb->getProductById($editId, $conn);
    if($result->num_rows == 0){
        header("Location: ../view/admin_products.php");
        exit();
    }
    $row = $result->fetch_assoc();
    $old = array(
        "name" => $row["name"], "description" => $row["description"],
        "size_chart" => $row["size_chart"], "price" => $row["price"],
        "category_id" => $row["category_id"], "gender" => $row["gender"],
        "stock" => $row["stock"], "image_path" => $row["image_path"]
    );
}

if(isset($_POST["save_product"])){
    $old["name"]        = trim($_POST["name"] ?? "");
    $old["description"] = trim($_POST["description"] ?? "");
    $old["size_chart"]  = trim($_POST["size_chart"] ?? "");
    $old["price"]       = trim($_POST["price"] ?? "");
    $old["category_id"] = (int)($_POST["category_id"] ?? 0);
    $old["gender"]      = $_POST["gender"] ?? "Men";
    $old["stock"]       = trim($_POST["stock"] ?? "");

    if($old["name"] == ""){ $errors["name"] = "Product name is required"; }
    if($old["description"] == ""){ $errors["description"] = "Description is required"; }
    if($old["size_chart"] == ""){ $errors["size_chart"] = "Size chart is required"; }
    if($old["price"] == "" || !is_numeric($old["price"]) || (float)$old["price"] <= 0){
        $errors["price"] = "Price must be greater than 0";
    }
    if($old["category_id"] <= 0){ $errors["category_id"] = "Select a category"; }
    if($old["gender"] != "Men" && $old["gender"] != "Women"){ $errors["gender"] = "Select valid gender"; }
    if($old["stock"] == "" || !ctype_digit($old["stock"])){ $errors["stock"] = "Stock must be a non-negative whole number"; }

    $imagePath = $isEdit ? ($old["image_path"] ?? "") : "";
    if(count($errors) == 0){
        $imagePath = uploadProductImage("image", $imagePath, $errors);
    }

    if(count($errors) == 0){
        $price = (float)$old["price"];
        $stock = (int)$old["stock"];
        if($isEdit){
            $ok = $mydb->updateProduct($editId, $old["name"], $old["description"], $old["size_chart"],
                $price, $old["category_id"], $old["gender"], $stock, $imagePath, $conn);
        } else {
            $ok = $mydb->createProduct($old["name"], $old["description"], $old["size_chart"],
                $price, $old["category_id"], $old["gender"], $stock, $imagePath, $conn);
        }
        if($ok){
            header("Location: ../view/admin_products.php?saved=1");
            exit();
        }
        $errors["database"] = "Failed to save product";
    }
}
?>
