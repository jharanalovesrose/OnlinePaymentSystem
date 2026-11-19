<?php

session_start();
include "db.php";

$message = "";

if (!isset($_SESSION["reset_user_id"])) {
    header("Location: forgot_password.php");
    exit();
}

$user_id = $_SESSION["reset_user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $otp = trim($_POST["otp"]);

    if (empty($otp)) {

        $message = "Please enter the verification code.";

    } elseif (!preg_match("/^[0-9]{6}$/", $otp)) {

        $message = "Please enter a valid 6-digit code.";

    } else {

        $sql = "SELECT reset_code, reset_expiry
                FROM users
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if ($user["reset_code"] != $otp) {

                $message = "Incorrect verification code.";

            } elseif (
                empty($user["reset_expiry"]) ||
                strtotime($user["reset_expiry"]) < time()
            ) {

                $message = "This verification code has expired.";

            } else {

                // OTP is correct and not expired
                $_SESSION["otp_verified"] = true;

                header("Location: reset_password.php");
                exit();

            }

        } else {

            $message = "Something went wrong. Please try again.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Verify OTP - Reliance Digital</title>

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
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .email {
            text-align: center;
            color: #0f766e;
            font-weight: bold;
            margin-bottom: 25px;
            word-break: break-word;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #334155;
            text-align: center;
        }

        .otp-input {
            width: 100%;
            padding: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 22px;
            text-align: center;
            letter-spacing: 6px;
        }

        .otp-input:focus {
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

        .demo-code {
            margin-top: 20px;
            padding: 12px;
            border-radius: 7px;
            background: #f0fdfa;
            color: #134e4a;
            text-align: center;
            font-size: 14px;
        }

        .demo-code strong {
            font-size: 18px;
            letter-spacing: 3px;
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

            <h1>Verify Code</h1>

            <p class="subtitle">
                Enter the 6-digit verification code to continue.
            </p>

            <div class="email">
                <?php echo htmlspecialchars($_SESSION["reset_email"]); ?>
            </div>

            <?php if ($message != ""): ?>

                <div class="message">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="form-group">

                    <label>Verification Code</label>

                    <input
                        type="text"
                        name="otp"
                        class="otp-input"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        inputmode="numeric"
                        placeholder="000000"
                        required
                    >

                </div>

                <button type="submit">
                    Verify Code
                </button>

            </form>

            <?php if (isset($_SESSION["demo_otp"])): ?>

                <div class="demo-code">

                    Demo Verification Code:
                    <br><br>

                    <strong>
                        <?php echo htmlspecialchars($_SESSION["demo_otp"]); ?>
                    </strong>

                </div>

            <?php endif; ?>

            <div class="links">

                <a href="forgot_password.php">
                    Request New Code
                </a>

                <br><br>

                <a href="login.php">
                    Back to Login
                </a>

            </div>

        </div>

    </div>

</body>

</html>