<?php include '../control/order_invoice_process.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo (int)$order["id"]; ?> - A$ADORÉ</title>
    <link rel="stylesheet" href="../css/task3_style.css">
    <link rel="stylesheet" href="../css/task4_style.css">
</head>
<body>
<div class="invoice-print-page" id="invoiceArea">
    <div class="invoice-print-header">
        <div>
            <p class="brand-tag">Fashion · Premium</p>
            <h1>A$ADORÉ</h1>
            <p>Online Clothing Brand</p>
        </div>
        <div class="invoice-print-meta">
            <strong>INVOICE</strong>
            <p>Order #<?php echo (int)$order["id"]; ?></p>
            <p><?php echo date("d M Y, h:i A", strtotime($order["order_date"])); ?></p>
            <p>Status: <?php echo esc(ucfirst($order["status"])); ?></p>
        </div>
    </div>
    <div class="invoice-box">
        <h3>Bill To</h3>
        <p><?php echo esc($customer["name"]); ?></p>
        <p><?php echo esc($customer["email"]); ?></p>
        <p><?php echo esc($customer["phone"]); ?></p>
    </div>
    <div class="invoice-box">
        <h3>Ship To</h3>
        <p><?php echo esc(!empty($order["shipping_address"]) ? $order["shipping_address"] : $customer["address"]); ?></p>
    </div>
    <?php if($payment){ ?>
    <div class="invoice-box">
        <h3>Payment</h3>
        <p>Method: <?php echo esc($payment["payment_method"]); ?></p>
        <p>Transaction ID: <?php echo esc($payment["transaction_id"]); ?></p>
        <p>Amount: BDT <?php echo number_format($payment["amount"], 2); ?></p>
    </div>
    <?php } ?>
    <table class="cart-table">
        <thead><tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr></thead>
        <tbody>
        <?php while($item = $orderItems->fetch_assoc()){ ?>
            <tr>
                <td><?php echo esc($item["product_name"]); ?></td>
                <td><?php echo (int)$item["quantity"]; ?></td>
                <td>BDT <?php echo number_format($item["unit_price"], 2); ?></td>
                <td>BDT <?php echo number_format($item["quantity"] * $item["unit_price"], 2); ?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="cart-total-label">Grand Total</td>
                <td class="cart-total-value">BDT <?php echo number_format($order["total_amount"], 2); ?></td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="invoice-print-actions no-print">
    <button type="button" class="btn-primary" onclick="window.print()">Print Invoice</button>
    <a class="btn-cancel" href="profile.php?order_id=<?php echo (int)$order["id"]; ?>">Back to Order</a>
    <a class="btn-cancel" href="profile.php">Purchase History</a>
</div>
</body>
</html>
