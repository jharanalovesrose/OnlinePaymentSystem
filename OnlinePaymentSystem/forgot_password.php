```php
<?php

session_start();
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);

    if (empty($email)) {

        $message = "Please enter your email address.";

    } else {

        $sql = "SELECT id FROM users WHERE email = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            // Generate 6-digit OTP
            $otp = random_int(100000, 999999);

            // OTP will expire after 10 minutes
            $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

            // Save OTP and expiry in database
            $sql_update = "UPDATE users
                           SET reset_code = ?, reset_expiry = ?
                           WHERE id = ?";

            $update_stmt = $conn->prepare($sql_update);
            $update_stmt->bind_param(
                "ssi",
                $otp,
                $expiry,
                $user["id"]
            );

            if ($update_stmt->execute()) {

                $_SESSION["reset_user_id"] = $user["id"];
                $_SESSION["reset_email"] = $email;

                /*
                 * For our local project demo,
                 * we temporarily store the OTP in the session.
                 * Later we can connect email/PHPMailer.
                 */
                $_SESSION["demo_otp"] = $otp;

                header("Location: verify_otp.php");
                exit();

            } else {

                $message = "Something went wrong. Please try again.";

            }

            $update_stmt->close();

        } else {

            $message = "No account found with this email address.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Forgot Password - Reliance Digital</title>

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

        .container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .box {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(15, 118, 110, 0.12);
        }

        h1 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 10px;
            color: #134e4a;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #334155;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 15px;
        }

        input:focus {
            outline: none;
            border-color: #0f766e;
            box-shadow: 0 0 0 2px rgba(15, 118, 110, 0.1);
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 7px;
            background: #0f766e;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #115e59;
        }

        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 7px;
            text-align: center;
            background: #fee2e2;
            color: #991b1b;
        }

        .links {
            text-align: center;
            margin-top: 25px;
        }

        .links a {
            color: #0f766e;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

    </style>

</head>

<body>

    <div class="container">

        <div class="box">

            <h1>Forgot Password?</h1>

            <p class="subtitle">
                Enter your registered email address to receive
                a verification code.
            </p>

            <?php if ($message != ""): ?>

                <div class="message">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="form-group">

                    <label>Email:</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>

                <button type="submit">
                    Send Verification Code
                </button>

            </form>

            <div class="links">

                <a href="login.php">
                    Back to Login
                </a>

            </div>

        </div>

    </div>

</body>

</html>