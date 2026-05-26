<?php
include '../control/admin_customers_process.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management - A$ADORÉ</title>
    <link rel="stylesheet" type="text/css" href="../css/task2_style.css">
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
            <span class="nav-user-info">Admin: <?php echo htmlspecialchars($_SESSION["name"]); ?></span>
        </div>
    </div>
</nav>

<div class="page-header">
    <h1>Customer Management</h1>
    <p>View and delete registered customers</p>
</div>

<div class="main-container">

    <?php if(!empty($errors["delete"])){ ?>
        <div class="msg-error"><?php echo htmlspecialchars($errors["delete"]); ?></div>
    <?php } ?>
    <?php if($success != ""){ ?>
        <div class="msg-success"><?php echo htmlspecialchars($success); ?></div>
    <?php } ?>

    <?php if($customers->num_rows == 0){ ?>
        <div class="no-results">No customers registered yet.</div>
    <?php } else { ?>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Registered</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; while($customer = $customers->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($customer["name"]); ?></td>
                            <td><?php echo htmlspecialchars($customer["email"]); ?></td>
                            <td><?php echo htmlspecialchars($customer["phone"]); ?></td>
                            <td><?php echo htmlspecialchars($customer["address"]); ?></td>
                            <td><?php echo date("d M Y", strtotime($customer["created_at"])); ?></td>
                            <td>
                                <form action="" method="post" style="display:inline;"
                                      onsubmit="return confirmDelete('customer and all their order data')">
                                    <input type="hidden" name="user_id" value="<?php echo $customer["id"]; ?>">
                                    <button type="submit" name="delete_customer" class="btn-action btn-delete-sm">Delete</button>
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
