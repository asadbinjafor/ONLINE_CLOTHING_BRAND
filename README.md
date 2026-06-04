# A$ADORÉ Clothing

## Project Scenario Summary

**A$ADORÉ Clothing** is a web-based online clothing store where customers can browse Men’s and Women’s fashion, add items to a cart, and complete checkout with payment. Admins manage the catalog, customers, and orders from a dedicated dashboard.

The system supports two registered roles and one public (guest) experience:

| Role | Description |
|------|-------------|
| **Admin** | Manages products (create, edit, delete with image upload), views customers, confirms or rejects orders, and reviews order/payment history. |
| **Customer** | Registers and logs in, browses and searches products, manages cart (AJAX), checks out, pays, and views order invoice and profile. |
| **Guest** | Can view the home page and product listings; must register and log in as a customer to use cart, checkout, and profile features. |

**Typical workflow:** Admin adds or updates products → customer browses and adds items to cart → customer checks out and submits payment → admin confirms the order → customer sees order success and invoice.

This project was built as **Web Technologies — Project 07**, using a PHP layered structure (`view/`, `control/`, `model/`) with validation, security helpers, and AJAX for dynamic shopping features.

---

## Technologies & Topics Used

The project applies front-end, back-end, database, and security topics from web technologies courses.

### Front-End

| Topic | How it is used in this project |
|-------|--------------------------------|
| **HTML5** | Page structure, forms, tables, navigation, product cards, admin panels |
| **CSS3** | Layout, custom styling per task (`task1_style.css`–`task4_style.css`), responsive UI, cards, badges, alerts, forms |
| **JavaScript** | Client-side validation (`task1_script.js`–`task4_script.js`), live product search/filter, cart updates via AJAX (XMLHttpRequest), checkout and payment validation |

### Back-End

| Topic | How it is used in this project |
|-------|--------------------------------|
| **PHP** | Server-side logic, sessions, form processing, JSON API endpoints |
| **Layered structure** | Separation into `view/` (pages), `control/` (process & API), `model/` (database access), `control/app.php` (app constants) |
| **MySQLi (prepared statements)** | Database connection and parameterized queries (SQL injection prevention) |
| **Sessions & cookies** | Login state, roles, “Remember Me” (signed cookie), CSRF tokens |
| **File upload** | Profile pictures and product images with MIME type and size checks |
| **Password security** | `password_hash()` on register/update; `password_verify()` on login |

### Database

| Topic | How it is used in this project |
|-------|--------------------------------|
| **MySQL** | Relational database `clothing_shop` |
| **Tables** | `users`, `categories`, `products`, `cart`, `orders`, `order_items`, `payments` |
| **Keys & integrity** | Foreign keys linking cart, orders, products, and categories |

### Other Web Topics

| Topic | How it is used in this project |
|-------|--------------------------------|
| **AJAX / JSON** | Product search, cart add/update/remove, order placement API responses |
| **XSS prevention** | `htmlspecialchars()` / `esc()` when displaying user and product data |
| **CSRF protection** | Hidden token on forms; verified on sensitive POST requests |
| **Security headers** | `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy` via `control/security.php` |
| **Role-based access** | `admin_gate.php` and `customer_gate.php` restrict admin and customer areas |
| **Responsive UI** | Viewport meta tags and flexible layouts for browsing on different screen sizes |
| **Apache (XAMPP)** | Local hosting; `index.php` redirects to the home page |

---

## Default User Credentials

After importing `database.sql`, you can log in with these demo accounts:

| Role | Display Name | Email | Password |
|------|--------------|-------|----------|
| **Admin** | Site Admin | `admin@adore.local` | `Admin@12345` |
| **Customer** | Jamie Shopper | `customer@adore.local` | `Admin@12345` |

> **Note:** Both accounts use the same password: **Admin@12345**

You can also register new accounts from **Register** and choose **Admin** or **Customer** role. For production use, restrict who can register as admin.

---

## How to Run the Project

1. Install **XAMPP** and start **Apache** and **MySQL**.
2. Copy the project folder to `htdocs` (e.g. `C:\xampp\htdocs\WTProject_07`).
3. Import **`database.sql`** in phpMyAdmin (creates database `clothing_shop`, sample categories/products, and seed users).
4. Open: **http://localhost/WTProject_07/index.php**
5. Log in with any default email and password from the table above.

If the folder name is not `WTProject_07`, use your folder name in the URL. Database settings are in `model/database.php` (default: host `localhost`, user `root`, empty password).

Ensure upload folders exist or are created automatically: `uploads/profile/` and `uploads/products/`.

---

## Main Modules (Assignment Tasks)

| Task | Module | Main features |
|------|--------|-----------------|
| **Task 1** | Auth & profile | Register, login, remember me, profile view/edit, password change, home page |
| **Task 2** | Admin | Dashboard, product CRUD with image upload, customer list, order approve/reject, history |
| **Task 3** | Customer shop | Browse products, live search/filter (AJAX), product detail, cart (AJAX add/update/remove) |
| **Task 4** | Checkout & payment | Checkout, place order API, payment form, order success, invoice |

---

## Project Folder Overview

```
WTProject_07/
├── index.php              → Entry redirect to home
├── database.sql           → Schema, sample data, seed users
├── model/
│   ├── database.php       → DB connection constants
│   └── mydb.php           → Data access (MySQLi prepared statements)
├── control/               → Process scripts & JSON APIs
├── view/                  → HTML/PHP pages
├── css/                   → task1–task4 stylesheets
├── js/                    → Validation & AJAX scripts
├── uploads/
│   ├── profile/           → User profile pictures
│   └── products/          → Product images
└── README.md              → This file
```

---

## Security Features (Summary)

- Prepared statements for database queries in `model/mydb.php`
- Hashed passwords (never stored as plain text)
- CSRF tokens on form submissions and order API
- Escaped output to reduce XSS risk
- Role-based access for admin and customer areas
- Validated file uploads (JPEG/PNG, size limit)
- Secure session cookie options and optional “Remember Me” HMAC cookie

---

This README describes the project scenario, technologies used, default login details, and setup steps for reviewers, instructors, and GitHub visitors.
