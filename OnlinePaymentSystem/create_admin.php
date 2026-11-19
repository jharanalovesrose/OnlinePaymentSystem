<?php

include "db.php";

$name = "Admin";
$email = "admin@gmail.com";
$password = "admin123";
$role = "admin";

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "UPDATE users SET password = ?, role = ? WHERE email = ?"
);

$stmt->bind_param(
    "sss",
    $hashed_password,
    $role,
    $email
);

if ($stmt->execute()) {
    echo "Admin account updated successfully!";
} else {
    echo "Error: " . $conn->error;
}

?>