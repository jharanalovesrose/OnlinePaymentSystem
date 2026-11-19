<?php

session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = $_POST["price"];

    if (empty($name) || empty($price)) {

        $message = "Please enter product name and price.";

    } else {

        // Check if an image was selected
        if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {

            $message = "Please select a product image.";

        } else {

            $image = $_FILES["image"];

            // Allowed image types
            $allowed_types = ["image/jpeg", "image/png", "image/webp"];

            if (!in_array($image["type"], $allowed_types)) {

                $message = "Only JPG, PNG, and WEBP images are allowed.";

            } else {

                // Create images folder if it does not exist
                $image_folder = "images/";

                if (!is_dir($image_folder)) {
                    mkdir($image_folder, 0777, true);
                }

                // Get original filename
                $original_name = basename($image["name"]);

                // Create unique filename
                $image_name = time() . "_" . $original_name;

                $image_path = $image_folder . $image_name;

                // Move uploaded image
                if (move_uploaded_file($image["tmp_name"], $image_path)) {

                    // Insert product into database
                    $sql = "INSERT INTO products (name, description, price, image)
                            VALUES (?, ?, ?, ?)";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param(
                        "ssds",
                        $name,
                        $description,
                        $price,
                        $image_name
                    );

                    if ($stmt->execute()) {

                        $message = "Product added successfully!";

                    } else {

                        $message = "Error adding product.";

                        // Remove uploaded image if database insert fails
                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }
                    }

                    $stmt->close();

                } else {

                    $message = "Error uploading image.";
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Product - Reliance Digital</title>

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

        /* Topbar */

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

        /* Form */

        .form-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            max-width: 800px;
        }

        .form-container h2 {
            color: #134e4a;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #64748b;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #334155;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 15px;
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #0f766e;
        }

        input[type="file"] {
            background: #f8fafc;
            cursor: pointer;
        }

        .image-note {
            color: #64748b;
            font-size: 13px;
            margin-top: -12px;
            margin-bottom: 20px;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #f97316;
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #ea580c;
        }

        .message {
            margin-bottom: 20px;
            padding: 12px;
            background: #ccfbf1;
            color: #134e4a;
            border-radius: 7px;
        }

        /* Mobile */

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

        <a href="add_product.php" class="active">
            ➕ Add Product
        </a>

        <a href="admin_products.php">
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


    <!-- Main Content -->

    <div class="main">

        <div class="topbar">

            <h1>
                Add Product
            </h1>

            <p>
                Add a new product to Reliance Digital.
            </p>

        </div>


        <div class="form-container">

            <h2>
                Product Information
            </h2>

            <p class="subtitle">
                Enter the details of the new product below.
            </p>


            <?php if (!empty($message)) { ?>

                <div class="message">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php } ?>


            <form method="POST" enctype="multipart/form-data">

                <label>
                    Product Name
                </label>

                <input
                    type="text"
                    name="name"
                    placeholder="Enter product name"
                    required
                >


                <label>
                    Description
                </label>

                <textarea
                    name="description"
                    placeholder="Enter product description"
                ></textarea>


                <label>
                    Price (NPR)
                </label>

                <input
                    type="number"
                    name="price"
                    placeholder="Enter price"
                    step="0.01"
                    min="0"
                    required
                >


                <label>
                    Product Image
                </label>

                <input
                    type="file"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp"
                    required
                >

                <p class="image-note">
                    Supported formats: JPG, PNG, WEBP
                </p>


                <button type="submit">
                    Add Product
                </button>

            </form>

        </div>

    </div>

</body>

</html>