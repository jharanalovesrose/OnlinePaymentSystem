<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Dashboard - Reliance Digital</title>

    <style>

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f0fdfa;
            color: #1e293b;
        }


        /* ================= NAVBAR ================= */

        .navbar {
            width: 100%;
            background: white;
            padding: 17px 50px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            box-shadow: 0 3px 15px rgba(15, 118, 110, 0.12);

            position: sticky;
            top: 0;
            z-index: 1000;
        }


        .logo {
            color: #134e4a;
            font-size: 24px;
            font-weight: bold;
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
            color: #475569;
            text-decoration: none;
            font-weight: bold;
            font-size: 15px;

            transition: 0.3s;
        }


        .nav-links a:hover {
            color: #f97316;
        }


        .nav-links .active {
            color: #0f766e;
        }


        .logout {
            background: #0f766e;
            color: white !important;

            padding: 9px 18px;
            border-radius: 7px;

            transition: 0.3s;
        }


        .logout:hover {
            background: #f97316;
        }


        /* ================= MAIN ================= */

        .main {
            width: 90%;
            max-width: 1200px;

            margin: auto;

            padding: 65px 0 80px;
        }


        /* ================= WELCOME ================= */

        .welcome {
            background: linear-gradient(
                135deg,
                #ccfbf1,
                #ffffff
            );

            border-radius: 16px;

            padding: 40px;

            margin-bottom: 45px;

            border: 1px solid #99f6e4;

            position: relative;

            overflow: hidden;
        }


        .welcome::after {
            content: "";
            position: absolute;

            width: 180px;
            height: 180px;

            background: #fed7aa;

            border-radius: 50%;

            right: -70px;
            top: -70px;

            opacity: 0.6;
        }


        .welcome h1 {
            margin: 0 0 10px;

            font-size: 38px;

            color: #134e4a;

            position: relative;
            z-index: 1;
        }


        .welcome h1 span {
            color: #f97316;
        }


        .welcome p {
            margin: 0;

            font-size: 17px;

            color: #64748b;

            position: relative;
            z-index: 1;
        }


        /* ================= SECTION TITLE ================= */

        .section-title {
            color: #134e4a;

            font-size: 27px;

            margin: 0 0 25px;
        }


        .section-title span {
            color: #f97316;
        }


        /* ================= DASHBOARD CARDS ================= */

        .dashboard-cards {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 22px;

        }


        .card {

            background: white;

            padding: 30px;

            border-radius: 13px;

            border: 1px solid #d8f3ef;

            box-shadow:
                0 6px 20px
                rgba(15, 118, 110, 0.08);

            transition: 0.3s;

        }


        .card:hover {

            transform: translateY(-6px);

            box-shadow:
                0 14px 30px
                rgba(15, 118, 110, 0.13);

            border-color: #99f6e4;

        }


        .card-icon {

            width: 55px;
            height: 55px;

            display: flex;

            align-items: center;
            justify-content: center;

            background: #ffedd5;

            color: #ea580c;

            border-radius: 12px;

            font-size: 25px;

            margin-bottom: 20px;

        }


        .card h2 {

            margin: 0 0 10px;

            color: #134e4a;

            font-size: 21px;

        }


        .card p {

            color: #64748b;

            min-height: 50px;

            line-height: 1.6;

            font-size: 14px;

            margin-bottom: 22px;

        }


        /* ================= BUTTON ================= */

        .btn {

            display: inline-block;

            padding: 10px 20px;

            background: #0f766e;

            color: white;

            text-decoration: none;

            border-radius: 7px;

            font-weight: bold;

            font-size: 14px;

            transition: 0.3s;

        }


        .btn:hover {

            background: #f97316;

            transform: translateY(-2px);

        }


        /* ================= ESCROW SECTION ================= */

        .escrow-section {

            margin-top: 55px;

            padding: 32px;

            background: white;

            border-radius: 13px;

            border-left: 5px solid #f97316;

            box-shadow:
                0 6px 20px
                rgba(15, 118, 110, 0.08);

        }


        .escrow-heading {

            display: flex;

            align-items: center;

            gap: 15px;

            margin-bottom: 12px;

        }


        .escrow-icon {

            width: 45px;
            height: 45px;

            background: #ffedd5;

            color: #ea580c;

            border-radius: 50%;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 20px;

        }


        .escrow-section h2 {

            margin: 0;

            color: #134e4a;

            font-size: 22px;

        }


        .escrow-section p {

            color: #64748b;

            margin: 0;

            line-height: 1.7;

            font-size: 14px;

        }


        /* ================= QUICK INFO ================= */

        .info-section {

            margin-top: 45px;

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 20px;

        }


        .info-box {

            background: #134e4a;

            color: white;

            padding: 25px;

            border-radius: 12px;

        }


        .info-box:nth-child(2) {

            background: #f97316;

        }


        .info-box:nth-child(3) {

            background: #0f766e;

        }


        .info-box h3 {

            margin: 0 0 8px;

            font-size: 17px;

        }


        .info-box p {

            margin: 0;

            font-size: 13px;

            line-height: 1.6;

            color: #ecfeff;

        }


        /* ================= FOOTER ================= */

        .footer {

            width: 100%;

            padding: 28px;

            text-align: center;

            background: #123331;

            color: #a7cfca;

            font-size: 14px;

        }


        .footer strong {

            color: #fb923c;

        }


        /* ================= MOBILE ================= */

        @media (max-width: 900px) {

            .navbar {

                padding: 17px 25px;

            }


            .dashboard-cards {

                grid-template-columns: 1fr 1fr;

            }


            .info-section {

                grid-template-columns: 1fr;

            }

        }


        @media (max-width: 650px) {

            .navbar {

                flex-direction: column;

                gap: 15px;

            }


            .nav-links {

                gap: 14px;

                flex-wrap: wrap;

                justify-content: center;

            }


            .main {

                width: 92%;

                padding: 40px 0 60px;

            }


            .welcome {

                padding: 30px 25px;

            }


            .welcome h1 {

                font-size: 29px;

            }


            .welcome p {

                font-size: 15px;

            }


            .dashboard-cards {

                grid-template-columns: 1fr;

            }


            .escrow-section {

                padding: 25px;

            }

        }

    </style>

