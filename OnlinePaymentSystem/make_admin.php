<?php

include "db.php";

$password = "admin123";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE users SET password = ?, role = 'admin' WHERE email = ?";

$stmt = $conn->prepare($sql);

$email = "admin@gmail.com";

$stmt->bind_param("ss", $hashed_password, $email);

if ($stmt->execute()) {
    echo "Admin password fixed successfully!";
} else {
    echo "Error: " . $conn->error;
}

?>