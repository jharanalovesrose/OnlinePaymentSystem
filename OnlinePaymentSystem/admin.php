<?php
session_start();
include "db.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Check if user is admin
if ($_SESSION["role"] !== "admin") {
    header("Location: dashboard.php");
    exit();
}

// Count total products
$product_query = "SELECT COUNT(*) AS total FROM products";
$product_result = mysqli_query($conn, $product_query);
$product_data = mysqli_fetch_assoc($product_result);
$total_products = $product_data["total"];

// Count total customers
$customer_query = "SELECT COUNT(*) AS total FROM users WHERE role = 'customer'";
$customer_result = mysqli_query($conn, $customer_query);
$customer_data = mysqli_fetch_assoc($customer_result);
$total_customers = $customer_data["total"];

// Count total transactions
$transaction_query = "SELECT COUNT(*) AS total FROM transactions";
$transaction_result = mysqli_query($conn, $transaction_query);
$transaction_data = mysqli_fetch_assoc($transaction_result);
$total_transactions = $transaction_data["total"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | Reliance Digital</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f0fdfa;
            color: #123331;
        }

        /* ================= SIDEBAR ================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            height: 100vh;
            background: #134e4a;
            padding: 25px 15px;
            color: white;
            z-index: 1000;
        }

        .logo {
            text-align: center;
            font-size: 23px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .logo span {
            color: #f97316;
        }

        .menu-title {
            font-size: 12px;
            color: #99f6e4;
            margin: 20px 10px 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar a {
            display: block;
            text-decoration: none;
            color: #ccfbf1;
            padding: 13px 15px;
            margin-bottom: 7px;
            border-radius: 8px;
            transition: 0.3s;
            font-size: 15px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #0f766e;
            color: white;
        }

        .sidebar a .icon {
            margin-right: 10px;
        }

        .logout {
            position: absolute;
            bottom: 25px;
            left: 15px;
            right: 15px;
        }

        .logout a {
            background: #115e59;
        }

        .logout a:hover {
            background: #f97316;
        }


        /* ================= MAIN ================= */

        .main {
            margin-left: 240px;
            padding: 30px;
        }


        /* ================= TOPBAR ================= */

        .topbar {
            background: white;
            border-radius: 15px;
            padding: 22px 25px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 20px rgba(15, 118, 110, 0.08);
        }

        .topbar h1 {
            font-size: 25px;
            color: #134e4a;
            margin-bottom: 5px;
        }

        .topbar p {
            color: #64748b;
            font-size: 14px;
        }

        .admin-badge {
            background: #ffedd5;
            color: #ea580c;
            padding: 10px 17px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: bold;
        }


        /* ================= WELCOME ================= */

        .welcome {
            background: linear-gradient(135deg, #0f766e, #134e4a);
            color: white;
            padding: 28px;
            border-radius: 18px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .welcome::after {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            background: rgba(249, 115, 22, 0.2);
            border-radius: 50%;
            right: -50px;
            top: -70px;
        }

        .welcome h2 {
            font-size: 25px;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .welcome p {
            color: #ccfbf1;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }


        /* ================= SECTION TITLE ================= */

        .section-title {
            font-size: 19px;
            color: #134e4a;
            margin-bottom: 15px;
        }


        /* ================= STAT CARDS ================= */

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(15, 118, 110, 0.08);
            display: flex;
            align-items: center;
            gap: 17px;
            transition: 0.3s;
            border-left: 5px solid #0f766e;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(15, 118, 110, 0.12);
        }

        .stat-card:nth-child(2) {
            border-left-color: #f97316;
        }

        .stat-card:nth-child(3) {
            border-left-color: #0f766e;
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            background: #f0fdfa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        .stat-card:nth-child(2) .stat-icon {
            background: #ffedd5;
        }

        .stat-info h3 {
            font-size: 28px;
            color: #134e4a;
            margin-bottom: 4px;
        }

        .stat-info p {
            color: #64748b;
            font-size: 13px;
        }


        /* ================= QUICK ACTIONS ================= */

        .actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .action-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 5px 20px rgba(15, 118, 110, 0.08);
            transition: 0.3s;
            border: 1px solid #ccfbf1;
        }

        .action-card:hover {
            transform: translateY(-5px);
            border-color: #f97316;
            box-shadow: 0 10px 25px rgba(15, 118, 110, 0.12);
        }

        .action-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: #f0fdfa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 18px;
        }

        .action-card:nth-child(2) .action-icon {
            background: #ffedd5;
        }

        .action-card h3 {
            color: #134e4a;
            font-size: 17px;
            margin-bottom: 8px;
        }

        .action-card p {
            color: #64748b;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .action-link {
            color: #f97316;
            font-size: 13px;
            font-weight: bold;
        }


        /* ================= INFO SECTION ================= */

        .info-section {
            margin-top: 35px;
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(15, 118, 110, 0.08);
        }

        .info-section h2 {
            color: #134e4a;
            font-size: 19px;
            margin-bottom: 10px;
        }

        .info-section p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.7;
        }


        /* ================= FOOTER ================= */

        .footer {
            margin-top: 40px;
            padding: 25px;
            text-align: center;
            background: #123331;
            color: #ccfbf1;
            border-radius: 15px;
            font-size: 13px;
        }

        .footer span {
            color: #f97316;
            font-weight: bold;
        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 1000px) {

            .stats,
            .actions {
                grid-template-columns: repeat(2, 1fr);
            }

        }


        @media (max-width: 700px) {

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 15px;
            }

            .logo {
                margin-bottom: 15px;
            }

            .menu-title {
                display: none;
            }

            .sidebar a {
                display: inline-block;
                margin-right: 5px;
                padding: 9px 10px;
            }

            .logout {
                position: relative;
                bottom: auto;
                left: auto;
                right: auto;
                margin-top: 5px;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .stats,
            .actions {
                grid-template-columns: 1fr;
            }

        }

    </style>
