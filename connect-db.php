<?php
$servername = "localhost";
$username = "wp2024";
$password = "@webprogramming";
$dbname = "mysister";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>