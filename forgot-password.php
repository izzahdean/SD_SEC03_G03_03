<?php

date_default_timezone_set('Asia/Kuala_Lumpur');

require_once 'connect-db.php';

require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errorMessage = '';
$successMessage = '';

function generateToken() {
    return bin2hex(random_bytes(16));
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = generateToken();
        $expiry_time = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expires_at = ?");
        $stmt->bind_param("sssss", $email, $token, $expiry_time, $token, $expiry_time);
        $stmt->execute();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'nrulizzh35@gmail.com';
            $mail->Password = 'qaml lehv vntq cqur';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('nrulizzh35@gmail.com', 'MyKakaks');
            $mail->addAddress($email);

            $reset_link = "http://localhost/testing/reset-password.php?token=" . $token;
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Dear User,<br><br> We received a request to reset your password.<br> Kindly click <a href='" . $reset_link . "'>here</a> 
			to reset your password. The link will expire in 1 hour.";
            $mail->AltBody = "Copy and paste the following link to reset your password: " . $reset_link;

            $mail->send();
            $successMessage = 'Password reset email has been sent. Please check your inbox.';
        } catch (Exception $e) {
            $errorMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $errorMessage = "No account found with that email address.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>MYKAKAKS - Forgot Password</title>
    
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="styling.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-xl-6 d-flex align-items-center justify-content-center">
                                <img src="image/login.png" alt="Logo" class="img-fluid">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                                        <p class="mb-4">We get it, stuff happens. Just enter your email address below and we'll send you a link to reset your password!</p>
                                    </div>
                                    
                                    <?php if (!empty($errorMessage)): ?>
                                        <div class="alert alert-danger mt-3"><?php echo $errorMessage; ?></div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($successMessage)): ?>
                                        <div class="alert alert-success mt-3"><?php echo $successMessage; ?></div>
                                    <?php endif; ?>
                                    
                                    <form action="forgot-password.php" method="POST" class="user">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email" id="exampleInputEmail" aria-describedby="emailHelp" 
											placeholder="Enter Email Address..." required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Send Password Reset Link
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.html">Create an Account!</a>
                                    </div>
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
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

</body>
</html>
