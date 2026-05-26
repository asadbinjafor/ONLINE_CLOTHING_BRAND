<?php include '../control/order_success_process.php'; include_once '../control/app.php';
$shipAddr = !empty($order["shipping_address"]) ? $order["shipping_address"] : $user["address"];
$status = $order["status"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - A$ADORÉ</title>
    <link rel="stylesheet" href="../css/task3_style.css">
    <link rel="stylesheet" href="../css/task4_style.css">
</head>
<body>
<nav>
    <div class="nav-inner">
        <a class="nav-brand" href="Home.php">A$ADORÉ</a>
        <div class="nav-links">
            <a href="Home.php">Home</a>
            <a href="cart.php">Cart (<?php echo (int)$cartCount; ?>)</a>
            <a href="profile.php">Profile</a>
            <a href="../control/logout_process.php">Logout</a>
        </div>
    </div>
</nav>

<div class="page-header success-header">
    <h1>Order Placed Successfully!</h1>
    <p>Step 3 — Your order is pending admin confirmation</p>
</div>

<div class="main-container">

    <div class="checkout-steps">
        <span class="step-pill done">1. Invoice</span>
        <span class="step-pill done">2. Payment</span>
        <span class="step-pill active">3. Confirmation</span>
    </div>

    <div class="order-placed-banner">Thank you! Your order has been received.</div>

    <div class="card order-success-card">
        <div class="success-icon">&#10003;</div>
        <h2>Order #<?php echo (int)$order["id"]; ?></h2>
        <span class="order-status-badge status-<?php echo esc($status); ?>">
            <?php echo ucfirst(esc($status)); ?> — Awaiting Admin
        </span>

        <div class="order-status-timeline">
            <div class="timeline-step <?php echo $status === 'pending' ? 'active' : ''; ?>">Pending</div>
            <div class="timeline-step <?php echo $status === 'confirmed' ? 'active' : ''; ?>">Confirmed</div>
            <div class="timeline-step <?php echo $status === 'rejected' ? 'active' : ''; ?>">Rejected</div>
        </div>

        <div class="order-details">
            <p><strong>Customer:</strong> <?php echo esc($order["customer_name"]); ?></p>
            <p><strong>Delivery Address:</strong> <?php echo esc($shipAddr); ?></p>
            <?php if($payment){ ?>
                <p><strong>Payment Method:</strong> <?php echo esc($payment["payment_method"]); ?></p>
                <p><strong>Transaction ID:</strong> <?php echo esc($payment["transaction_id"]); ?></p>
            <?php } ?>
            <p><strong>Order Date:</strong> <?php echo date("d M Y, h:i A", strtotime($order["order_date"])); ?></p>
            <p><strong>Total Paid:</strong> BDT <?php echo number_format($order["total_amount"], 2); ?></p>
        </div>

        <div class="cart-table-wrapper">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Gender</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($item = $orderItems->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo esc($item["product_name"]); ?></td>
                            <td><?php echo esc($item["gender"]); ?></td>
                            <td><?php echo (int)$item["quantity"]; ?></td>
                            <td>BDT <?php echo number_format($item["unit_price"], 2); ?></td>
                            <td>BDT <?php echo number_format($item["quantity"] * $item["unit_price"], 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="cart-actions" style="justify-content:center; flex-wrap:wrap;">
            <a class="btn-primary" href="order_invoice.php?order_id=<?php echo (int)$order["id"]; ?>" target="_blank">View / Print Invoice</a>
            <a class="btn-secondary" href="profile.php?order_id=<?php echo (int)$order["id"]; ?>">Purchase History</a>
            <a class="btn-primary" href="Home.php">Continue Shopping</a>
        </div>
    </div>

</div>

</body>
</html>
