CREATE DATABASE IF NOT EXISTS clothing_shop;
USE clothing_shop;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','customer') NOT NULL DEFAULT 'customer',
    profile_picture VARCHAR(255),
    address TEXT NOT NULL,
    phone VARCHAR(30) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    parent_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    size_chart TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image_path VARCHAR(255),
    stock INT NOT NULL DEFAULT 0,
    gender ENUM('Men','Women') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    shipping_address TEXT,
    status ENUM('pending','confirmed','rejected') NOT NULL DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    transaction_id VARCHAR(100),
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- Gender root categories
INSERT INTO categories (name, parent_id)
SELECT 'Men', NULL WHERE NOT EXISTS (SELECT 1 FROM categories WHERE name = 'Men' AND parent_id IS NULL);

INSERT INTO categories (name, parent_id)
SELECT 'Women', NULL WHERE NOT EXISTS (SELECT 1 FROM categories WHERE name = 'Women' AND parent_id IS NULL);

INSERT INTO categories (name, parent_id)
SELECT 'Shirts', id FROM categories WHERE name = 'Men' AND parent_id IS NULL
AND NOT EXISTS (SELECT 1 FROM categories WHERE name = 'Shirts');

INSERT INTO categories (name, parent_id)
SELECT 'Pants', id FROM categories WHERE name = 'Men' AND parent_id IS NULL
AND NOT EXISTS (SELECT 1 FROM categories WHERE name = 'Pants');

INSERT INTO categories (name, parent_id)
SELECT 'Salwar', id FROM categories WHERE name = 'Women' AND parent_id IS NULL
AND NOT EXISTS (SELECT 1 FROM categories WHERE name = 'Salwar');

INSERT INTO categories (name, parent_id)
SELECT 'Jeans', id FROM categories WHERE name = 'Women' AND parent_id IS NULL
AND NOT EXISTS (SELECT 1 FROM categories WHERE name = 'Jeans');

-- Sample products
INSERT INTO products (name, description, size_chart, price, category_id, stock, gender, image_path)
SELECT 'Classic Cotton Shirt', 'Comfortable cotton shirt for men', 'S,M,L,XL,XXL', 1200.00, c.id, 50, 'Men', ''
FROM categories c WHERE c.name = 'Shirts'
AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Classic Cotton Shirt');

INSERT INTO products (name, description, size_chart, price, category_id, stock, gender, image_path)
SELECT 'Slim Fit Pants', 'Modern slim fit pants', '30,32,34,36,38', 1800.00, c.id, 40, 'Men', ''
FROM categories c WHERE c.name = 'Pants'
AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Slim Fit Pants');

INSERT INTO products (name, description, size_chart, price, category_id, stock, gender, image_path)
SELECT 'Embroidered Salwar', 'Traditional salwar set', 'S,M,L,XL', 2500.00, c.id, 30, 'Women', ''
FROM categories c WHERE c.name = 'Salwar'
AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Embroidered Salwar');

INSERT INTO products (name, description, size_chart, price, category_id, stock, gender, image_path)
SELECT 'High Waist Jeans', 'Stylish high waist jeans', '26,28,30,32,34', 2200.00, c.id, 35, 'Women', ''
FROM categories c WHERE c.name = 'Jeans'
AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'High Waist Jeans');

-- Demo accounts (password for both: Admin@12345)
INSERT INTO users (name, email, password_hash, role, address, phone, profile_picture)
SELECT 'Site Admin', 'admin@adore.local',
       '$2y$12$hFElozMNtN4JbgTMvEfrYe7Bmedlh3MGwYDHxsjSXLqQiOuvstA3u',
       'admin', '1 Admin Street, Dhaka', '01700000001', ''
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'admin@adore.local');

INSERT INTO users (name, email, password_hash, role, address, phone, profile_picture)
SELECT 'Jamie Shopper', 'customer@adore.local',
       '$2y$12$hFElozMNtN4JbgTMvEfrYe7Bmedlh3MGwYDHxsjSXLqQiOuvstA3u',
       'customer', '22 Customer Road, Dhaka', '01700000002', ''
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'customer@adore.local');
