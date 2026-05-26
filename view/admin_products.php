<?php include '../control/admin_products_process.php'; include_once '../control/app.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - A$ADORÉ</title>
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
    <h1>Product Management</h1>
    <p>Add, edit and delete clothing products</p>
</div>
<div class="main-container">
    <?php if(!empty($errors["delete"])){ ?><div class="msg-error"><?php echo htmlspecialchars($errors["delete"]); ?></div><?php } ?>
    <?php if($success != ""){ ?><div class="msg-success"><?php echo htmlspecialchars($success); ?></div><?php } ?>
    <?php if(isset($_GET["saved"])){ ?><div class="msg-success">Product saved successfully</div><?php } ?>
    <a class="btn-add" href="admin_product_form.php">+ Add New Product</a>
    <?php if($products->num_rows == 0){ ?>
        <div class="no-results">No products found.</div>
    <?php } else { ?>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr><th>#</th><th>Image</th><th>Name</th><th>Category</th><th>Gender</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php $i=1; while($p = $products->fetch_assoc()){ ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php if(!empty($p["image_path"])){ ?><img class="product-thumb" src="<?php echo PRODUCT_UPLOAD_WEB . htmlspecialchars($p["image_path"]); ?>"><?php } else { echo "—"; } ?></td>
                    <td><?php echo htmlspecialchars($p["name"]); ?></td>
                    <td><?php echo htmlspecialchars($p["category_name"] ?? "—"); ?></td>
                    <td><?php echo htmlspecialchars($p["gender"]); ?></td>
                    <td><?php echo number_format($p["price"],2); ?></td>
                    <td><?php echo (int)$p["stock"]; ?></td>
                    <td>
                        <a class="btn-action btn-edit-sm" href="admin_product_form.php?id=<?php echo $p["id"]; ?>">Edit</a>
                        <form method="post" style="display:inline;" onsubmit="return confirmDelete('product')">
                            <input type="hidden" name="product_id" value="<?php echo $p["id"]; ?>">
                            <button type="submit" name="delete_product" class="btn-action btn-delete-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>
<script src="../js/task2_script.js"></script>
</body>
</html>
<?php $mydb->closeConn($conn); ?>
