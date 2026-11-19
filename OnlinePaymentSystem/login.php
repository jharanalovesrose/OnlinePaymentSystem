<?php

session_start();
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] == "admin") {
                header("Location: admin.php");
            } else {
                header("Location: dashboard.php");
            }

            exit();

        } else {
            $message = "Incorrect password.";
        }

    } else {
        $message = "User not found.";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Login - Reliance Digital</title>

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

        .login-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(15, 118, 110, 0.12);
        }

        .login-box h1 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 10px;
            color: #134e4a;
        }

        .login-subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #334155;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 15px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0f766e;
            box-shadow: 0 0 0 2px rgba(15, 118, 110, 0.1);
        }

        .login-button {
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

        .login-button:hover {
            background: #115e59;
        }

        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 7px;
            background: #fee2e2;
            color: #991b1b;
            text-align: center;
        }

        /* Forgot Password */

        .forgot-password {
            display: block;
            text-align: right;
            margin-top: 8px;
            color: #0f766e;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password:hover {
            text-decoration: underline;
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

        .home-link {
            margin-top: 15px;
        }

    </style>

</head>

<body>

    <div class="login-container">

        <div class="login-box">

            <h1>Login</h1>

            <p class="login-subtitle">
                Welcome back! Please login to continue.
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
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Password:</label>

                    <input
                        type="password"
                        name="password"
                        required
                    >

                    <a href="forgot_password.php" class="forgot-password">
                        Forgot Password?
                    </a>

                </div>

                <button
                    type="submit"
                    class="login-button"
                >
                    Login
                </button>

            </form>

            <div class="links">

                <a href="register.php">
                    Don't have an account? Register
                </a>

                <div class="home-link">

                    <a href="index.php">
                        Back to Home
                    </a>

                </div>

            </div>

        </div>

    </div>

</body>

</html>