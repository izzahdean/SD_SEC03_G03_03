<?php
$conn = new mysqli('localhost', 'wp2024', '@webprogramming', 'mysister');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
