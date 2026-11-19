<?php

session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}


// Check if product ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_products.php");
    exit();
}

$product_id = intval($_GET['id']);


// Delete the product
$sql = "DELETE FROM products WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {

    // Product deleted successfully
    header("Location: admin_products.php");
    exit();

} else {

    echo "Error deleting product.";

}

$stmt->close();

?>
