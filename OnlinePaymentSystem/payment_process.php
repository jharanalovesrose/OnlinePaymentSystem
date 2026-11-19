<?php

session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$amount = $_POST["amount"];
$payment_method = $_POST["payment_method"];

$transaction_id = "TXN" . time();
$status = "Success";

$sql = "INSERT INTO transactions
        (user_id, amount, payment_method, transaction_id, status)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "idsss",
    $user_id,
    $amount,
    $payment_method,
    $transaction_id,
    $status
);

if ($stmt->execute()) {

    echo "<h2>Payment Successful!</h2>";
    echo "<p>Amount: Rs. " . htmlspecialchars($amount) . "</p>";
    echo "<p>Payment Method: " . htmlspecialchars($payment_method) . "</p>";
    echo "<p>Transaction ID: " . htmlspecialchars($transaction_id) . "</p>";

    echo '<br><a href="transactions.php">View Transactions</a>';
    echo '<br><br><a href="dashboard.php">Back to Dashboard</a>';

} else {

    echo "Payment failed: " . $conn->error;

}

?>