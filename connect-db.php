<?php
$servername = "localhost";
$username = "webs402024";
$password = "webs402024";
$dbname = "mysister";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>