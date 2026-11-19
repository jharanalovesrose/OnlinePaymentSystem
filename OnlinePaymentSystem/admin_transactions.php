<?php

session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}


// Get all transactions
$sql = "SELECT * FROM transactions ORDER BY id DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transactions - Reliance Digital</title>

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

        /* Sidebar */

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
            transition: 0.3s;
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

        /* Main */

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

        /* Table */

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

        .amount {
            color: #0f766e;
            font-weight: bold;
        }

        .status {
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            display: inline-block;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .failed {
            background: #fee2e2;
            color: #991b1b;
        }

        .pending {
            background: #fef3c7;
            color: #92400e;
        }

        .no-transactions {
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


        <a href="admin_products.php">
            📦 View Products
        </a>


        <a href="admin_transactions.php" class="active">
            💳 Transactions
        </a>


        <div class="logout">

            <a href="logout.php">
                Logout
            </a>

        </div>

    </div>



    <!-- Main Content -->

    <div class="main">


        <div class="topbar">

            <h1>
                Transactions
            </h1>

            <p>
                View all customer payment transactions.
            </p>

        </div>



        <div class="table-container">


            <?php if ($result->num_rows > 0) { ?>


                <table>


                    <tr>

                        <th>ID</th>

                        <th>User ID</th>

                        <th>Amount</th>

                        <th>Payment Method</th>

                        <th>Transaction ID</th>

                        <th>Status</th>

                        <th>Created At</th>

                    </tr>



                    <?php while ($transaction = $result->fetch_assoc()) { ?>


                        <tr>


                            <td>
                                <?php echo htmlspecialchars($transaction['id']); ?>
                            </td>


                            <td>
                                <?php echo htmlspecialchars($transaction['user_id']); ?>
                            </td>


                            <td class="amount">

                                NPR
                                <?php echo htmlspecialchars($transaction['amount']); ?>

                            </td>


                            <td>
                                <?php echo htmlspecialchars($transaction['payment_method']); ?>
                            </td>


                            <td>
                                <?php echo htmlspecialchars($transaction['transaction_id']); ?>
                            </td>


                            <td>


                                <?php

                                $status = strtolower($transaction['status']);

                                if ($status == "success") {

                                    echo '<span class="status success">Success</span>';

                                } elseif ($status == "failed") {

                                    echo '<span class="status failed">Failed</span>';

                                } else {

                                    echo '<span class="status pending">'
                                        . htmlspecialchars($transaction['status'])
                                        . '</span>';

                                }

                                ?>


                            </td>


                            <td>
                                <?php echo htmlspecialchars($transaction['created_at']); ?>
                            </td>


                        </tr>


                    <?php } ?>


                </table>


            <?php } else { ?>


                <div class="no-transactions">

                    <h2>
                        No Transactions Found
                    </h2>

                    <p>
                        There are currently no payment transactions.
                    </p>

                </div>


            <?php } ?>


        </div>


    </div>


</body>

</html>