<?php

session_start();
include "db.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Get all products
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Products | Reliance Digital</title>

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
            background: #ea580c;
        }


        /* ================= MAIN ================= */

        .main {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            padding: 55px 0 70px;
        }


        /* ================= PAGE HEADING ================= */

        .heading {
            text-align: center;
            margin-bottom: 45px;
        }

        .heading .small-title {
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
            font-size: 40px;
            color: #134e4a;
        }

        .heading h1 span {
            color: #f97316;
        }

        .heading p {
            margin: 0;
            color: #64748b;
            font-size: 17px;
        }


        /* ================= PRODUCT GRID ================= */

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }


        /* ================= PRODUCT CARD ================= */

        .product-card {
            background: white;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #ccfbf1;
            box-shadow: 0 8px 25px rgba(15, 118, 110, 0.08);
            transition: 0.3s;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(15, 118, 110, 0.15);
        }


        /* ================= PRODUCT IMAGE ================= */

        .product-top {
            height: 230px;
            background: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid #e2e8f0;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 15px;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .no-image {
            color: #94a3b8;
            font-size: 14px;
            font-weight: 600;
        }


        /* ================= AVAILABLE BADGE ================= */

        .available {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #dcfce7;
            color: #15803d;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }


        /* ================= PRODUCT INFORMATION ================= */

        .product-info {
            padding: 22px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .product-info h2 {
            margin: 0 0 10px;
            color: #134e4a;
            font-size: 20px;
            line-height: 1.3;
        }

        .description {
            color: #64748b;
            line-height: 1.6;
            font-size: 14px;
            margin: 0;
            min-height: 50px;
        }


        /* ================= PRICE ================= */

        .price {
            margin: 20px 0;
            color: #0f766e;
            font-size: 21px;
            font-weight: bold;
        }


        /* ================= BUY BUTTON ================= */

        .buy-button {
            display: block;
            width: 100%;
            padding: 12px;
            background: #f97316;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 7px;
            font-weight: bold;
            transition: 0.3s;
            margin-top: auto;
        }

        .buy-button:hover {
            background: #ea580c;
        }


        /* ================= NO PRODUCTS ================= */

        .no-products {
            background: white;
            padding: 50px 30px;
            text-align: center;
            border-radius: 14px;
            border: 1px solid #ccfbf1;
            box-shadow: 0 8px 25px rgba(15, 118, 110, 0.08);
        }

        .no-products .icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .no-products h2 {
            color: #134e4a;
            margin-bottom: 10px;
        }

        .no-products p {
            color: #64748b;
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

        @media (max-width: 1050px) {

            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }

        }

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

            .product-grid {
                grid-template-columns: repeat(2, 1fr);
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

            .product-grid {
                grid-template-columns: 1fr;
            }

            .heading h1 {
                font-size: 30px;
            }

            .nav-links {
                font-size: 14px;
            }

            .logout {
                padding: 8px 14px;
            }

            .product-top {
                height: 250px;
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

        <a href="products.php" class="active">
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


<!-- ================= MAIN CONTENT ================= -->

<div class="main">


    <!-- ================= HEADING ================= -->

    <div class="heading">

        <div class="small-title">
            OUR COLLECTION
        </div>

        <h1>
            Explore Our <span>Products</span>
        </h1>

        <p>
            Choose your favorite product and complete your purchase securely.
        </p>

    </div>


    <!-- ================= PRODUCTS ================= -->

    <?php if ($result && $result->num_rows > 0) { ?>

        <div class="product-grid">

            <?php while ($product = $result->fetch_assoc()) { ?>

                <div class="product-card">


                    <!-- ================= PRODUCT IMAGE ================= -->

                    <div class="product-top">

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


                        <div class="available">
                            AVAILABLE
                        </div>

                    </div>


                    <!-- ================= PRODUCT INFORMATION ================= -->

                    <div class="product-info">

                        <h2>
                            <?php
                            echo htmlspecialchars($product["name"]);
                            ?>
                        </h2>


                        <p class="description">

                            <?php
                            echo htmlspecialchars($product["description"]);
                            ?>

                        </p>


                        <div class="price">

                            NPR
                            <?php
                            echo number_format(
                                (float)$product["price"],
                                2
                            );
                            ?>

                        </div>


                        <a
                            href="checkout.php?id=<?php echo $product["id"]; ?>"
                            class="buy-button"
                        >
                            Buy Now →
                        </a>

                    </div>

                </div>

            <?php } ?>

        </div>


    <?php } else { ?>


        <!-- ================= NO PRODUCTS ================= -->

        <div class="no-products">

            <div class="icon">
                🛍️
            </div>

            <h2>
                No Products Available
            </h2>

            <p>
                There are currently no products available.
                Please check again later.
            </p>

        </div>


    <?php } ?>


</div>


<!-- ================= FOOTER ================= -->

<footer>

    © 2026
    <strong>Reliance Digital</strong>.
    Secure shopping and payments made simple.

</footer>


</body>

</html>