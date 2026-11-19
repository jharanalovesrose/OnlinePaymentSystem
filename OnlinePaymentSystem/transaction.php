<?php

session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM transactions
        WHERE user_id = ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Transactions - Reliance Digital</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f0fdfa;
            color: #134e4a;
        }

        /* Navbar */

        .navbar {
            height: 70px;
            background: #134e4a;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 50px;
            color: white;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            gap: 10px;
        }

        .nav-links a {
            text-decoration: none;
            color: #ccfbf1;
            padding: 10px 18px;
            border-radius: 7px;
            transition: 0.3s;
        }

        .nav-links a:hover {
            background: #0f766e;
            color: white;
        }

        .nav-links a.active {
            background: #0f766e;
            color: white;
        }

        .nav-links .logout {
            background: #115e59;
            color: white;
        }

        .nav-links .logout:hover {
            background: #0f766e;
        }

        /* Main */

        .main {
            padding: 40px 50px;
        }

        .welcome {
            margin-bottom: 30px;
        }

        .welcome h1 {
            font-size: 30px;
            margin-bottom: 8px;
        }

        .welcome p {
            color: #64748b;
            font-size: 16px;
        }

        /* Transaction box */

        .transaction-box {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        .transaction-box h2 {
            margin-bottom: 20px;
            font-size: 22px;
        }

        /* Table */

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th {
            background: #134e4a;
            color: white;
            padding: 14px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            font-size: 14px;
        }

        tr:hover td {
            background: #f0fdfa;
        }

        /* Amount */

        .amount {
            font-weight: bold;
            color: #134e4a;
        }

        /* Status */

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
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

        /* Buttons */

        .actions {
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 7px;
            margin-right: 10px;
            font-weight: bold;
            transition: 0.3s;
        }

        .payment-btn {
            background: #0f766e;
            color: white;
        }

        .payment-btn:hover {
            background: #115e59;
        }

        .dashboard-btn {
            background: #e2e8f0;
            color: #334155;
        }

        .dashboard-btn:hover {
            background: #cbd5e1;
        }

        /* No transactions */

        .no-transactions {
            text-align: center;
            padding: 35px;
            color: #64748b;
            font-size: 16px;
        }

        /* Footer */

        .footer {
            text-align: center;
            padding: 30px;
            margin-top: 40px;
            color: #64748b;
            font-size: 14px;
        }

    </style>

</head>

<body>

    <!-- Navbar -->

    <div class="navbar">

        <div class="logo">
            Reliance Digital
        </div>

        <div class="nav-links">

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="products.php">
                Products
            </a>

            <a href="transaction.php" class="active">
                Transactions
            </a>

            <a href="logout.php" class="logout">
                Logout
            </a>

        </div>

    </div>


    <!-- Main Content -->

    <div class="main">

        <div class="welcome">

            <h1>
                My Transactions
            </h1>

            <p>
                Welcome,
                <?php echo htmlspecialchars($_SESSION["name"]); ?>!
            </p>

        </div>


        <div class="transaction-box">

            <h2>
                Transaction History
            </h2>

            <?php if ($result->num_rows > 0) { ?>

                <table>

                    <tr>
                        <th>ID</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Transaction ID</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>

                    <?php while ($row = $result->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo $row["id"]; ?>
                            </td>

                            <td class="amount">
                                NPR <?php echo htmlspecialchars($row["amount"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["payment_method"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["transaction_id"]); ?>
                            </td>

                            <td>

                                <?php
                                $status = strtolower($row["status"]);

                                if ($status == "success") {
                                    echo '<span class="status success">Success</span>';
                                } elseif ($status == "failed") {
                                    echo '<span class="status failed">Failed</span>';
                                } elseif ($status == "pending") {
                                    echo '<span class="status pending">Pending</span>';
                                } else {
                                    echo '<span class="status">' .
                                         htmlspecialchars($row["status"]) .
                                         '</span>';
                                }
                                ?>

                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["created_at"]); ?>
                            </td>

                        </tr>

                    <?php } ?>

                </table>

            <?php } else { ?>

                <div class="no-transactions">
                    You don't have any transactions yet.
                </div>

            <?php } ?>


            <div class="actions">

                <a href="payment.php" class="btn payment-btn">
                    Make Another Payment
                </a>

                <a href="dashboard.php" class="btn dashboard-btn">
                    Back to Dashboard
                </a>

            </div>

        </div>

    </div>


    <!-- Footer -->

    <div class="footer">
        Reliance Digital &copy; 2026
    </div>

</body>

</html>