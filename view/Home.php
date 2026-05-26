<?php include '../control/home_process.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - A$ADORÉ Clothing</title>
    <link rel="stylesheet" type="text/css" href="../css/task1_style.css">
    <?php if(isset($_SESSION["user_id"]) && $_SESSION["role"] === "customer"){ ?>
    <link rel="stylesheet" type="text/css" href="../css/task3_style.css">
    <?php } ?>
</head>
<body>

<nav>
    <div class="nav-inner">
        <a class="nav-brand" href="Home.php">A$ADORÉ</a>
        <div class="nav-links">
            <a href="Home.php">Home</a>
            <?php if(isset($_SESSION["user_id"])){ ?>
                <?php if($_SESSION["role"] === "customer"){ ?>
                    <a href="cart.php">Cart (<span id="navCartCount"><?php echo $mydb->getCartCount($_SESSION["user_id"], $conn); ?></span>)</a>
                <?php } ?>
                <a href="profile.php">Profile</a>
                <?php if($_SESSION["role"] === "admin"){ ?>
                    <a href="admin_dashboard.php">Admin Dashboard</a>
                <?php } ?>
                <a href="../control/logout_process.php">Logout</a>
                <span class="nav-user-info">Hi, <?php echo htmlspecialchars($_SESSION["name"]); ?></span>
            <?php } else { ?>
                <a href="login.php">Login</a>
                <a href="Registration.php">Register</a>
            <?php } ?>
        </div>
    </div>
</nav>

<div class="page-header">
    <h1>A$ADORÉ</h1>
    <p>Shop the latest fashion for Men and Women</p>
</div>

<div class="main-container">

    <div class="filters-bar">
        <div class="filter-item">
            <label for="searchText">Search Product</label>
            <input type="text" id="searchText" placeholder="Type product name..." onkeyup="searchProducts()">
        </div>
        <div class="filter-item">
            <label for="genderFilter">Gender</label>
            <select id="genderFilter" onchange="searchProducts()">
                <option value="">All</option>
                <option value="Men" <?php if($gender=="Men") echo "selected"; ?>>Men</option>
                <option value="Women" <?php if($gender=="Women") echo "selected"; ?>>Women</option>
            </select>
        </div>
        <div class="filter-item">
            <label for="categoryFilter">Category</label>
            <select id="categoryFilter" onchange="searchProducts()">
                <option value="">All Categories</option>
                <?php
                if($filterCategories){
                    while($cat = $filterCategories->fetch_assoc()){
                ?>
                    <option value="<?php echo $cat["id"]; ?>" <?php if($categoryId==$cat["id"]) echo "selected"; ?>>
                        <?php echo htmlspecialchars($cat["name"]); ?>
                    </option>
                <?php } } ?>
            </select>
        </div>
    </div>

    <h2 class="section-title">Shop by Gender</h2>
    <div class="category-list">
        <a class="category-link <?php if($gender=="" && $categoryId==0) echo "active"; ?>" href="Home.php">All</a>
        <a class="category-link <?php if($gender=="Men") echo "active"; ?>" href="Home.php?gender=Men">Men</a>
        <a class="category-link <?php if($gender=="Women") echo "active"; ?>" href="Home.php?gender=Women">Women</a>
    </div>

    <?php if($gender != "" && $childCategories){ ?>
    <h2 class="section-title"><?php echo htmlspecialchars($gender); ?> Categories</h2>
    <div class="category-list">
        <a class="category-link <?php if($categoryId==0) echo "active"; ?>" href="Home.php?gender=<?php echo urlencode($gender); ?>">All <?php echo htmlspecialchars($gender); ?></a>
        <?php while($cat = $childCategories->fetch_assoc()){ ?>
            <a class="category-link <?php if($categoryId==$cat["id"]) echo "active"; ?>"
               href="Home.php?gender=<?php echo urlencode($gender); ?>&category_id=<?php echo $cat["id"]; ?>">
                <?php echo htmlspecialchars($cat["name"]); ?>
            </a>
        <?php } ?>
    </div>
    <?php } ?>

    <h2 class="section-title">Featured Products</h2>
    <div class="product-grid featured-grid">
        <?php while($fp = $featured->fetch_assoc()){ ?>
            <div class="product-card">
                <h3><a href="product_detail.php?id=<?php echo $fp["id"]; ?>"><?php echo htmlspecialchars($fp["name"]); ?></a></h3>
                <span class="badge badge-gender"><?php echo htmlspecialchars($fp["gender"]); ?></span>
                <p><?php echo htmlspecialchars($fp["category_name"] ?? ""); ?></p>
                <p class="product-price">BDT <?php echo number_format($fp["price"], 2); ?></p>
                <a class="btn-link" href="product_detail.php?id=<?php echo $fp["id"]; ?>">View Details</a>
            </div>
        <?php } ?>
    </div>

    <h2 class="section-title">Products</h2>
    <div id="productList" class="product-grid">
        <?php if($products->num_rows == 0){ ?>
            <div class="no-results">No products found.</div>
        <?php } ?>
        <?php while($p = $products->fetch_assoc()){ ?>
            <div class="product-card" data-product-id="<?php echo $p["id"]; ?>">
                <h3><a href="product_detail.php?id=<?php echo $p["id"]; ?>"><?php echo htmlspecialchars($p["name"]); ?></a></h3>
                <span class="badge badge-gender"><?php echo htmlspecialchars($p["gender"]); ?></span>
                <p>Category: <?php echo htmlspecialchars($p["category_name"] ?? ""); ?></p>
                <p>Stock: <?php echo (int)$p["stock"]; ?> units</p>
                <p class="product-price">BDT <?php echo number_format($p["price"], 2); ?></p>
                <a class="btn-link" href="product_detail.php?id=<?php echo $p["id"]; ?>">View Details</a>
                <?php if(isset($_SESSION["user_id"]) && $_SESSION["role"] === "customer" && $p["stock"] > 0){ ?>
                    <div class="add-cart-form">
                        <input type="number" class="add-cart-qty" id="qty-input-<?php echo $p["id"]; ?>"
                               value="1" min="1" max="<?php echo $p["stock"]; ?>" data-stock="<?php echo $p["stock"]; ?>">
                        <button class="btn-add-cart" onclick="addToCart(<?php echo $p["id"]; ?>)">Add to Cart</button>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

</div>

<script src="../js/task1_script.js"></script>
<?php if(isset($_SESSION["user_id"]) && $_SESSION["role"] === "customer"){ ?>
<script src="../js/task3_script.js"></script>
<?php } ?>
</body>
</html>
<?php $mydb->closeConn($conn); ?>
