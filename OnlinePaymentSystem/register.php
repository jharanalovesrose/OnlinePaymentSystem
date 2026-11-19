<?php

include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check whether email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $message = "Email already registered.";

    } else {

        // Securely hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Every new user is a customer
        $role = "customer";

        $stmt = $conn->prepare(
            "INSERT INTO users (name, email, password, role)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssss",
            $name,
            $email,
            $hashed_password,
            $role
        );

        if ($stmt->execute()) {
            $message = "Registration successful! You can now login.";
        } else {
            $message = "Registration failed: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Register - Online Payment System</title>

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

        .register-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register-box {
            width: 100%;
            max-width: 450px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(15, 118, 110, 0.12);
        }

        .register-box h1 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 10px;
            color: #134e4a;
        }

        .register-subtitle {
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

        .register-button {
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

        .register-button:hover {
            background: #115e59;
        }

        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 7px;
            background: #dcfce7;
            color: #166534;
            text-align: center;
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

    <div class="register-container">

        <div class="register-box">

            <h1>Create Account</h1>

            <p class="register-subtitle">
                Create an account to use our payment system.
            </p>

            <?php if ($message != ""): ?>

                <div class="message">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="form-group">

                    <label>Name:</label>

                    <input
                        type="text"
                        name="name"
                        required
                    >

                </div>

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

                </div>

                <button
                    type="submit"
                    class="register-button"
                >
                    Register
                </button>

            </form>

            <div class="links">

                <a href="login.php">
                    Already have an account? Login
                </a>

                <div class="home-link">

                    <a href="index.php">
                        ← Back to Home
                    </a>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
