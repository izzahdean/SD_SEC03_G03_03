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
$password = "@webprogramming"; 
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

    // Server-side password validation
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[@$!%*?&]/', $password)) {
        echo "Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character!";
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
            $mail->isSMTP(); 
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true; 
            $mail->Username = 'nrulizzh35@gmail.com'; 
            $mail->Password = 'qaml lehv vntq cqur'; 
            $mail->SMTPSecure = 'tls'; 
            $mail->Port = 587; 

            // Recipients
            $mail->setFrom('nrulizzh35@gmail.com', 'MyKakaks');
            $mail->addAddress($email); 

            // Content
            $mail->isHTML(true); 
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
    // Display the registration form with the password validation
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MYKAKAKS Guest - Sign Up</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="styling.css" rel="stylesheet">
    <style>
        .password-checklist {
            display: none;
            font-size: 14px;
			margin-right: 120px;
        }
        .password-checklist input[type="checkbox"] {
            margin-right: 10px;
        }
        .password-checklist li {
            margin-bottom: 5px;
        }
        .password-checklist li.valid {
            color: green;
        }
        .password-checklist li.invalid {
            color: red;
        }
    </style>
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
                                            <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control form-control-user" id="repeatPassword" name="repeat_password" placeholder="Confirm Password" required>
                                        </div>
                                    </div>
                                    <ul class="password-checklist">
                                        <li><input type="checkbox" id="length" disabled> At least 8 characters</li>
                                        <li><input type="checkbox" id="uppercase" disabled> At least one uppercase letter</li>
                                        <li><input type="checkbox" id="lowercase" disabled> At least one lowercase letter</li>
                                        <li><input type="checkbox" id="number" disabled> At least one number</li>
                                        <li><input type="checkbox" id="special" disabled> At least one special character</li>
                                    </ul>
                                    <button type="submit" class="btn btn-primary btn-user btn-block" id="submitBtn" disabled>
                                        Sign Up
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

    <script>
        const passwordInput = document.getElementById('password');
        const repeatPasswordInput = document.getElementById('repeatPassword');
        const checklist = {
            length: document.getElementById('length'),
            uppercase: document.getElementById('uppercase'),
            lowercase: document.getElementById('lowercase'),
            number: document.getElementById('number'),
            special: document.getElementById('special'),
        };
        const passwordChecklist = document.querySelector('.password-checklist');
        const submitBtn = document.getElementById('submitBtn');

        // Show checklist when user starts typing
        passwordInput.addEventListener('focus', function () {
            passwordChecklist.style.display = 'block';
        });

        passwordInput.addEventListener('input', function () {
            const password = passwordInput.value;

            // Check for each validation
            checklist.length.checked = password.length >= 8;
            checklist.uppercase.checked = /[A-Z]/.test(password);
            checklist.lowercase.checked = /[a-z]/.test(password);
            checklist.number.checked = /\d/.test(password);
            checklist.special.checked = /[@$!%*?&]/.test(password);

            // Enable the submit button if all requirements are met
            const allValid = Object.values(checklist).every(item => item.checked);
            submitBtn.disabled = !allValid;
        });

        // Optionally hide the checklist when the input loses focus
        passwordInput.addEventListener('blur', function () {
            if (passwordInput.value === '') {
                passwordChecklist.style.display = 'none';
            }
        });
    </script>
</body>
</html>
<?php
}
