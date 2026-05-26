<?php
include '../control/product_detail_process.php';
include_once '../control/app.php';
$imageWeb = !empty($product["image_path"]) ? PRODUCT_UPLOAD_WEB . htmlspecialchars($product["image_path"]) : "";
$isCustomer = isset($_SESSION["user_id"]) && $_SESSION["role"] === "customer";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product["name"]); ?> - A$ADORÉ</title>
    <link rel="stylesheet" type="text/css" href="../css/task1_style.css">
    <?php if($isCustomer){ ?><link rel="stylesheet" href="../css/task3_style.css"><?php } ?>
</head>
<body>
<nav>
    <div class="nav-inner">
        <a class="nav-brand" href="Home.php">A$ADORÉ</a>
        <div class="nav-links">
            <a href="Home.php">Home</a>
            <?php if($isCustomer){ ?>
                <a href="cart.php">Cart (<span id="navCartCount"><?php echo $cartCount; ?></span>)</a>
            <?php } ?>
            <a href="profile.php">Profile</a>
            <?php if(isset($_SESSION["user_id"])){ ?>
                <a href="../control/logout_process.php">Logout</a>
            <?php } else { ?>
                <a href="login.php">Login</a>
            <?php } ?>
        </div>
    </div>
</nav>
<div class="page-header">
    <h1><?php echo htmlspecialchars($product["name"]); ?></h1>
</div>
<div class="main-container">
    <div class="product-detail-card">
        <div class="product-detail-image">
            <?php if($imageWeb){ ?><img src="<?php echo $imageWeb; ?>" alt=""><?php }
            else { ?><div class="no-image">No Image</div><?php } ?>
        </div>
        <div class="product-detail-info">
            <span class="badge badge-gender"><?php echo htmlspecialchars($product["gender"]); ?></span>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($product["category_name"] ?? "—"); ?></p>
            <p><strong>Price:</strong> BDT <?php echo number_format($product["price"], 2); ?></p>
            <p><strong>Stock:</strong> <?php echo (int)$product["stock"]; ?> units</p>
            <p><strong>Description:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($product["description"] ?? "")); ?></p>
            <p><strong>Size Chart:</strong></p>
            <pre class="size-chart"><?php echo htmlspecialchars($product["size_chart"] ?? "N/A"); ?></pre>
            <?php if($isCustomer && $product["stock"] > 0){ ?>
                <div class="add-cart-form">
                    <input type="number" id="detailQty" value="1" min="1" max="<?php echo $product["stock"]; ?>" data-stock="<?php echo $product["stock"]; ?>">
                    <button class="btn-add-cart" onclick="addToCartFromDetail(<?php echo $product["id"]; ?>)">Add to Cart</button>
                </div>
            <?php } elseif(!isset($_SESSION["user_id"])){ ?>
                <p><a href="login.php">Login</a> to add to cart.</p>
            <?php } ?>
            <p><a class="btn-link" href="Home.php">&larr; Back to Home</a></p>
        </div>
    </div>
</div>
<?php if($isCustomer){ ?>
<script src="../js/task3_script.js"></script>
<script>
function addToCartFromDetail(id){
    var q=document.getElementById("detailQty"), qty=parseInt(q.value), stock=parseInt(q.getAttribute("data-stock"));
    if(isNaN(qty)||qty<=0){alert("Quantity must be at least 1");return;}
    if(qty>stock){alert("Only "+stock+" in stock");return;}
    var x=new XMLHttpRequest();
    x.onreadystatechange=function(){if(x.readyState==4&&x.status==200){var d=JSON.parse(x.responseText);
        if(d.success){var n=document.getElementById("navCartCount");if(n)n.textContent=d.cart_count;alert(d.message);}
        else alert(d.message);}};
    x.open("POST","../control/cart_add_api.php",true);
    x.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    x.send("product_id="+id+"&quantity="+qty);
}
</script>
<?php } ?>
</body>
</html>
<?php $mydb->closeConn($conn); ?>
