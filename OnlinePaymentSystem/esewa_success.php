<?php

session_start();

include "db.php";
include "esewa_config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["data"])) {
    echo "<h2>Payment response not received.</h2>";
    exit();
}

$data = $_GET["data"];

$decoded_data = base64_decode($data);

if ($decoded_data === false) {
    echo "<h2>Invalid payment response.</h2>";
    exit();
}

$response = json_decode($decoded_data, true);

if (!$response) {
    echo "<h2>Invalid payment response.</h2>";
    exit();
}

$transaction_uuid = $response["transaction_uuid"] ?? "";
$product_code = $response["product_code"] ?? "";
$total_amount = $response["total_amount"] ?? "";

if (
    empty($transaction_uuid) ||
    empty($product_code) ||
    empty($total_amount)
) {
    echo "<h2>Incomplete payment response.</h2>";
    exit();
}

if ($product_code !== $esewa_product_code) {
    echo "<h2>Invalid product code.</h2>";
    exit();
}


/* ================================
   VERIFY PAYMENT WITH eSEWA
================================ */

$verify_url =
    "https://rc-epay.esewa.com.np/api/epay/transaction/status/";

$verify_url .= "?product_code="
    . urlencode($product_code);

$verify_url .= "&total_amount="
    . urlencode($total_amount);

$verify_url .= "&transaction_uuid="
    . urlencode($transaction_uuid);


$ch = curl_init($verify_url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

$response_data = curl_exec($ch);

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);


if ($response_data === false || $http_code != 200) {

    echo "<h2>Payment verification failed.</h2>";

    echo "<p>Please do not try to make the payment again immediately.</p>";

    exit();
}


$verification = json_decode($response_data, true);

if (!$verification) {

    echo "<h2>Invalid verification response.</h2>";

    exit();
}


/* ================================
   PAYMENT COMPLETED
================================ */

if (
    isset($verification["status"]) &&
    $verification["status"] === "COMPLETE"
) {

    /* Check whether transaction is already recorded */

    $check = $conn->prepare(
        "SELECT id FROM transactions
         WHERE transaction_id = ?"
    );

    $check->bind_param(
        "s",
        $transaction_uuid
    );

    $check->execute();

    $check_result = $check->get_result();


    /* Insert transaction only once */

    if ($check_result->num_rows == 0) {

        $user_id = $_SESSION["user_id"];

        $amount = $total_amount;

        $payment_method = "eSewa";

        $status = "Success";


        $stmt = $conn->prepare(
            "INSERT INTO transactions
            (
                user_id,
                amount,
                payment_method,
                transaction_id,
                status
            )
            VALUES (?, ?, ?, ?, ?)"
        );


        $stmt->bind_param(
            "idsss",
            $user_id,
            $amount,
            $payment_method,
            $transaction_uuid,
            $status
        );


        $stmt->execute();
    }

} else {

    echo "<h2>Payment was not completed.</h2>";

    echo "<p>eSewa status: "
        . htmlspecialchars(
            $verification["status"] ?? "Unknown"
        )
        . "</p>";

    echo '<br><a href="products.php">Back to Products</a>';

    exit();
}


/* ================================
   SUCCESS PAGE INFORMATION
================================ */

$payment_date = date("d M Y");

$payment_time = date("h:i A");

$customer_name = "Reliance Digital";

?>

<!DOCTYPE html>

<html>

