<?php
session_start();
include "db.php";

/* Get products from database */
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reliance Digital - Online Payment System</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0fdfa;
            color: #1e293b;
            line-height: 1.6;
        }


        /* ================= NAVBAR ================= */

        .navbar {
            background: white;
            height: 74px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 3px 15px rgba(15, 118, 110, 0.12);
        }

        .nav-container {
            width: 92%;
            max-width: 1200px;
            margin: auto;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            text-decoration: none;
            color: #134e4a;
            font-size: 25px;
            font-weight: bold;
        }

        .logo span {
            color: #f97316;
        }

        .nav-links {
            display: flex;
            gap: 28px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #475569;
            font-size: 15px;
            font-weight: 600;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #f97316;
        }

        .nav-buttons {
            display: flex;
            gap: 10px;
        }

        .login-btn {
            text-decoration: none;
            color: #0f766e;
            border: 1px solid #0f766e;
            padding: 9px 19px;
            border-radius: 7px;
            font-weight: bold;
            transition: 0.3s;
        }

        .login-btn:hover {
            background: #f0fdfa;
        }

        .register-btn {
            text-decoration: none;
            color: white;
            background: #0f766e;
            padding: 9px 19px;
            border-radius: 7px;
            font-weight: bold;
            transition: 0.3s;
        }

        .register-btn:hover {
            background: #115e59;
        }


        /* ================= HERO ================= */

        .hero {
            padding: 85px 20px;
            background:
                radial-gradient(circle at 85% 20%, #fed7aa 0, transparent 22%),
                linear-gradient(135deg, #ccfbf1, #ffffff 65%);
        }

        .hero-container {
            width: 92%;
            max-width: 1200px;
            margin: auto;

            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 60px;
            align-items: center;
        }

        .hero-badge {
            display: inline-block;
            background: #ffedd5;
            color: #ea580c;
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .hero-content h1 {
            font-size: 52px;
            line-height: 1.12;
            color: #134e4a;
            margin-bottom: 20px;
        }

        .hero-content h1 span {
            color: #f97316;
        }

        .hero-content p {
            color: #64748b;
            font-size: 18px;
            line-height: 1.7;
            max-width: 600px;
            margin-bottom: 30px;
        }

        .hero-buttons {
            display: flex;
            gap: 14px;
        }

        .shop-btn {
            display: inline-block;
            text-decoration: none;
            background: #0f766e;
            color: white;
            padding: 13px 28px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .shop-btn:hover {
            background: #115e59;
            transform: translateY(-2px);
        }

        .learn-btn {
            display: inline-block;
            text-decoration: none;
            color: #ea580c;
            background: white;
            border: 1px solid #fb923c;
            padding: 13px 28px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .learn-btn:hover {
            background: #fff7ed;
        }


        /* ================= HERO CARD ================= */

        .hero-card {
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 18px 45px rgba(15, 118, 110, 0.15);
            border: 1px solid #ccfbf1;
        }

        .card-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .card-heading h3 {
            color: #134e4a;
            font-size: 21px;
        }

        .featured-badge {
            background: #ffedd5;
            color: #ea580c;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
        }

        .hero-item {
            display: flex;
            align-items: center;
            gap: 16px;
            background: #f8fafc;
            padding: 17px;
            border-radius: 11px;
            margin-bottom: 12px;
        }

        .hero-icon {
            width: 52px;
            height: 52px;
            border-radius: 11px;
            background: #ccfbf1;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 25px;
        }

        .hero-item h4 {
            color: #334155;
            margin-bottom: 3px;
        }

        .hero-item p {
            color: #64748b;
            font-size: 13px;
        }

        .secure-box {
            background: #ecfdf5;
            color: #15803d;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin-top: 18px;
        }


        /* ================= PRODUCTS ================= */

        .products-section {
            padding: 80px 20px;
            background: white;
        }

        .section-container {
            width: 92%;
            max-width: 1200px;
            margin: auto;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 45px;
        }

        .section-tag {
            display: inline-block;
            color: #ea580c;
            background: #fff7ed;
            padding: 6px 13px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .section-heading h2 {
            color: #134e4a;
            font-size: 34px;
            margin-bottom: 9px;
        }

        .section-heading p {
            color: #64748b;
            font-size: 16px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
        }

        .product-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 13px;
            overflow: hidden;
            transition: 0.3s;
        }

        .product-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 14px 30px rgba(15, 118, 110, 0.13);
            border-color: #99f6e4;
        }

        .product-image {
            height: 155px;
            background: linear-gradient(135deg, #f0fdfa, #fff7ed);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 58px;
            position: relative;
        }

        .product-label {
            position: absolute;
            top: 12px;
            left: 12px;

            background: #f97316;
            color: white;

            padding: 4px 8px;
            border-radius: 5px;

            font-size: 10px;
            font-weight: bold;
        }

        .product-info {
            padding: 20px;
        }

        .product-info h3 {
            color: #134e4a;
            font-size: 18px;
            margin-bottom: 8px;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-description {
            color: #64748b;
            font-size: 13px;
            height: 42px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .product-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .price {
            color: #ea580c;
            font-size: 17px;
            font-weight: bold;
        }

        .buy-btn {
            text-decoration: none;
            background: #0f766e;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            transition: 0.3s;
        }

        .buy-btn:hover {
            background: #f97316;
        }

        .view-all {
            text-align: center;
            margin-top: 35px;
        }

        .view-all a {
            text-decoration: none;
            color: #0f766e;
            border: 1px solid #0f766e;
            padding: 10px 22px;
            border-radius: 7px;
            font-weight: bold;
            transition: 0.3s;
        }

        .view-all a:hover {
            background: #0f766e;
            color: white;
        }

        .no-products {
            text-align: center;
            background: #f8fafc;
            padding: 40px;
            border-radius: 10px;
            color: #64748b;
        }


        /* ================= FEATURES ================= */

        .features-section {
            padding: 80px 20px;
            background: #f0fdfa;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
        }

        .feature-card {
            background: white;
            padding: 30px 22px;
            text-align: center;
            border-radius: 13px;
            border: 1px solid #dff5f2;
            transition: 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(15, 118, 110, 0.10);
        }

        .feature-icon {
            width: 55px;
            height: 55px;
            margin: 0 auto 18px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;
            background: #ffedd5;
            color: #ea580c;
            font-size: 24px;
        }

        .feature-card h3 {
            color: #134e4a;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .feature-card p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }


        /* ================= HOW IT WORKS ================= */

        .how-section {
            padding: 80px 20px;
            background: white;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .step {
            text-align: center;
            padding: 30px;
            position: relative;
        }

        .step-number {
            width: 50px;
            height: 50px;
            margin: 0 auto 18px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #0f766e;
            color: white;

            border-radius: 50%;
            font-size: 18px;
            font-weight: bold;

            box-shadow: 0 5px 15px rgba(15, 118, 110, 0.25);
        }

        .step:nth-child(2) .step-number {
            background: #f97316;
        }

        .step h3 {
            color: #134e4a;
            margin-bottom: 10px;
        }

        .step p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }


        /* ================= CTA ================= */

        .cta {
            padding: 75px 20px;
            background: linear-gradient(135deg, #134e4a, #0f766e);
            text-align: center;
            color: white;
        }

        .cta h2 {
            font-size: 35px;
            margin-bottom: 12px;
        }

        .cta p {
            color: #ccfbf1;
            font-size: 16px;
            margin-bottom: 28px;
        }

        .cta-btn {
            display: inline-block;
            text-decoration: none;
            background: #f97316;
            color: white;
            padding: 13px 30px;
            border-radius: 7px;
            font-weight: bold;
            transition: 0.3s;
        }

        .cta-btn:hover {
            background: #ea580c;
            transform: translateY(-2px);
        }


        /* ================= FOOTER ================= */

        footer {
            background: #123331;
            color: white;
            padding: 55px 20px 20px;
        }

        .footer-grid {
            width: 92%;
            max-width: 1200px;
            margin: auto;

            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
        }

        .footer-brand h2 {
            font-size: 23px;
            margin-bottom: 15px;
        }

        .footer-brand h2 span {
            color: #fb923c;
        }

        .footer-brand p {
            color: #a7cfca;
            font-size: 14px;
            max-width: 350px;
        }

        .footer-column h3 {
            font-size: 16px;
            margin-bottom: 18px;
        }

        .footer-column a {
            display: block;
            text-decoration: none;
            color: #a7cfca;
            font-size: 14px;
            margin-bottom: 10px;
            transition: 0.3s;
        }

        .footer-column a:hover {
            color: #fb923c;
        }

        .footer-bottom {
            width: 92%;
            max-width: 1200px;
            margin: 40px auto 0;

            padding-top: 20px;
            border-top: 1px solid #315652;

            text-align: center;
            color: #8fb5b0;
            font-size: 13px;
        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 950px) {

            .nav-links {
                display: none;
            }

            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-content p {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-buttons {
                justify-content: center;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .steps {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }


        @media (max-width: 600px) {

            .navbar {
                height: auto;
                padding: 14px 0;
            }

            .nav-container {
                flex-wrap: wrap;
                gap: 12px;
            }

            .logo {
                font-size: 21px;
            }

            .login-btn,
            .register-btn {
                padding: 7px 12px;
                font-size: 12px;
            }

            .hero {
                padding: 55px 10px;
            }

            .hero-content h1 {
                font-size: 38px;
            }

            .hero-content p {
                font-size: 16px;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .shop-btn,
            .learn-btn {
                width: 220px;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .section-heading h2 {
                font-size: 28px;
            }

            .cta h2 {
                font-size: 28px;
            }
        }

    </style>

</head>


<body>


<!-- ================= NAVBAR ================= -->

<nav class="navbar">

    <div class="nav-container">

        <a href="index.php" class="logo">
            Reliance <span>Digital</span>
        </a>


        <div class="nav-links">

            <a href="index.php">
                Home
            </a>

            <a href="#products">
                Products
            </a>

            <a href="#features">
                Features
            </a>

            <a href="#how-it-works">
                How It Works
            </a>

        </div>


        <div class="nav-buttons">

            <a href="login.php" class="login-btn">
                Login
            </a>

            <a href="register.php" class="register-btn">
                Register
            </a>

        </div>

    </div>

</nav>


<!-- ================= HERO ================= -->

<section class="hero">

    <div class="hero-container">


        <div class="hero-content">

            <div class="hero-badge">
                ✦ Smart Shopping & Secure Payments
            </div>

            <h1>
                Your Digital World,
                <span>Made Simple.</span>
            </h1>

            <p>
                Discover quality electronics and appliances at
                Reliance Digital. Browse our products freely,
                choose what you need, and enjoy a simple and
                secure online payment experience.
            </p>


            <div class="hero-buttons">

                <a href="#products" class="shop-btn">
                    Shop Now
                </a>

                <a href="#how-it-works" class="learn-btn">
                    How It Works
                </a>

            </div>

        </div>


        <!-- Hero Information Card -->

        <div class="hero-card">

            <div class="card-heading">

                <h3>
                    Shop With Confidence
                </h3>

                <span class="featured-badge">
                    FEATURED
                </span>

            </div>


            <div class="hero-item">

                <div class="hero-icon">
                    📱
                </div>

                <div>

                    <h4>
                        Quality Electronics
                    </h4>

                    <p>
                        Explore our range of digital products
                    </p>

                </div>

            </div>


            <div class="hero-item">

                <div class="hero-icon">
                    🛒
                </div>

                <div>

                    <h4>
                        Easy Shopping
                    </h4>

                    <p>
                        Browse products without logging in
                    </p>

                </div>

            </div>


            <div class="hero-item">

                <div class="hero-icon">
                    💳
                </div>

                <div>

                    <h4>
                        Secure Payments
                    </h4>

                    <p>
                        Simple and convenient online checkout
                    </p>

                </div>

            </div>


            <div class="secure-box">
                ✓ Your shopping experience matters to us
            </div>

        </div>

    </div>

</section>


<!-- ================= PRODUCTS ================= -->

<section class="products-section" id="products">

    <div class="section-container">


        <div class="section-heading">

            <div class="section-tag">
                OUR PRODUCTS
            </div>

            <h2>
                Featured Products
            </h2>

            <p>
                Explore our latest electronics and appliances.
                Browse freely without creating an account.
            </p>

        </div>


        <?php if (mysqli_num_rows($result) > 0): ?>

            <div class="products-grid">


                <?php while ($product = mysqli_fetch_assoc($result)): ?>

                    <div class="product-card">


                        <div class="product-image">

                            <span class="product-label">
                                AVAILABLE
                            </span>


                            <?php

                            $name = strtolower($product['name']);

                            if (
                                strpos($name, 'phone') !== false ||
                                strpos($name, 'mobile') !== false
                            ) {
                                echo "📱";

                            } elseif (
                                strpos($name, 'laptop') !== false ||
                                strpos($name, 'computer') !== false
                            ) {
                                echo "💻";

                            } elseif (
                                strpos($name, 'tv') !== false ||
                                strpos($name, 'television') !== false
                            ) {
                                echo "📺";

                            } elseif (
                                strpos($name, 'headphone') !== false ||
                                strpos($name, 'earphone') !== false
                            ) {
                                echo "🎧";

                            } elseif (
                                strpos($name, 'watch') !== false
                            ) {
                                echo "⌚";

                            } elseif (
                                strpos($name, 'camera') !== false
                            ) {
                                echo "📷";

                            } elseif (
                                strpos($name, 'speaker') !== false
                            ) {
                                echo "🔊";

                            } elseif (
                                strpos($name, 'fridge') !== false ||
                                strpos($name, 'refrigerator') !== false
                            ) {
                                echo "🧊";

                            } elseif (
                                strpos($name, 'washing') !== false
                            ) {
                                echo "🧺";

                            } else {
                                echo "🛍️";
                            }

                            ?>

                        </div>


                        <div class="product-info">


                            <h3>
                                <?php
                                echo htmlspecialchars($product['name']);
                                ?>
                            </h3>


                            <div class="product-description">

                                <?php
                                echo htmlspecialchars($product['description']);
                                ?>

                            </div>


                            <div class="product-bottom">


                                <div class="price">

                                    NPR
                                    <?php
                                    echo number_format(
                                        $product['price'],
                                        2
                                    );
                                    ?>

                                </div>


                                <a
                                    href="checkout.php?id=<?php echo $product['id']; ?>"
                                    class="buy-btn"
                                >
                                    Buy Now
                                </a>


                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>


            </div>


            <div class="view-all">

                <a href="#products">
                    View All Products
                </a>

            </div>


        <?php else: ?>


            <div class="no-products">

                <h3>
                    No Products Available
                </h3>

                <p>
                    Products will appear here once they are added.
                </p>

            </div>


        <?php endif; ?>


    </div>

</section>


<!-- ================= FEATURES ================= -->

<section class="features-section" id="features">

    <div class="section-container">


        <div class="section-heading">

            <div class="section-tag">
                WHY CHOOSE US
            </div>

            <h2>
                A Better Way to Shop
            </h2>

            <p>
                We make online shopping and payments simple,
                convenient and reliable.
            </p>

        </div>


        <div class="features-grid">


            <div class="feature-card">

                <div class="feature-icon">
                    🔒
                </div>

                <h3>
                    Secure Payment
                </h3>

                <p>
                    Make online payments through a secure and
                    reliable payment system.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon">
                    💰
                </div>

                <h3>
                    Great Prices
                </h3>

                <p>
                    Discover quality electronics and appliances
                    at affordable prices.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon">
                    🛍️
                </div>

                <h3>
                    Easy Shopping
                </h3>

                <p>
                    Browse our products without needing to
                    log in first.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon">
                    🧾
                </div>

                <h3>
                    Track Transactions
                </h3>

                <p>
                    Logged-in customers can view their payment
                    history from their dashboard.
                </p>

            </div>


        </div>

    </div>

</section>


<!-- ================= HOW IT WORKS ================= -->

<section class="how-section" id="how-it-works">

    <div class="section-container">


        <div class="section-heading">

            <div class="section-tag">
                SIMPLE PROCESS
            </div>

            <h2>
                How It Works
            </h2>

            <p>
                Shopping and payment are just a few simple steps away.
            </p>

        </div>


        <div class="steps">


            <div class="step">

                <div class="step-number">
                    1
                </div>

                <h3>
                    Browse Products
                </h3>

                <p>
                    Explore our available electronics and appliances
                    directly from the website.
                </p>

            </div>


            <div class="step">

                <div class="step-number">
                    2
                </div>

                <h3>
                    Choose Your Product
                </h3>

                <p>
                    Select the product you want and click
                    the Buy Now button.
                </p>

            </div>


            <div class="step">

                <div class="step-number">
                    3
                </div>

                <h3>
                    Complete Payment
                </h3>

                <p>
                    Login when required, complete your payment,
                    and view your transaction details.
                </p>

            </div>


        </div>

    </div>

</section>


<!-- ================= CTA ================= -->

<section class="cta">

    <h2>
        Ready to Start Shopping?
    </h2>

    <p>
        Explore our products and enjoy a simple online shopping experience.
    </p>

    <a href="#products" class="cta-btn">
        Explore Products
    </a>

</section>


<!-- ================= FOOTER ================= -->

<footer>

    <div class="footer-grid">


        <div class="footer-brand">

            <h2>
                Reliance <span>Digital</span>
            </h2>

            <p>
                A modern online electronics shopping and payment
                system designed to provide customers with a simple,
                convenient and secure experience.
            </p>

        </div>


        <div class="footer-column">

            <h3>
                Quick Links
            </h3>

            <a href="index.php">
                Home
            </a>

            <a href="#products">
                Products
            </a>

            <a href="#features">
                Features
            </a>

            <a href="#how-it-works">
                How It Works
            </a>

        </div>


        <div class="footer-column">

            <h3>
                Account
            </h3>

            <a href="login.php">
                Login
            </a>

            <a href="register.php">
                Register
            </a>

        </div>


        <div class="footer-column">

            <h3>
                Payment
            </h3>

            <a href="#features">
                Secure Payment
            </a>

            <a href="#how-it-works">
                Easy Checkout
            </a>

            <a href="#features">
                Transaction History
            </a>

        </div>


    </div>


    <div class="footer-bottom">

        © 2026 Reliance Digital. All Rights Reserved.

    </div>

</footer>


</body>
</html>