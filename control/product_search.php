<?php
include_once '../model/mydb.php';
header("Content-Type: application/json");

$q          = trim($_GET["q"] ?? "");
$categoryId = isset($_GET["category"]) ? (int)$_GET["category"] : 0;
$gender     = trim($_GET["gender"] ?? "");

if($gender != "" && $gender != "Men" && $gender != "Women"){
    http_response_code(400);
    echo json_encode(array("success"=>false, "message"=>"Invalid gender"));
    exit();
}

$mydb = new MyDB();
$conn = $mydb->createConn();

if($conn->connect_error){
    http_response_code(500);
    echo json_encode(array("success"=>false, "message"=>"Database connection failed"));
    exit();
}

$result   = $mydb->searchProducts($q, $categoryId, $gender, $conn);
$products = array();

while($row = $result->fetch_assoc()){
    $products[] = array(
        "id"            => $row["id"],
        "name"          => htmlspecialchars($row["name"]),
        "description"   => htmlspecialchars($row["description"] ?? ""),
        "size_chart"    => htmlspecialchars($row["size_chart"] ?? ""),
        "price"         => $row["price"],
        "stock"         => $row["stock"],
        "gender"        => htmlspecialchars($row["gender"]),
        "image_path"    => htmlspecialchars($row["image_path"] ?? ""),
        "category_name" => htmlspecialchars($row["category_name"] ?? "")
    );
}

echo json_encode(array("success"=>true, "products"=>$products));
$mydb->closeConn($conn);
?>
