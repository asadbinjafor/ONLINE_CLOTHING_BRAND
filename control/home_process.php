<?php
include_once '../model/mydb.php';
include_once 'auth.php';

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$mydb = new MyDB();
$conn = $mydb->createConn();
tryRememberLogin($mydb, $conn);

$gender     = $_GET["gender"] ?? "";
$categoryId = isset($_GET["category_id"]) ? (int)$_GET["category_id"] : 0;

if($gender != "Men" && $gender != "Women"){
    $gender = "";
}

$rootCategories = $mydb->getRootCategories($conn);
$childCategories = null;
if($gender != ""){
    $genderCat = null;
    $roots = $mydb->getRootCategories($conn);
    while($r = $roots->fetch_assoc()){
        if($r["name"] === $gender){
            $genderCat = $r;
            break;
        }
    }
    if($genderCat){
        $childCategories = $mydb->getChildCategories($genderCat["id"], $conn);
    }
}

$filterCategories = $mydb->getCategoriesByGender($gender != "" ? $gender : "Men", $conn);
if($gender == "Women"){
    $filterCategories = $mydb->getCategoriesByGender("Women", $conn);
} elseif($gender == "") {
    $filterCategories = $conn->query("SELECT * FROM categories WHERE parent_id IS NOT NULL ORDER BY name");
}

$featured = $mydb->getFeaturedProducts(6, $conn);
$products = $mydb->getProducts($categoryId, $gender, $conn);
?>
