<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include PHPMailer files
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection parameters
$servername = "localhost";
$username = "wp2024";
$password = "@webprogramming"; // Update with your MySQL password
$dbname = "mysister";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a 4-digit OTP code
function generateOtp() {
    return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    // Password validation: Check if password is at least 8 characters long
    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters long!";
        exit();
    }

    // Check if passwords match
    if ($password !== $repeat_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind SQL statement to insert into `customer` table
    $stmt = $conn->prepare("INSERT INTO customer (fname, lname, cnum, address, email, pass, verified) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $verified = 1; // Set verified to 1
    $stmt->bind_param("ssssssi", $first_name, $last_name, $contact_number, $address, $email, $hashed_password, $verified);

    // Execute the query
    if ($stmt->execute()) {
        // Prepare and bind SQL statement to insert into `users` table
        $user_type = 'customer'; // Assuming a default user type for new users
        $stmt = $conn->prepare("INSERT INTO users (email, pass, user_type) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $hashed_password, $user_type);
        $stmt->execute();

        // Generate a 4-digit OTP code
        $verification_code = generateOtp();

        // Prepare and execute SQL to insert OTP into `otp_codes` table
        $stmt = $conn->prepare("INSERT INTO otp_codes (email, code, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $email, $verification_code);
        $stmt->execute();

        // Send OTP email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'nrulizzh35@gmail.com'; // SMTP username
            $mail->Password = 'qaml lehv vntq cqur'; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('nrulizzh35@gmail.com', 'MyKakaks');
            $mail->addAddress($email); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Your account authentication Code';
            $mail->Body    = 'Your OTP code is: ' . $verification_code;
            $mail->AltBody = 'Your OTP code is: ' . $verification_code;

            $mail->send();

            // Redirect to OTP verification page with email as query string
            header("Location: otp-page.php?email=" . urlencode($email));
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Display the registration form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>MYKAKAKS Guest - Register</title>
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link href="styling.css" rel="stylesheet">
    </head>
    <body class="bg-gradient-primary">
        <div class="container">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-xl-6 d-flex align-items-center justify-content-center">
                            <img src="image/login.png" alt="Logo" class="img-fluid">
                        </div><br>
                        <div class="col-lg-6 d-flex align-items-center justify-content-center">
                            <div class="p-4 w-100">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4"><b>Create an Account!</b></h1>
                                    </div>
                                    <form id="signupForm" class="user" action="register.php" method="POST">
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="text" class="form-control form-control-user" id="firstName" name="first_name" placeholder="First Name" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-user" id="lastName" name="last_name" placeholder="Last Name" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" class="form-control form-control-user" id="contactNumber" name="contact_number" placeholder="Contact Number" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="address" name="address" placeholder="Address" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email Address" required>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password (atleast 8 characters)" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control form-control-user" id="repeatPassword" name="repeat_password" placeholder="Confirm Password" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Register Account
                                        </button>
                                        <hr>
                                    </form>
                                    <div class="text-center">
                                        <a class="small" href="login.php">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Client-side password validation -->
        <script>
            document.getElementById('signupForm').addEventListener('submit', function (event) {
                var password = document.getElementById('password').value;
                var repeatPassword = document.getElementById('repeatPassword').value;

                // Check if the password is at least 8 characters long
                if (password.length < 8) {
                    alert('Password must be at least 8 characters long.');
                    event.preventDefault(); // Prevent the form from being submitted
                    return;
                }

                // Check if passwords match
                if (password !== repeatPassword) {
                    alert('Passwords do not match!');
                    event.preventDefault();
                }
            });
        </script>
    </body>
    </html>
    <?php
}
?>
