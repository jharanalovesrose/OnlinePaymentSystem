<?php

session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["product_id"])) {
    header("Location: products.php");
    exit();
}

$product_id = $_GET["product_id"];

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "Product not found.";
    exit();
}

$product = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payment | Reliance Digital</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f0fdfa;
            color: #1e293b;
        }


        /* ================= NAVBAR ================= */

        .navbar {
            background: white;
            padding: 18px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccfbf1;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 25px;
            font-weight: bold;
            color: #0f766e;
        }

        .logo span {
            color: #f97316;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .nav-links a {
            text-decoration: none;
            color: #475569;
            font-weight: 600;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #0f766e;
        }

        .nav-links a.active {
            color: #0f766e;
        }

        .logout {
            background: #f97316;
            color: white !important;
            padding: 10px 20px;
            border-radius: 7px;
        }

        .logout:hover {
            background: #ea580c !important;
        }


        /* ================= MAIN ================= */

        .main {
            width: 90%;
            max-width: 850px;
            margin: auto;
            padding: 55px 0 70px;
        }


        /* ================= HEADING ================= */

        .heading {
            text-align: center;
            margin-bottom: 35px;
        }

        .small-title {
            display: inline-block;
            background: #ffedd5;
            color: #ea580c;
            padding: 7px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .heading h1 {
            margin: 0 0 12px;
            color: #134e4a;
            font-size: 40px;
        }

        .heading h1 span {
            color: #f97316;
        }

        .heading p {
            margin: 0;
            color: #64748b;
            font-size: 16px;
        }


        /* ================= PAYMENT CARD ================= */

        .payment-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #ccfbf1;
            box-shadow: 0 10px 30px rgba(15, 118, 110, 0.10);
            overflow: hidden;
        }


        /* ================= CUSTOMER ================= */

        .customer-section {
            background: #f0fdfa;
            padding: 20px 30px;
            border-bottom: 1px solid #ccfbf1;
        }

        .customer-label {
            color: #64748b;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .customer-name {
            color: #134e4a;
            font-size: 18px;
            font-weight: bold;
        }


        /* ================= PAYMENT CONTENT ================= */

        .payment-content {
            padding: 35px;
        }


        /* ================= ESEWA HEADER ================= */

        .esewa-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding: 18px;
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            border-radius: 10px;
        }

        .esewa-icon {
            width: 52px;
            height: 52px;
            background: #0f766e;
            color: white;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 25px;
            font-weight: bold;
        }

        .esewa-header h2 {
            margin: 0 0 4px;
            color: #134e4a;
            font-size: 20px;
        }

        .esewa-header p {
            margin: 0;
            color: #64748b;
            font-size: 13px;
        }


        /* ================= ORDER SUMMARY ================= */

        .summary-title {
            color: #134e4a;
            font-size: 22px;
            margin: 0 0 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e2e8f0;
            gap: 20px;
        }

        .label {
            color: #64748b;
        }

        .value {
            color: #1e293b;
            font-weight: bold;
            text-align: right;
        }


        /* ================= TOTAL ================= */

        .amount-row {
            margin-top: 5px;
            padding: 22px 0;
            border-top: 2px solid #ccfbf1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .amount-label {
            color: #134e4a;
            font-size: 17px;
            font-weight: bold;
        }

        .amount {
            color: #f97316;
            font-size: 28px;
            font-weight: bold;
        }


        /* ================= SECURITY ================= */

        .secure-info {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #ffedd5;
            border: 1px solid #fed7aa;
            padding: 15px;
            border-radius: 9px;
            margin: 5px 0 25px;
        }

        .secure-icon {
            font-size: 24px;
        }

        .secure-text strong {
            display: block;
            color: #9a3412;
            margin-bottom: 3px;
        }

        .secure-text span {
            color: #c2410c;
            font-size: 13px;
        }


        /* ================= PAY BUTTON ================= */

        .pay-button {
            width: 100%;
            padding: 15px;
            background: #f97316;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .pay-button:hover {
            background: #ea580c;
            transform: translateY(-1px);
        }


        /* ================= BACK LINKS ================= */

        .links {
            text-align: center;
            margin-top: 22px;
        }

        .links a {
            color: #0f766e;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .separator {
            margin: 0 10px;
            color: #cbd5e1;
        }


        /* ================= FOOTER ================= */

        footer {
            background: #123331;
            color: #cbd5e1;
            text-align: center;
            padding: 25px;
            font-size: 14px;
        }

        footer strong {
            color: #5eead4;
        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 700px) {

            .navbar {
                padding: 18px 20px;
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }

            .main {
                width: 92%;
                padding: 40px 0;
            }

            .payment-content {
                padding: 25px 22px;
            }

            .heading h1 {
                font-size: 32px;
            }

            .summary-row,
            .amount-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 7px;
            }

            .value {
                text-align: left;
            }

            .amount {
                font-size: 25px;
            }

        }

    </style>

