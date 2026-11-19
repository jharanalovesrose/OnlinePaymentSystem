<?php

session_start();

include "db.php";
include "esewa_config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST["product_id"])) {
    header("Location: products.php");
    exit();
}

$product_id = $_POST["product_id"];

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "Product not found.";
    exit();
}

$product = $result->fetch_assoc();

$amount = $product["price"];

$tax_amount = 0;

$product_service_charge = 0;

$product_delivery_charge = 0;

$total_amount =
    $amount
    + $tax_amount
    + $product_service_charge
    + $product_delivery_charge;


// Generate unique transaction UUID

$transaction_uuid =
    date("YmdHis")
    . "-"
    . $_SESSION["user_id"]
    . "-"
    . $product_id;


// eSewa signature message

$message =
    "total_amount="
    . $total_amount
    . ",transaction_uuid="
    . $transaction_uuid
    . ",product_code="
    . $esewa_product_code;


// Generate HMAC SHA256 signature

$signature = base64_encode(
    hash_hmac(
        "sha256",
        $message,
        $esewa_secret_key,
        true
    )
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Redirecting to eSewa</title>

</head>

<body>

<h2>Redirecting to eSewa...</h2>

<p>
    Please wait while we connect you to the eSewa test payment page.
</p>

<form
    id="esewaForm"
    action="<?php echo $esewa_payment_url; ?>"
    method="POST"
>

    <input
        type="hidden"
        name="amount"
        value="<?php echo $amount; ?>"
    >

    <input
        type="hidden"
        name="tax_amount"
        value="<?php echo $tax_amount; ?>"
    >

    <input
        type="hidden"
        name="total_amount"
        value="<?php echo $total_amount; ?>"
    >

    <input
        type="hidden"
        name="transaction_uuid"
        value="<?php echo $transaction_uuid; ?>"
    >

    <input
        type="hidden"
        name="product_code"
        value="<?php echo $esewa_product_code; ?>"
    >

    <input
        type="hidden"
        name="product_service_charge"
        value="<?php echo $product_service_charge; ?>"
    >

    <input
        type="hidden"
        name="product_delivery_charge"
        value="<?php echo $product_delivery_charge; ?>"
    >

    <input
        type="hidden"
        name="success_url"
        value="http://localhost/OnlinePaymentSystem/esewa_success.php"
    >

    <input
        type="hidden"
        name="failure_url"
        value="http://localhost/OnlinePaymentSystem/esewa_failure.php"
    >

    <input
        type="hidden"
        name="signed_field_names"
        value="total_amount,transaction_uuid,product_code"
    >

    <input
        type="hidden"
        name="signature"
        value="<?php echo $signature; ?>"
    >

</form>

<script>

document.getElementById("esewaForm").submit();

</script>

</body>

</html>