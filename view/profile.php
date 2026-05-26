<?php include '../control/profile_process.php'; include_once '../control/app.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - A$ADORÉ</title>
    <link rel="stylesheet" href="../css/task1_style.css">
    <link rel="stylesheet" href="../css/task3_style.css">
    <link rel="stylesheet" href="../css/task4_style.css">
</head>
<body>

<nav>
    <div class="nav-inner">
        <a class="nav-brand" href="Home.php">A$ADORÉ</a>
        <div class="nav-links">
            <a href="Home.php">Home</a>
            <?php if($role === "customer"){ ?>
                <a href="cart.php">Cart</a>
            <?php } ?>
            <a href="profile.php">Profile</a>
            <?php if($role === "admin"){ ?>
                <a href="admin_dashboard.php">Admin Dashboard</a>
            <?php } ?>
            <a href="../control/logout_process.php">Logout</a>
            <span class="nav-user-info">Hi, <?php echo htmlspecialchars($_SESSION["name"]); ?></span>
        </div>
    </div>
</nav>

<div class="page-header">
    <h1>My Profile</h1>
    <p>Account details and purchase history</p>
</div>

<div class="main-container">

    <?php if(isset($_GET["updated"])){ ?>
        <div class="msg-success">Profile updated successfully.</div>
    <?php } ?>

    <div class="card">
        <div class="profile-layout">
            <?php if($profilePicture != ""){ ?>
                <img class="profile-avatar" src="<?php echo PROFILE_UPLOAD_WEB . htmlspecialchars($profilePicture); ?>" alt="">
            <?php } else { ?>
                <div class="profile-avatar-placeholder">&#9786;</div>
            <?php } ?>
            <div class="profile-details">
                <h2><?php echo htmlspecialchars($name); ?></h2>
                <span class="role-badge role-<?php echo $role; ?>"><?php echo ucfirst(htmlspecialchars($role)); ?></span>
                <p>Email: <?php echo htmlspecialchars($email); ?></p>
                <p>Phone: <?php echo htmlspecialchars($phone); ?></p>
                <p>Address: <?php echo htmlspecialchars($address); ?></p>
                <a class="btn-edit" href="editprofile.php">Edit Profile</a>
            </div>
        </div>
    </div>

    <?php if($role === "customer"){ ?>
    <div class="purchase-history-section">
        <h2 class="section-title">Purchase History</h2>

        <?php if(!empty($orderDetails)){ ?>
            <div class="card order-detail-panel">
                <h3>Order #<?php echo $orderDetails["order"]["id"]; ?> — Item Details</h3>
                <p><strong>Date:</strong> <?php echo date("d M Y, h:i A", strtotime($orderDetails["order"]["order_date"])); ?></p>
                <p><strong>Status:</strong>
                    <span class="order-status-badge status-<?php echo $orderDetails["order"]["status"]; ?>">
                        <?php echo ucfirst($orderDetails["order"]["status"]); ?>
                    </span>
                </p>
                <p><strong>Total:</strong> BDT <?php echo number_format($orderDetails["order"]["total_amount"], 2); ?></p>
                <?php if(!empty($orderDetails["order"]["shipping_address"])){ ?>
                    <p><strong>Delivery:</strong> <?php echo esc($orderDetails["order"]["shipping_address"]); ?></p>
                <?php } ?>
                <?php if($orderDetails["payment"]){ ?>
                    <p><strong>Payment:</strong> <?php echo htmlspecialchars($orderDetails["payment"]["payment_method"]); ?></p>
                    <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($orderDetails["payment"]["transaction_id"]); ?></p>
                <?php } ?>
                <div class="cart-table-wrapper" style="margin-top:14px;">
                    <table class="cart-table profile-orders-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Gender</th>
                                <th>Qty</th>
                                <th>Unit Price (BDT)</th>
                                <th>Subtotal (BDT)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($oi = $orderDetails["items"]->fetch_assoc()){ ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($oi["product_name"]); ?></td>
                                    <td><?php echo htmlspecialchars($oi["gender"]); ?></td>
                                    <td><?php echo $oi["quantity"]; ?></td>
                                    <td><?php echo number_format($oi["unit_price"], 2); ?></td>
                                    <td><?php echo number_format($oi["quantity"] * $oi["unit_price"], 2); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <p style="margin-top:14px;">
                    <a class="btn-link" href="order_invoice.php?order_id=<?php echo $orderDetails["order"]["id"]; ?>" target="_blank">Print Invoice</a>
                    &nbsp;|&nbsp;
                    <a class="btn-link" href="profile.php">Back to all orders</a>
                </p>
            </div>
        <?php } ?>

        <div class="cart-table-wrapper">
            <table class="cart-table profile-orders-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Total (BDT)</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($orderList) == 0){ ?>
                        <tr><td colspan="6">No orders yet. <a href="Home.php">Start shopping</a></td></tr>
                    <?php } ?>
                    <?php foreach($orderList as $ord){ ?>
                        <tr>
                            <td>#<?php echo $ord["id"]; ?></td>
                            <td><?php echo date("d M Y", strtotime($ord["order_date"])); ?></td>
                            <td><?php echo number_format($ord["total_amount"], 2); ?></td>
                            <td><?php echo htmlspecialchars($ord["payment_method"] ?? "—"); ?></td>
                            <td>
                                <span class="order-status-badge status-<?php echo $ord["status"]; ?>">
                                    <?php echo ucfirst($ord["status"]); ?>
                                </span>
                            </td>
                            <td>
                                <a class="btn-link" href="profile.php?order_id=<?php echo $ord["id"]; ?>">View Items</a>
                                <a class="btn-link" href="order_invoice.php?order_id=<?php echo $ord["id"]; ?>" target="_blank">Invoice</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>

</div>

</body>
</html>
