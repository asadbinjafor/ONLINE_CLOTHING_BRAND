<?php include '../control/checkout_process.php'; include_once '../control/app.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - A$ADORÉ</title>
    <link rel="stylesheet" href="../css/task3_style.css">
    <link rel="stylesheet" href="../css/task4_style.css">
</head>
<body>

<nav>
    <div class="nav-inner">
        <a class="nav-brand" href="Home.php">A$ADORÉ</a>
        <div class="nav-links">
            <a href="Home.php">Home</a>
            <a href="cart.php">Cart (<span id="navCartCount"><?php echo (int)$cartCount; ?></span>)</a>
            <a href="profile.php">Profile</a>
            <a href="../control/logout_process.php">Logout</a>
        </div>
    </div>
</nav>

<div class="page-header">
    <h1>Checkout</h1>
    <p>Step 1 — Review invoice and delivery details</p>
</div>

<div class="main-container">

    <div class="checkout-steps">
        <span class="step-pill active">1. Invoice</span>
        <span class="step-pill">2. Payment</span>
        <span class="step-pill">3. Confirmation</span>
    </div>

    <?php if(!empty($errors["stock"])){ ?>
        <div class="msg-error"><?php echo esc($errors["stock"]); ?></div>
    <?php } ?>

    <div class="checkout-layout">
        <div class="card invoice-card">
            <h2 class="section-title">Order Invoice</h2>

            <div class="invoice-box">
                <h3>Customer Information</h3>
                <p><strong>Name:</strong> <?php echo esc($user["name"]); ?></p>
                <p><strong>Email:</strong> <?php echo esc($user["email"]); ?></p>
                <p><strong>Phone:</strong> <?php echo esc($user["phone"]); ?></p>
            </div>

            <div class="cart-table-wrapper">
                <table class="cart-table">
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
                        <?php foreach($itemsArray as $item){ ?>
                            <tr>
                                <td><?php echo esc($item["name"]); ?></td>
                                <td><?php echo esc($item["gender"]); ?></td>
                                <td><?php echo (int)$item["quantity"]; ?></td>
                                <td><?php echo number_format($item["price"], 2); ?></td>
                                <td><?php echo number_format($item["subtotal"], 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="cart-total-label">Total Amount:</td>
                            <td class="cart-total-value">BDT <?php echo number_format($totalAmount, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <form action="" method="post" id="checkoutForm" novalidate>
                <?php echo csrfField(); ?>
                <div class="checkout-address-box form-group">
                    <label for="delivery_address">Delivery Address *</label>
                    <textarea id="delivery_address" name="delivery_address" placeholder="House, road, area, city — full delivery address" required><?php echo esc($deliveryAddress); ?></textarea>
                    <span id="addressError" class="form-error-inline"><?php echo !empty($errors["address"]) ? esc($errors["address"]) : ""; ?></span>
                </div>

                <div class="cart-actions">
                    <a class="btn-cancel" href="cart.php">Cancel</a>
                    <input type="submit" name="confirm_checkout" class="btn-primary" value="Continue to Payment">
                </div>
            </form>
        </div>

        <aside class="checkout-sidebar">
            <h3>Order Summary</h3>
            <div class="summary-row"><span>Items</span><span><?php echo count($itemsArray); ?></span></div>
            <div class="summary-row"><span>Products</span><span><?php echo (int)$cartCount; ?> in cart</span></div>
            <div class="summary-total"><span>Total</span><span>BDT <?php echo number_format($totalAmount, 2); ?></span></div>
            <div class="trust-badges">
                <span class="trust-badge">Secure checkout</span>
                <span class="trust-badge">Stock verified</span>
            </div>
        </aside>
    </div>

</div>

<script src="../js/task4_script.js"></script>
</body>
</html>
