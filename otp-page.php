<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Handle OTP verification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'verify') {
    $otp = $_POST['otp'];
    $email = $_POST['email'];

    // Check OTP in the database
    $stmt = $conn->prepare("SELECT email FROM otp_codes WHERE email = ? AND code = ? AND TIMESTAMPDIFF(MINUTE, created_at, NOW()) < 30");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // OTP is valid, delete it from the database
        $stmt = $conn->prepare("DELETE FROM otp_codes WHERE email = ? AND code = ?");
        $stmt->bind_param("ss", $email, $otp);
        $stmt->execute();
        
        // Redirect to login page
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $conn->close();
    exit();
}

// Handle Resend OTP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'resend') {
    $email = $_POST['email'];

    // Generate a new OTP
    $verification_code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

    // Update or insert new OTP
    $stmt = $conn->prepare("DELETE FROM otp_codes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $stmt = $conn->prepare("INSERT INTO otp_codes (email, code, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $email, $verification_code);
    $stmt->execute();

    // Send OTP email using PHPMailer
    require_once 'PHPMailer/src/PHPMailer.php';
    require_once 'PHPMailer/src/SMTP.php';
    require_once 'PHPMailer/src/Exception.php';

    // Initialize PHPMailer
    $mail = new PHPMailer\PHPMailer\PHPMailer();

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
        $mail->Body    = 'Your new OTP code is: ' . $verification_code;
        $mail->AltBody = 'Your new OTP code is: ' . $verification_code;

        $mail->send();
        echo 'resent';
    } catch (Exception $e) {
        echo 'error';
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body {
            background-color: #00204a;
            color: #ffffff;
            font-family: Arial, sans-serif;
        }
		
		p {
			color: #9D9D9D;
		}

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background-color: #ffffff;
            color: #00204a;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .otp-group {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .otp-box {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            border-radius: 5px;
            border: 2px solid #00204a;
            margin: 0 5px;
            background-color: #f8f9fa;
            color: #00204a;
        }

        .button-container {
            text-align: center;
        }

        .button-container button {
            background-color: #00204a;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .button-container button:hover {
            background-color: #001a3a;
        }

        .options {
            margin-top: 20px;
        }

        .options button {
            background-color: #ffffff;
			color: #00204a;
			border: none;
			padding: 10px 20px;
			font-size: 16px;
			border-radius: 5px;
			cursor: pointer; 
			transition: background-color 0.3s;
        }

        .options button:hover {
            background-color: #f8f9fa;
        }
		
		.options button:focus {
			outline: none;
			box-shadow: none; 
		}
    </style>
    <script>
        function verifyOtp() {
            const otp = Array.from(document.querySelectorAll('.otp-box')).map(input => input.value).join('');
            const email = new URLSearchParams(window.location.search).get('email');

            if (otp.length !== 4) {
                alert('Please enter the 4-digit OTP.');
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    if (xhr.responseText === 'success') {
                        alert('OTP verified');
                        window.location.href = 'login.php';
                    } else {
                        alert('Invalid OTP');
                    }
                } else {
                    alert('An error occurred');
                }
            };

            xhr.send('otp=' + encodeURIComponent(otp) + '&email=' + encodeURIComponent(email) + '&action=verify');
        }

        function resendOtp() {
            const email = new URLSearchParams(window.location.search).get('email');

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    if (xhr.responseText === 'resent') {
                        alert('New OTP sent to your email.');
                    } else {
                        alert('An error occurred while resending OTP.');
                    }
                } else {
                    alert('An error occurred');
                }
            };

            xhr.send('email=' + encodeURIComponent(email) + '&action=resend');
        }

        function changeEmail() {
            window.location.href = 'register.php';
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Verify Your OTP</h1>
            <form id="otpForm">
                <div class="otp-group">
                    <input type="text" class="otp-box" maxlength="1" pattern="\d" required>
                    <input type="text" class="otp-box" maxlength="1" pattern="\d" required>
                    <input type="text" class="otp-box" maxlength="1" pattern="\d" required>
                    <input type="text" class="otp-box" maxlength="1" pattern="\d" required>
                </div>
                <div class="button-container">
                    <button type="button" onclick="verifyOtp()">Verify OTP</button>
                </div>
				<p>Didn't receive the OTP yet?</p>
                <div class="options">
                    <button type="button" onclick="changeEmail()"><b>Change Email</b></button> or
                    <button type="button" onclick="resendOtp()"><b>Resend OTP</b></button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
