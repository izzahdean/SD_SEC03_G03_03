<?php
// Include database connection parameters
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

// Initialize message variables
$errorMessage = '';
$successMessage = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the token and passwords from the form
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the token is valid and not expired
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid
        if ($newPassword === $confirmPassword) {
            // Update the password in the users table
            $row = $result->fetch_assoc();
            $email = $row['email'];

            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $stmt = $conn->prepare("UPDATE users SET pass = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);
            if ($stmt->execute()) {
                // Optionally, delete the token from password_resets table
                $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();

                $successMessage = 'Your password has been successfully reset!';
            } else {
                $errorMessage = 'Failed to update password. Please try again.';
            }
        } else {
            $errorMessage = 'Passwords do not match.';
        }
    } else {
        $errorMessage = 'Invalid or expired token.';
    }

    $stmt->close();
    $conn->close();
} else {
    // If the token is not set, redirect to the forgot password page
    if (!isset($_GET['token'])) {
        header("Location: forgot-password.php");
        exit();
    }
    $token = $_GET['token'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>MYKAKAKS - Reset Password</title>
    
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
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Reset Your Password</h1>
                                    </div>

                                    <!-- Display messages -->
                                    <?php if (!empty($errorMessage)): ?>
                                        <div class="alert alert-danger mt-3"><?php echo $errorMessage; ?></div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($successMessage)): ?>
                                        <div class="alert alert-success mt-3"><?php echo $successMessage; ?></div>
                                    <?php endif; ?>

                                    <form action="reset-password.php" method="POST" class="user">
                                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="new_password" placeholder="New Password" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="confirm_password" placeholder="Confirm Password" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Reset Password
                                        </button>
                                    </form>
                                    <hr>
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>
</html>
