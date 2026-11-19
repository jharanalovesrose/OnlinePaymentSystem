<?php

$conn = new mysqli("localhost", "root", "", "online_payment");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

?>