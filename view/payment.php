<?php include '../control/payment_process.php'; include_once '../control/app.php';
$deliveryAddress = $_SESSION["delivery_address"] ?? $user["address"];
$payIcons = array(
    "Credit Card"     => "💳",
    "bKash"           => "📱",
    "Nagad"           => "📲",
    "Bank Transfer"   => "🏦",
    "Cash on Delivery"=> "💵"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - A$ADORÉ</title>
    <link rel="stylesheet" href="../css/task3_style.css">
    <link rel="stylesheet" href="../css/task4_style.css">
</head>
<body>
<nav>
    <div class="nav-inner">
        <a class="nav-brand" href="Home.php">A$ADORÉ</a>
        <div class="nav-links">
            <a href="Home.php">Home</a>
            <a href="cart.php">Cart</a>
            <a href="profile.php">Profile</a>
            <a href="../control/logout_process.php">Logout</a>
        </div>
    </div>
</nav>

<div class="page-header">
    <h1>Payment</h1>
    <p>Step 2 — Select payment method and place order</p>
</div>

<div class="main-container">

    <div class="checkout-steps">
        <span class="step-pill done">1. Invoice</span>
        <span class="step-pill active">2. Payment</span>
        <span class="step-pill">3. Confirmation</span>
    </div>

    <div id="paymentMsg"></div>

    <?php if(!empty($errors["stock"])){ ?>
        <div class="msg-error"><?php echo esc($errors["stock"]); ?></div>
    <?php } ?>
    <?php if(!empty($errors["database"])){ ?>
        <div class="msg-error"><?php echo esc($errors["database"]); ?></div>
    <?php } ?>
    <?php if(!empty($errors["address"])){ ?>
        <div class="msg-error"><?php echo esc($errors["address"]); ?></div>
    <?php } ?>

    <div class="card payment-page-card">
        <div class="payment-summary">
            <h3>Order Summary</h3>
            <p><strong>Items:</strong> <?php echo count($itemsArray); ?> product(s)</p>
            <p><strong>Total:</strong> BDT <?php echo number_format($totalAmount, 2); ?></p>
            <p><strong>Deliver To:</strong> <?php echo esc($deliveryAddress); ?></p>
        </div>

        <h2 class="section-title">Payment Method *</h2>

        <form action="" method="post" id="paymentForm" novalidate>
            <?php echo csrfField(); ?>
            <input type="hidden" id="csrf_token_ajax" value="<?php echo esc(csrfToken()); ?>">
            <div class="payment-options">
                <?php
                $methods = array("Credit Card", "bKash", "Nagad", "Bank Transfer", "Cash on Delivery");
                foreach($methods as $m){
                    $icon = $payIcons[$m] ?? "✓";
                ?>
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="<?php echo esc($m); ?>">
                    <span class="pay-icon"><?php echo $icon; ?></span>
                    <span class="pay-label"><?php echo esc($m); ?></span>
                </label>
                <?php } ?>
            </div>
            <span id="paymentMethodError" class="form-error-inline"><?php echo !empty($errors["payment_method"]) ? esc($errors["payment_method"]) : ""; ?></span>

            <div class="cart-actions" style="margin-top:20px;">
                <a class="btn-cancel" href="checkout.php">Back</a>
                <input type="submit" name="confirm_payment" class="btn-primary" value="Place Order">
            </div>
        </form>
    </div>

</div>

<script src="../js/task4_script.js"></script>
</body>
</html>
