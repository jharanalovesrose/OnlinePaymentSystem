<?php

session_start();
include "db.php";

/*
    Only allow access after OTP verification
*/
if (
    !isset($_SESSION["reset_user_id"]) ||
    !isset($_SESSION["otp_verified"]) ||
    $_SESSION["otp_verified"] !== true
) {
    header("Location: forgot_password.php");
    exit();
}

$user_id = $_SESSION["reset_user_id"];

$message = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($password) || empty($confirm_password)) {

    $message = "Please fill in both password fields.";

} elseif ($password !== $confirm_password) {

    $message = "Passwords do not match.";

} else {

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "UPDATE users
                SET password = ?,
                    reset_code = NULL,
                    reset_expiry = NULL
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $user_id);

        if ($stmt->execute()) {

            /*
                Remove password-reset session data
            */
            unset($_SESSION["reset_user_id"]);
            unset($_SESSION["reset_email"]);
            unset($_SESSION["demo_otp"]);
            unset($_SESSION["otp_verified"]);

            $success = "Password changed successfully!";

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

    <title>Reset Password - Reliance Digital</title>

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

        .success {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 7px;
            text-align: center;
            background: #dcfce7;
            color: #166534;
        }

        .links {
            text-align: center;
            margin-top: 25px;
        }

        .links a {
            color: #0f766e;
            text-decoration: none;
            font-weight: bold;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .password-info {
            margin-top: 8px;
            color: #64748b;
            font-size: 13px;
        }

    </style>

</head>

<body>

    <div class="container">

        <div class="box">

            <h1>Reset Password</h1>

            <p class="subtitle">
                Create a new password for your account.
            </p>

            <?php if ($message != ""): ?>

                <div class="message">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php endif; ?>


            <?php if ($success != ""): ?>

                <div class="success">
                    <?php echo htmlspecialchars($success); ?>
                </div>

                <div class="links">

                    <a href="login.php">
                        Go to Login
                    </a>

                </div>

            <?php else: ?>

                <form method="POST">

                    <div class="form-group">

                        <label>New Password:</label>

                        <input
                            type="password"
                            name="password"
                            placeholder="Enter new password"
                            required
                        >


                    </div>


                    <div class="form-group">

                        <label>Confirm Password:</label>

                        <input
                            type="password"
                            name="confirm_password"
                            placeholder="Confirm new password"
                            required
                        >

                    </div>


                    <button type="submit">
                        Reset Password
                    </button>

                </form>


                <div class="links">

                    <a href="login.php">
                        Back to Login
                    </a>

                </div>

            <?php endif; ?>

        </div>

    </div>

</body>

</html>