</head>

<body>


<!-- ================= SIDEBAR ================= -->

<div class="sidebar">

    <div class="logo">
        Reliance <span>Digital</span>
    </div>

    <div class="menu-title">
        Main Menu
    </div>

    <a href="admin.php" class="active">
        <span class="icon">📊</span>
        Dashboard
    </a>

    <div class="menu-title">
        Store Management
    </div>

    <a href="add_product.php">
        <span class="icon">➕</span>
        Add Product
    </a>

    <a href="admin_products.php">
        <span class="icon">🛍️</span>
        View Products
    </a>

    <a href="admin_transactions.php">
        <span class="icon">💳</span>
        Transactions
    </a>

    <div class="logout">
        <a href="logout.php">
            <span class="icon">🚪</span>
            Logout
        </a>
    </div>

</div>


<!-- ================= MAIN CONTENT ================= -->

<div class="main">


    <!-- TOPBAR -->

    <div class="topbar">

        <div>
            <h1>Store Management Dashboard</h1>
            <p>Manage your Reliance Digital online store.</p>
        </div>

        <div class="admin-badge">
            👤 Administrator
        </div>

    </div>


    <!-- WELCOME -->

    <div class="welcome">

        <h2>Welcome, Admin! 👋</h2>

        <p>
            Manage products, customers and payment transactions
            from one convenient dashboard.
        </p>

    </div>


    <!-- STATISTICS -->

    <h2 class="section-title">
        Store Overview
    </h2>

    <div class="stats">


        <!-- PRODUCTS -->

        <div class="stat-card">

            <div class="stat-icon">
                🛍️
            </div>

            <div class="stat-info">

                <h3>
                    <?php echo $total_products; ?>
                </h3>

                <p>
                    Total Products
                </p>

            </div>

        </div>


        <!-- CUSTOMERS -->

        <div class="stat-card">

            <div class="stat-icon">
                👥
            </div>

            <div class="stat-info">

                <h3>
                    <?php echo $total_customers; ?>
                </h3>

                <p>
                    Total Customers
                </p>

            </div>

        </div>


        <!-- TRANSACTIONS -->

        <div class="stat-card">

            <div class="stat-icon">
                💳
            </div>

            <div class="stat-info">

                <h3>
                    <?php echo $total_transactions; ?>
                </h3>

                <p>
                    Total Transactions
                </p>

            </div>

        </div>

    </div>


    <!-- QUICK ACTIONS -->

    <h2 class="section-title">
        Quick Actions
    </h2>

    <div class="actions">


        <!-- ADD PRODUCT -->

        <a href="add_product.php" class="action-card">

            <div class="action-icon">
                ➕
            </div>

            <h3>
                Add Product
            </h3>

            <p>
                Add new electronics and appliances
                to your online store.
            </p>

            <div class="action-link">
                Add Product →
            </div>

        </a>


        <!-- VIEW PRODUCTS -->

        <a href="admin_products.php" class="action-card">

            <div class="action-icon">
                🛍️
            </div>

            <h3>
                View Products
            </h3>

            <p>
                View, manage and delete products
                available in your store.
            </p>

            <div class="action-link">
                View Products →
            </div>

        </a>


        <!-- TRANSACTIONS -->

        <a href="admin_transactions.php" class="action-card">

            <div class="action-icon">
                💳
            </div>

            <h3>
                View Transactions
            </h3>

            <p>
                Check customer payments and
                transaction details.
            </p>

            <div class="action-link">
                View Transactions →
            </div>

        </a>

    </div>


    <!-- INFORMATION -->

    <div class="info-section">

        <h2>
            💡 Store Management
        </h2>

        <p>
            From this dashboard, you can manage products,
            monitor customers and review payment transactions.
            Use the menu on the left to access different
            sections of the Reliance Digital store.
        </p>

    </div>


    <!-- FOOTER -->

    <div class="footer">

        © 2026 <span>Reliance Digital</span>.
        Online Payment System — Admin Panel

    </div>

</div>


</body>
</html>