</head>


<body>


<!-- ================= NAVIGATION ================= -->

<div class="navbar">

    <div class="logo">
        Reliance <span>Digital</span>
    </div>

    <div class="nav-links">

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="products.php">
            Products
        </a>

        <a href="transaction.php">
            Transactions
        </a>

        <a href="logout.php" class="logout">
            Logout
        </a>

    </div>

</div>


<!-- ================= MAIN ================= -->

<div class="main">


    <!-- Heading -->

    <div class="heading">

        <div class="small-title">
            💳 SECURE CHECKOUT
        </div>

        <h1>
            Make Your <span>Payment</span>
        </h1>

        <p>
            Review your order and continue securely with eSewa.
        </p>

    </div>


    <!-- Payment Card -->

    <div class="payment-card">


        <!-- Customer -->

        <div class="customer-section">

            <div class="customer-label">
                CUSTOMER
            </div>

            <div class="customer-name">

                <?php
                echo htmlspecialchars($_SESSION["name"]);
                ?>

            </div>

        </div>


        <div class="payment-content">


            <!-- eSewa -->

            <div class="esewa-header">

                <div class="esewa-icon">
                    e
                </div>

                <div>

                    <h2>
                        eSewa Payment
                    </h2>

                    <p>
                        Pay securely using eSewa
                    </p>

                </div>

            </div>


            <!-- Order Summary -->

            <h2 class="summary-title">
                Order Summary
            </h2>


            <div class="summary-row">

                <span class="label">
                    Product
                </span>

                <span class="value">

                    <?php
                    echo htmlspecialchars($product["name"]);
                    ?>

                </span>

            </div>


            <div class="summary-row">

                <span class="label">
                    Payment Method
                </span>

                <span class="value">
                    eSewa
                </span>

            </div>


            <div class="summary-row">

                <span class="label">
                    Amount
                </span>

                <span class="value">

                    NPR
                    <?php
                    echo number_format(
                        (float)$product["price"],
                        2
                    );
                    ?>

                </span>

            </div>


            <!-- Total -->

            <div class="amount-row">

                <span class="amount-label">
                    Total Amount
                </span>

                <span class="amount">

                    NPR
                    <?php
                    echo number_format(
                        (float)$product["price"],
                        2
                    );
                    ?>

                </span>

            </div>


            <!-- Security -->

            <div class="secure-info">

                <div class="secure-icon">
                    🔒
                </div>

                <div class="secure-text">

                    <strong>
                        Secure Payment
                    </strong>

                    <span>
                        Your payment information is processed securely.
                    </span>

                </div>

            </div>


            <!-- Payment Form -->

            <form
                action="esewa_payment.php"
                method="POST"
            >

                <input
                    type="hidden"
                    name="product_id"
                    value="<?php echo $product["id"]; ?>"
                >

                <button
                    type="submit"
                    class="pay-button"
                >
                    Pay with eSewa →
                </button>

            </form>


            <!-- Links -->

            <div class="links">

                <a href="products.php">
                    ← Back to Products
                </a>

                <span class="separator">
                    |
                </span>

                <a href="dashboard.php">
                    Dashboard
                </a>

            </div>


        </div>

    </div>

</div>


<!-- ================= FOOTER ================= -->

<footer>

    © 2026
    <strong>Reliance Digital</strong>.
    Secure shopping and payments made simple.

</footer>


</body>

</html>