<head>

    <title>Payment Successful</title>

    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family: Arial, sans-serif;

            background: #f3f5f4;

            color: #222;

        }


        .page {

            min-height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 35px 15px;

        }


        .success-card {

            width: 100%;

            max-width: 430px;

            background: white;

            border-radius: 18px;

            padding: 32px 30px 28px;

            box-shadow:
                0 8px 30px rgba(0, 0, 0, 0.10);

            text-align: center;

        }


        /* PAYMENT SUCCESSFUL */

        .success-title {

            margin: 0;

            font-size: 24px;

            font-weight: 600;

            color: #222;

        }


        /* GREEN TICK */

        .check-circle {

            width: 82px;

            height: 82px;

            margin: 25px auto 18px;

            border-radius: 50%;

            background: #e6f7ec;

            display: flex;

            align-items: center;

            justify-content: center;

        }


        .check {

            width: 52px;

            height: 52px;

            border-radius: 50%;

            background: #35a853;

            color: white;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 30px;

            font-weight: bold;

        }


        /* AMOUNT */

        .amount {

            margin-top: 8px;

            font-size: 30px;

            font-weight: 700;

            color: #111827;

        }


        .currency {

            font-size: 18px;

            margin-right: 5px;

            font-weight: 600;

        }


        /* FUND TRANSFERRED */

        .fund-section {

            margin-top: 27px;

            padding: 19px 0;

            border-top: 1px solid #eeeeee;

            border-bottom: 1px solid #eeeeee;

        }


        .fund-label {

            font-size: 14px;

            color: #777;

            margin-bottom: 8px;

        }


        .fund-name {

            font-size: 16px;

            font-weight: 600;

            color: #222;

        }


        /* DATE AND TIME */

        .date-time {

            margin-top: 20px;

            font-size: 14px;

            color: #666;

            line-height: 1.7;

        }


        /* VIEW DETAILS */

        .details {

            margin-top: 24px;

            border-top: 1px solid #eeeeee;

        }


        .details-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            padding: 18px 0;

        }


        .details-title {

            font-size: 15px;

            font-weight: 600;

            color: #222;

        }


        .arrow {

            font-size: 21px;

            color: #777;

        }


        /* TRANSACTION CODE */

        .transaction-box {

            background: #f7f9f8;

            border-radius: 9px;

            padding: 15px;

            text-align: left;

            margin-bottom: 15px;

        }


        .transaction-label {

            font-size: 12px;

            color: #777;

            margin-bottom: 7px;

        }


        .transaction-code {

            font-size: 13px;

            font-weight: 600;

            color: #222;

            word-break: break-all;

            line-height: 1.5;

        }


        /* PAYMENT INFORMATION */

        .payment-info { 
            margin-bottom: 23px; 
        }

        .expense-category { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 16px; 
            background: #f7f9f8; 
            border-radius: 9px; 
            text-align: left; 
        } 

        .expense-category strong { 
            font-size: 15px; 
            color: #222; 
        } 

        .expense-category p { 
            margin: 5px 0 0; 
            font-size: 12px; 
            color: #777; 
        }

        .expense-arrow { 
            font-size: 22px; 
            color: #777; }


        /* DONE BUTTON */

        .done-button {

            display: block;

            width: 100%;

            padding: 14px;

            background: #35a853;

            color: white;

            text-decoration: none;

            border-radius: 8px;

            font-size: 16px;

            font-weight: 600;

            transition: 0.2s;

        }


        .done-button:hover {

            background: #2d9148;

        }


        /* SMALL FOOTNOTE */

        .verified-text {

            margin-top: 17px;

            font-size: 12px;

            color: #999;

        }


        /* MOBILE */

        @media (max-width: 500px) {

            .page {

                padding: 20px 12px;

            }


            .success-card {

                padding: 28px 22px 25px;

            }


            .success-title {

                font-size: 22px;

            }


            .amount {

                font-size: 28px;

            }

        }

    </style>

</head>


<body>


<div class="page">


    <div class="success-card">


        <!-- PAYMENT SUCCESSFUL -->

        <h1 class="success-title">

            Payment Successful

        </h1>


        <!-- GREEN TICK -->

        <div class="check-circle">

            <div class="check">

                ✓

            </div>

        </div>


        <!-- AMOUNT -->

        <div class="amount">

            <span class="currency">NPR</span>

            <?php

            echo htmlspecialchars(
                number_format(
                    (float)$total_amount,
                    2
                )
            );

            ?>

        </div>


        <!-- FUND TRANSFERRED -->

        <div class="fund-section">

            <div class="fund-label">

                Fund Transferred To

            </div>


            <div class="fund-name">

                <?php

                echo htmlspecialchars(
                    $customer_name
                );

                ?>

            </div>

        </div>


        <!-- DATE AND TIME -->

        <div class="date-time">

            <?php

            echo $payment_date;

            ?>

            <br>

            <?php

            echo $payment_time;

            ?>

        </div>


        <!-- VIEW DETAILS -->

        <div class="details">


            <div class="details-header">

                <span class="details-title">

                    View Details

                </span>


                <span class="arrow">

                    ›

                </span>

            </div>


            <!-- TRANSACTION CODE -->

            <div class="transaction-box">


                <div class="transaction-label">

                    Transaction Code

                </div>


                <div class="transaction-code">

                    <?php

                    echo htmlspecialchars(
                        $transaction_uuid
                    );

                    ?>

                </div>


            </div>


            <!-- PAYMENT INFORMATION -->

            <div class="payment-info">

    <div class="expense-category">
        <div>
            <strong>Add Expenses Category</strong>

            <p>
                This will help organize your expenses
            </p>
        </div>

        <span class="expense-arrow"></span>
    </div>

</div>


        </div>


        <!-- DONE -->

        <a
            href="dashboard.php"
            class="done-button"
        >

            Done

        </a>


        <div class="verified-text">

            Payment verified successfully

        </div>


    </div>


</div>


</body>

</html>