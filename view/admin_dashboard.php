<?php include '../control/admin_dashboard_process.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - A$ADORÉ</title>
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
    <h1>Admin Dashboard</h1>
    <p>Manage products, customers and orders</p>
</div>
<div class="main-container">
    <div class="dashboard-stats">
        <a class="stat-card" href="admin_products.php">
            <div class="stat-number"><?php echo $counts["products"]; ?></div>
            <div class="stat-label">Total Products</div>
        </a>
        <a class="stat-card" href="admin_customers.php">
            <div class="stat-number"><?php echo $counts["customers"]; ?></div>
            <div class="stat-label">Customers</div>
        </a>
        <a class="stat-card" href="admin_orders.php">
            <div class="stat-number"><?php echo $counts["orders"]; ?></div>
            <div class="stat-label">Total Orders</div>
        </a>
        <a class="stat-card stat-card-pending" href="admin_orders.php">
            <div class="stat-number"><?php echo $counts["pending_orders"]; ?></div>
            <div class="stat-label">Pending Orders</div>
        </a>
    </div>
    <h2 class="section-title">Quick Links</h2>
    <div class="quick-links">
        <a class="quick-link-btn" href="admin_product_form.php">Add New Product</a>
        <a class="quick-link-btn" href="admin_products.php">All Products</a>
        <a class="quick-link-btn" href="admin_customers.php">Manage Customers</a>
        <a class="quick-link-btn" href="admin_orders.php">Order List</a>
        <a class="quick-link-btn" href="admin_history.php">Purchase History</a>
    </div>
</div>
</body>
</html>
