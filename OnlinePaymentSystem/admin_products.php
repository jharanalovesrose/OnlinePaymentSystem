<?php

session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
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

    <title>Manage Products - Reliance Digital</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f1f5f9;
            color: #1e293b;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            height: 100vh;
            background: #134e4a;
            padding: 25px 15px;
            color: white;
        }

        .logo {
            text-align: center;
            font-size: 23px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .logo span {
            color: #5eead4;
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
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #0f766e;
            color: white;
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

        .main {
            margin-left: 240px;
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        }

        .topbar h1 {
            color: #134e4a;
            font-size: 27px;
        }

        .topbar p {
            margin-top: 5px;
            color: #64748b;
        }

        .add-button {
            display: inline-block;
            background: #0f766e;
            color: white;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 7px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .add-button:hover {
            background: #115e59;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #134e4a;
            color: white;
            padding: 14px;
            text-align: left;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        tr:hover {
            background: #f8fafc;
        }

        .price {
            color: #0f766e;
            font-weight: bold;
        }

        .delete-button {
            background: #dc2626;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
        }

        .delete-button:hover {
            background: #b91c1c;
        }

        .no-products {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }

        @media (max-width: 700px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .logout {
                position: relative;
                left: auto;
                right: auto;
                bottom: auto;
                margin-top: 20px;
            }

            .main {
                margin-left: 0;
                padding: 15px;
            }

        }

    </style>

</head>

<body>


    <!-- Sidebar -->

    <div class="sidebar">

        <div class="logo">
            Reliance <span>Digital</span>
        </div>

        <div class="menu-title">
            Store Management
        </div>

        <a href="admin.php">
            🏠 Dashboard
        </a>

        <a href="add_product.php">
            ➕ Add Product
        </a>

        <a href="admin_products.php" class="active">
            📦 View Products
        </a>

        <a href="admin_transactions.php">
            💳 Transactions
        </a>

        <div class="logout">
            <a href="logout.php">
                Logout
            </a>
        </div>

    </div>


    <!-- Main -->

    <div class="main">

        <div class="topbar">

            <h1>
                Manage Products
            </h1>

            <p>
                View and manage all products in the store.
            </p>

        </div>


        <a href="add_product.php" class="add-button">
            ➕ Add New Product
        </a>


        <div class="table-container">

            <?php if ($result->num_rows > 0) { ?>

                <table>

                    <tr>

                        <th>ID</th>

                        <th>Product Name</th>

                        <th>Description</th>

                        <th>Price</th>

                        <th>Created At</th>

                        <th>Action</th>

                    </tr>


                    <?php while ($product = $result->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo $product['id']; ?>
                            </td>

                            <td>
                                <strong>
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </strong>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($product['description']); ?>
                            </td>

                            <td class="price">
                                NPR <?php echo htmlspecialchars($product['price']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($product['created_at']); ?>
                            </td>

                            <td>

                                <a
                                    href="delete_product.php?id=<?php echo $product['id']; ?>"
                                    class="delete-button"
                                    onclick="return confirm('Are you sure you want to delete this product?');"
                                >
                                    Delete
                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                </table>

            <?php } else { ?>

                <div class="no-products">

                    <h2>
                        No Products Found
                    </h2>

                    <p>
                        Add your first product using the Add Product button.
                    </p>

                </div>

            <?php } ?>

        </div>

    </div>

</body>

</html>