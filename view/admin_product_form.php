<?php include '../control/admin_product_form_process.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? "Edit Product" : "Add Product"; ?> - A$ADORÉ</title>
    <link rel="stylesheet" href="../css/task2_style.css">
</head>
<body>
<nav>
    <div class="nav-inner">
        <a class="nav-brand" href="admin_dashboard.php">A$ADORÉ Admin</a>
        <div class="nav-links">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_products.php">Products</a>
            <a href="admin_customers.php">Customers</a>
            <a href="admin_orders.php">Orders</a>
            <a href="admin_history.php">History</a>
            <a href="Home.php">Home</a>
            <a href="../control/logout_process.php">Logout</a>
        </div>
    </div>
</nav>
<div class="page-header">
    <h1><?php echo $isEdit ? "Edit Product" : "Add New Product"; ?></h1>
</div>
<div class="main-container">
    <div class="card" style="max-width:620px;">
        <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateProductForm()">
            <input type="hidden" name="save_product" value="1">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($old["name"]); ?>">
                <span class="error"><?php echo $errors["name"] ?? ""; ?></span>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" onchange="filterCategories()">
                    <option value="Men" <?php if($old["gender"]=="Men") echo "selected"; ?>>Men</option>
                    <option value="Women" <?php if($old["gender"]=="Women") echo "selected"; ?>>Women</option>
                </select>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="">-- Select --</option>
                    <?php
                    $menCategories->data_seek(0);
                    while($cat = $menCategories->fetch_assoc()){
                        if($old["gender"]=="Men" || !$isEdit){
                    ?>
                        <option value="<?php echo $cat["id"]; ?>" data-gender="Men"
                            <?php if($old["category_id"]==$cat["id"]) echo "selected"; ?>>
                            <?php echo htmlspecialchars($cat["name"]); ?> (Men)
                        </option>
                    <?php }} 
                    $womenCategories->data_seek(0);
                    while($cat = $womenCategories->fetch_assoc()){ ?>
                        <option value="<?php echo $cat["id"]; ?>" data-gender="Women"
                            <?php if($old["category_id"]==$cat["id"]) echo "selected"; ?>>
                            <?php echo htmlspecialchars($cat["name"]); ?> (Women)
                        </option>
                    <?php } ?>
                </select>
                <span class="error"><?php echo $errors["category_id"] ?? ""; ?></span>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($old["description"]); ?></textarea>
                <span class="error"><?php echo $errors["description"] ?? ""; ?></span>
            </div>
            <div class="form-group">
                <label for="size_chart">Size Chart</label>
                <textarea id="size_chart" name="size_chart" placeholder="e.g. S,M,L,XL"><?php echo htmlspecialchars($old["size_chart"]); ?></textarea>
                <span class="error"><?php echo $errors["size_chart"] ?? ""; ?></span>
            </div>
            <div class="form-group">
                <label for="price">Price (BDT)</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($old["price"]); ?>">
                <span class="error"><?php echo $errors["price"] ?? ""; ?></span>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="text" id="stock" name="stock" value="<?php echo htmlspecialchars($old["stock"]); ?>">
                <span class="error"><?php echo $errors["stock"] ?? ""; ?></span>
            </div>
            <div class="form-group">
                <label for="image">Product Image (JPEG/PNG, max 2MB)</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png">
                <?php if($isEdit && !empty($old["image_path"])){ ?>
                    <p>Current: <?php echo htmlspecialchars($old["image_path"]); ?></p>
                <?php } ?>
                <span class="error"><?php echo $errors["image"] ?? ""; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn-add" value="<?php echo $isEdit ? 'Update Product' : 'Add Product'; ?>">
                <a class="btn-cancel" href="admin_products.php">Cancel</a>
            </div>
        </form>
    </div>
</div>
<script src="../js/task2_script.js"></script>
</body>
</html>
<?php $mydb->closeConn($conn); ?>
