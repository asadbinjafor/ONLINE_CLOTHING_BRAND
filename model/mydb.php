<?php
include_once __DIR__ . "/database.php";

class MyDB {

    function createConn(){
        return new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    function createUser($name, $email, $passwordHash, $role, $address, $phone, $profilePicture, $conn){
        $sql  = "INSERT INTO users (name, email, password_hash, role, address, phone, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $name, $email, $passwordHash, $role, $address, $phone, $profilePicture);
        return $stmt->execute();
    }

    function emailExists($email, $conn){
        $sql  = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result();
    }

    function emailExistsForOtherUser($email, $userId, $conn){
        $sql  = "SELECT id FROM users WHERE email = ? AND id <> ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getUserByEmail($email, $conn){
        $sql  = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getUserById($id, $conn){
        $sql  = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    function updateProfile($id, $name, $email, $address, $phone, $profilePicture, $conn){
        $sql  = "UPDATE users SET name = ?, email = ?, address = ?, phone = ?, profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $email, $address, $phone, $profilePicture, $id);
        return $stmt->execute();
    }

    function updatePassword($id, $passwordHash, $conn){
        $sql  = "UPDATE users SET password_hash = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $passwordHash, $id);
        return $stmt->execute();
    }

    function getRootCategories($conn){
        $sql = "SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name";
        return $conn->query($sql);
    }

    function getChildCategories($parentId, $conn){
        $sql  = "SELECT * FROM categories WHERE parent_id = ? ORDER BY name";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $parentId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getCategoryById($id, $conn){
        $sql  = "SELECT * FROM categories WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getCategoriesByGender($gender, $conn){
        $sql  = "SELECT child.* FROM categories child
                 JOIN categories parent ON child.parent_id = parent.id
                 WHERE parent.name = ? ORDER BY child.name";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $gender);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getFeaturedProducts($limit, $conn){
        $sql  = "SELECT products.*, categories.name AS category_name
                 FROM products
                 LEFT JOIN categories ON products.category_id = categories.id
                 ORDER BY products.created_at DESC LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getProducts($categoryId, $gender, $conn){
        $sql  = "SELECT products.*, categories.name AS category_name
                 FROM products
                 LEFT JOIN categories ON products.category_id = categories.id
                 WHERE (? = 0 OR products.category_id = ?)
                 AND (? = '' OR products.gender = ?)
                 ORDER BY products.name";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $categoryId, $categoryId, $gender, $gender);
        $stmt->execute();
        return $stmt->get_result();
    }

    function searchProducts($q, $categoryId, $gender, $conn){
        $search = "%" . $q . "%";
        $sql    = "SELECT products.id, products.name, products.description, products.size_chart,
                          products.price, products.stock, products.gender, products.image_path,
                          categories.name AS category_name
                   FROM products
                   LEFT JOIN categories ON products.category_id = categories.id
                   WHERE products.name LIKE ?
                   AND (? = 0 OR products.category_id = ?)
                   AND (? = '' OR products.gender = ?)
                   ORDER BY products.name";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siiss", $search, $categoryId, $categoryId, $gender, $gender);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getProductById($id, $conn){
        $sql  = "SELECT products.*, categories.name AS category_name
                 FROM products
                 LEFT JOIN categories ON products.category_id = categories.id
                 WHERE products.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getAllProducts($conn){
        $sql = "SELECT products.*, categories.name AS category_name
                FROM products
                LEFT JOIN categories ON products.category_id = categories.id
                ORDER BY products.name";
        return $conn->query($sql);
    }

    function createProduct($name, $description, $sizeChart, $price, $categoryId, $gender, $stock, $imagePath, $conn){
        $sql  = "INSERT INTO products (name, description, size_chart, price, category_id, gender, stock, image_path)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdiss", $name, $description, $sizeChart, $price, $categoryId, $gender, $stock, $imagePath);
        return $stmt->execute();
    }

    function updateProduct($id, $name, $description, $sizeChart, $price, $categoryId, $gender, $stock, $imagePath, $conn){
        $sql  = "UPDATE products SET name = ?, description = ?, size_chart = ?, price = ?,
                 category_id = ?, gender = ?, stock = ?, image_path = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdissi", $name, $description, $sizeChart, $price, $categoryId, $gender, $stock, $imagePath, $id);
        return $stmt->execute();
    }

    function deleteProduct($id, $conn){
        $sql  = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    function productInOrder($productId, $conn){
        $sql  = "SELECT id FROM order_items WHERE product_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    function getDashboardCounts($conn){
        $counts = array();
        $row = $conn->query("SELECT COUNT(*) AS cnt FROM products")->fetch_assoc();
        $counts["products"] = (int)$row["cnt"];
        $row = $conn->query("SELECT COUNT(*) AS cnt FROM users WHERE role = 'customer'")->fetch_assoc();
        $counts["customers"] = (int)$row["cnt"];
        $row = $conn->query("SELECT COUNT(*) AS cnt FROM orders")->fetch_assoc();
        $counts["orders"] = (int)$row["cnt"];
        $row = $conn->query("SELECT COUNT(*) AS cnt FROM orders WHERE status = 'pending'")->fetch_assoc();
        $counts["pending_orders"] = (int)$row["cnt"];
        return $counts;
    }

    function getAllCustomers($conn){
        $sql = "SELECT id, name, email, phone, address, created_at FROM users
                WHERE role = 'customer' ORDER BY created_at DESC";
        return $conn->query($sql);
    }

    function deleteUserCart($userId, $conn){
        $sql  = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    function deleteUserOrderItems($userId, $conn){
        $sql  = "DELETE oi FROM order_items oi JOIN orders o ON oi.order_id = o.id WHERE o.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    function deleteUserPayments($userId, $conn){
        $sql  = "DELETE p FROM payments p JOIN orders o ON p.order_id = o.id WHERE o.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    function deleteUserOrders($userId, $conn){
        $sql  = "DELETE FROM orders WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    function deleteUser($userId, $conn){
        $sql  = "DELETE FROM users WHERE id = ? AND role = 'customer'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    function getAllOrders($conn){
        $sql = "SELECT orders.*, users.name AS customer_name, users.email AS customer_email
                FROM orders JOIN users ON orders.user_id = users.id
                ORDER BY orders.order_date DESC";
        return $conn->query($sql);
    }

    function getOrderById($orderId, $conn){
        $sql  = "SELECT orders.*, users.name AS customer_name, users.email AS customer_email
                 FROM orders JOIN users ON orders.user_id = users.id WHERE orders.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function updateOrderStatus($orderId, $status, $conn){
        $sql  = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $orderId);
        return $stmt->execute();
    }

    function getOrderItems($orderId, $conn){
        $sql  = "SELECT order_items.*, products.name AS product_name, products.gender
                 FROM order_items JOIN products ON order_items.product_id = products.id
                 WHERE order_items.order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getConfirmedOrders($conn){
        $sql = "SELECT orders.*, users.name AS customer_name, users.email AS customer_email
                FROM orders JOIN users ON orders.user_id = users.id
                WHERE orders.status = 'confirmed' ORDER BY orders.order_date DESC";
        return $conn->query($sql);
    }

    function getCustomerOrders($userId, $conn){
        $sql  = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getCustomerOrdersWithPayment($userId, $conn){
        $sql  = "SELECT orders.*, payments.payment_method, payments.transaction_id
                 FROM orders
                 LEFT JOIN payments ON orders.id = payments.order_id
                 WHERE orders.user_id = ?
                 ORDER BY orders.order_date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getOrderWithItems($orderId, $userId, $conn){
        $sql  = "SELECT orders.*, users.name AS customer_name, users.email AS customer_email
                 FROM orders JOIN users ON orders.user_id = users.id
                 WHERE orders.id = ? AND orders.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $orderId, $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getPaymentByOrderId($orderId, $conn){
        $sql  = "SELECT * FROM payments WHERE order_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function addToCart($userId, $productId, $quantity, $conn){
        $sql  = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $newQty = $row["quantity"] + $quantity;
            $sql2  = "UPDATE cart SET quantity = ? WHERE id = ?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("ii", $newQty, $row["id"]);
            return $stmt2->execute();
        }
        $sql2  = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("iii", $userId, $productId, $quantity);
        return $stmt2->execute();
    }

    function getCartItems($userId, $conn){
        $sql  = "SELECT cart.id, cart.product_id, cart.quantity,
                        products.name, products.price, products.stock, products.image_path, products.gender
                 FROM cart JOIN products ON cart.product_id = products.id
                 WHERE cart.user_id = ? ORDER BY cart.added_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getCartCount($userId, $conn){
        $sql  = "SELECT COALESCE(SUM(quantity), 0) AS total FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (int)$row["total"];
    }

    function updateCartQuantity($cartId, $userId, $quantity, $conn){
        $sql  = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $quantity, $cartId, $userId);
        return $stmt->execute();
    }

    function removeCartItem($cartId, $userId, $conn){
        $sql  = "DELETE FROM cart WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cartId, $userId);
        return $stmt->execute();
    }

    function clearCart($userId, $conn){
        $sql  = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    function createOrder($userId, $totalAmount, $shippingAddress, $conn){
        $shippingAddress = trim((string)$shippingAddress);
        $sql  = "INSERT INTO orders (user_id, total_amount, shipping_address, status) VALUES (?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ids", $userId, $totalAmount, $shippingAddress);
        if($stmt->execute()){
            return $conn->insert_id;
        }
        return false;
    }

    function createOrderItem($orderId, $productId, $quantity, $unitPrice, $conn){
        $sql  = "INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $orderId, $productId, $quantity, $unitPrice);
        return $stmt->execute();
    }

    function createPayment($orderId, $amount, $paymentMethod, $transactionId, $conn){
        $sql  = "INSERT INTO payments (order_id, amount, payment_method, transaction_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idss", $orderId, $amount, $paymentMethod, $transactionId);
        return $stmt->execute();
    }

    function decreaseStock($productId, $quantity, $conn){
        $sql  = "UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $quantity, $productId, $quantity);
        return $stmt->execute();
    }

    function increaseStock($productId, $quantity, $conn){
        $sql  = "UPDATE products SET stock = stock + ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $quantity, $productId);
        return $stmt->execute();
    }

    function closeConn($conn){
        if(!($conn instanceof mysqli)){
            return;
        }
        $threadId = $conn->thread_id;
        if($threadId !== null && $threadId !== 0){
            $conn->close();
        }
    }
}
?>