</head>


<body>


<!-- ================= NAVBAR ================= -->

<div class="navbar">


    <div class="logo">

        Reliance <span>Digital</span>

    </div>


    <div class="nav-links">


        <a
            href="dashboard.php"
            class="active"
        >
            Dashboard
        </a>


        <a href="products.php">
            Products
        </a>


        <a href="transaction.php">
            Transactions
        </a>


        <a
            href="logout.php"
            class="logout"
        >
            Logout
        </a>


    </div>

</div>



<!-- ================= MAIN CONTENT ================= -->

<div class="main">


    <!-- Welcome -->

    <div class="welcome">

        <h1>

            Welcome,

            <span>
                <?php
                echo htmlspecialchars($_SESSION["name"]);
                ?>!
            </span>

        </h1>


        <p>

            Manage your shopping, payments and transactions
            from your Reliance Digital dashboard.

        </p>

    </div>



    <!-- Products -->

    <h2 class="section-title">

        What would you like to <span>do?</span>

    </h2>


    <div class="dashboard-cards">


        <!-- Products Card -->

        <div class="card">

            <div class="card-icon">
                🛍️
            </div>


            <h2>
                Browse Products
            </h2>


            <p>

                Explore available electronics and appliances
                and choose the product you want to purchase.

            </p>


            <a
                href="products.php"
                class="btn"
            >
                View Products
            </a>

        </div>



        <!-- Transaction Card -->

        <div class="card">

            <div class="card-icon">
                🧾
            </div>


            <h2>
                My Transactions
            </h2>


            <p>

                View your previous payments, transaction IDs,
                payment methods and payment status.

            </p>


            <a
                href="transaction.php"
                class="btn"
            >
                View Transactions
            </a>

        </div>



        <!-- Shopping Card -->

        <div class="card">

            <div class="card-icon">
                💳
            </div>


            <h2>
                Make a Payment
            </h2>


            <p>

                Select a product and continue to checkout
                to complete your online payment.

            </p>


            <a
                href="products.php"
                class="btn"
            >
                Shop Now
            </a>

        </div>


    </div>



    <!-- ================= ESCROW INFORMATION ================= -->

    <div class="escrow-section">


        <div class="escrow-heading">


            <div class="escrow-icon">
                🔐
            </div>


            <h2>
                How Escrow Protects Your Purchase
            </h2>


        </div>


        <p>

            Your payment is securely recorded in the system
            after successful eSewa verification. The transaction
            remains associated with your purchase while the
            payment process is completed.

        </p>


    </div>



    <!-- ================= QUICK INFORMATION ================= -->

    <div class="info-section">


        <div class="info-box">

            <h3>
                🔒 Secure Payments
            </h3>

            <p>

                Your payment information is handled through
                a secure online payment process.

            </p>

        </div>


        <div class="info-box">

            <h3>
                🛒 Easy Shopping
            </h3>

            <p>

                Browse products and choose what you need
                with a simple shopping experience.

            </p>

        </div>


        <div class="info-box">

            <h3>
                📋 Track Transactions
            </h3>

            <p>

                Keep track of your successful, pending
                and failed transactions.

            </p>

        </div>


    </div>


</div>



<!-- ================= FOOTER ================= -->

<div class="footer">

    Reliance <strong>Digital</strong>
    &copy; 2026

</div>


</body>

</html>