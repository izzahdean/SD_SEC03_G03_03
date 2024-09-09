<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$con = mysqli_connect("localhost", "wp2024", "@webprogramming", "mykakaks");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($con, $_GET['token']);

    // Verify the token
    $sql = "SELECT * FROM users WHERE token='$token' AND is_verified=0";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Update user status to verified
        $update_sql = "UPDATE users SET is_verified=1, token=NULL WHERE token='$token'";
        if (mysqli_query($con, $update_sql)) {
            echo "Your email has been verified. You can now <a href='login.html'>login</a>.";
        } else {
            echo "Error updating record: " . mysqli_error($con);
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}

mysqli_close($con);
?>
