<?php include '../control/admin_orders_process.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List - A$ADORÉ</title>
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
    <h1>Order List</h1>
    <p>Confirm or reject customer purchase requests</p>
</div>
<div class="main-container">
    <div id="orderMsg"></div>
    <?php if($orders->num_rows == 0){ ?>
        <div class="no-results">No orders yet.</div>
    <?php } else { ?>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr><th>Order #</th><th>Customer</th><th>Total (BDT)</th><th>Date</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php while($order = $orders->fetch_assoc()){ ?>
                <tr>
                    <td>#<?php echo $order["id"]; ?></td>
                    <td><?php echo htmlspecialchars($order["customer_name"]); ?><br><small><?php echo htmlspecialchars($order["customer_email"]); ?></small></td>
                    <td><?php echo number_format($order["total_amount"], 2); ?></td>
                    <td><?php echo date("d M Y, h:i A", strtotime($order["order_date"])); ?></td>
                    <td><span class="order-status-badge status-<?php echo $order["status"]; ?>" id="status-badge-<?php echo $order["id"]; ?>"><?php echo ucfirst($order["status"]); ?></span></td>
                    <td id="action-cell-<?php echo $order["id"]; ?>">
                        <?php if($order["status"] == "pending"){ ?>
                            <button class="btn-action btn-confirm-sm" onclick="updateOrderStatus(<?php echo $order["id"]; ?>, 'confirmed')">Confirm</button>
                            <button class="btn-action btn-reject-sm" onclick="updateOrderStatus(<?php echo $order["id"]; ?>, 'rejected')">Reject</button>
                        <?php } else { ?><span class="text-muted">—</span><?php } ?>
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
