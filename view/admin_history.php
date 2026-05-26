<?php include '../control/admin_history_process.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History - A$ADORÉ</title>
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
    <h1>All Purchase History</h1>
    <p>Confirmed orders from all customers</p>
</div>
<div class="main-container">
    <?php if(empty($orderData)){ ?>
        <div class="no-results">No confirmed orders found.</div>
    <?php } else { ?>
        <?php foreach($orderData as $order){ ?>
            <div class="card" style="margin-bottom:20px;">
                <h3>Order #<?php echo $order["id"]; ?> — <?php echo htmlspecialchars($order["customer_name"]); ?></h3>
                <p>Date: <?php echo date("d M Y, h:i A", strtotime($order["order_date"])); ?> | Total: BDT <?php echo number_format($order["total_amount"], 2); ?></p>
                <table class="admin-table">
                    <thead><tr><th>Product</th><th>Gender</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr></thead>
                    <tbody>
                    <?php while($item = $order["items"]->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item["product_name"]); ?></td>
                            <td><?php echo htmlspecialchars($item["gender"]); ?></td>
                            <td><?php echo $item["quantity"]; ?></td>
                            <td><?php echo number_format($item["unit_price"], 2); ?></td>
                            <td><?php echo number_format($item["quantity"] * $item["unit_price"], 2); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    <?php } ?>
</div>
</body>
</html>
<?php $mydb->closeConn($conn); ?>
