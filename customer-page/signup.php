<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$con = mysqli_connect("localhost", "wp2024", "@webprogramming", "mykakaks");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and escape user inputs
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $contact_number = mysqli_real_escape_string($con, $_POST['contact_number']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    // Check if passwords match
    if ($password === $repeat_password) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Prepare the SQL query
        $sql = "INSERT INTO users (first_name, last_name, contact_number, address, email, password, token, is_verified) 
                VALUES ('$first_name', '$last_name', '$contact_number', '$address', '$email', '$hashed_password', '$token', 0)";

        if (mysqli_query($con, $sql)) {
            // Send verification email
            $verification_link = "http://yourdomain.com/verify.php?token=" . $token;
            $subject = "Email Verification";
            $message = "Please click the following link to verify your email address: $verification_link";
            $headers = "From: nrulizzh35@gmail.com";

            // Check if mail function is successful
            if (mail($email, $subject, $message, $headers)) {
                // Email sent successfully
                echo "Registration successful! Please check your email to verify your account.";
                
                // Redirect to login.html after showing the message
                header("Refresh: 5; url=login.html");
                exit();
            } else {
                // Email sending failed
                echo "Email sending failed. Please try again later.";
            }
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Passwords do not match!";
    }

    // Close the database connection
    mysqli_close($con);
}
?>
