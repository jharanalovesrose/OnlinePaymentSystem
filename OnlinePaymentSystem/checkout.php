<?php

session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: products.php");
    exit();
}

$product_id = $_GET["id"];

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

    <title>Checkout | Reliance Digital</title>

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
            max-width: 950px;
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


        /* ================= CHECKOUT CARD ================= */

        .checkout-card {
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
            font-size: 13px;
            margin-bottom: 5px;
        }

        .customer-name {
            color: #134e4a;
            font-size: 18px;
            font-weight: bold;
        }


        /* ================= PRODUCT SECTION ================= */

        .product-section {
            padding: 35px;
        }


        /* ================= PRODUCT IMAGE ================= */

        .product-image-box {
            width: 100%;
            height: 300px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            margin-bottom: 28px;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 20px;
        }

        .no-image {
            color: #94a3b8;
            font-size: 15px;
            font-weight: 600;
        }


        /* ================= PRODUCT NAME ================= */

        .product-name {
            color: #134e4a;
            font-size: 29px;
            margin: 0 0 12px;
        }

        .description {
            color: #64748b;
            line-height: 1.7;
            font-size: 15px;
            margin: 0 0 28px;
        }


        /* ================= PRICE ================= */

        .price-box {
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .price-label {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .price {
            color: #0f766e;
            font-size: 30px;
            font-weight: bold;
        }


        /* ================= PAYMENT INFO ================= */

        .secure-info {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #ffedd5;
            border: 1px solid #fed7aa;
            padding: 15px;
            border-radius: 9px;
            margin-bottom: 25px;
        }

        .secure-icon {
            font-size: 25px;
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


        /* ================= PAYMENT BUTTON ================= */

        .payment-button {
            width: 100%;
            padding: 14px;
            background: #f97316;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .payment-button:hover {
            background: #ea580c;
            transform: translateY(-1px);
        }


        /* ================= BACK LINK ================= */

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #0f766e;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
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

        @media (max-width: 800px) {

            .navbar {
                padding: 18px 25px;
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                gap: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }

            .heading h1 {
                font-size: 34px;
            }

        }


        @media (max-width: 550px) {

            .main {
                width: 92%;
                padding-top: 40px;
            }

            .product-section {
                padding: 25px;
            }

            .customer-section {
                padding: 18px 25px;
            }

            .product-name {
                font-size: 25px;
            }

            .price {
                font-size: 27px;
            }

            .nav-links {
                font-size: 14px;
            }

            .product-image-box {
                height: 240px;
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


    <!-- ================= HEADING ================= -->

    <div class="heading">

        <div class="small-title">
            🛒 CHECKOUT
        </div>

        <h1>
            Review Your <span>Purchase</span>
        </h1>

        <p>
            Check your product details before proceeding to payment.
        </p>

    </div>


    <!-- ================= CHECKOUT CARD ================= -->

    <div class="checkout-card">


        <!-- ================= CUSTOMER ================= -->

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


        <!-- ================= PRODUCT ================= -->

        <div class="product-section">


            <!-- Product Image -->

            <div class="product-image-box">

                <?php if (!empty($product["image"])) { ?>

                    <img
                        src="images/<?php echo htmlspecialchars($product["image"]); ?>"
                        alt="<?php echo htmlspecialchars($product["name"]); ?>"
                        class="product-image"
                    >

                <?php } else { ?>

                    <div class="no-image">
                        No Image Available
                    </div>

                <?php } ?>

            </div>


            <!-- Product Name -->

            <h2 class="product-name">

                <?php
                echo htmlspecialchars($product["name"]);
                ?>

            </h2>


            <!-- Description -->

            <p class="description">

                <?php
                echo htmlspecialchars($product["description"]);
                ?>

            </p>


            <!-- ================= PRICE ================= -->

            <div class="price-box">

                <div class="price-label">
                    TOTAL PRICE
                </div>

                <div class="price">

                    NPR
                    <?php
                    echo number_format(
                        (float)$product["price"],
                        2
                    );
                    ?>

                </div>

            </div>


            <!-- ================= SECURE PAYMENT ================= -->

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


            <!-- ================= PAYMENT FORM ================= -->

            <form action="payment.php" method="GET">

                <input
                    type="hidden"
                    name="product_id"
                    value="<?php echo $product["id"]; ?>"
                >

                <button
                    type="submit"
                    class="payment-button"
                >
                    Proceed to Payment →
                </button>

            </form>


            <!-- ================= BACK ================= -->

            <a
                href="products.php"
                class="back-link"
            >
                ← Back to Products
            </a>


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