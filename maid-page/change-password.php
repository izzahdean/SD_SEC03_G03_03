<?php
// Start session to use session variables if necessary
session_start();

// Database connection
$servername = "localhost";
$username = "wp2024";
$password = "@webprogramming"; // Update with your MySQL password
$dbname = "mysister";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get posted form data
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Assuming you're identifying the logged-in user with their email from the session
    $userEmail = $_SESSION['email']; // Ensure session has 'email'

    // Fetch the user's current hashed password from the database
    $stmt = $conn->prepare("SELECT pass FROM maid WHERE email = ?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $stmt->bind_result($hashedPasswordFromDB);
    $stmt->fetch();
    $stmt->close();

    // Validate old password
    if (!password_verify($oldPassword, $hashedPasswordFromDB)) {
        $errorMessage = "The old password you entered is incorrect.";
    } elseif ($newPassword !== $confirmPassword) {
        // Validate if new password and confirm password match
        $errorMessage = "New password and confirm password do not match.";
    } elseif (strlen($newPassword) < 6) {
        // Optional: Add a length check for the new password
        $errorMessage = "New password must be at least 6 characters long.";
    } else {
        // Hash the new password and update in the database
        $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("UPDATE maid SET pass = ? WHERE email = ?");
        $stmt->bind_param("ss", $newHashedPassword, $userEmail);
        
        if ($stmt->execute()) {
            $successMessage = "Your password has been successfully changed!";
        } else {
            $errorMessage = "There was a problem changing your password. Please try again.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #231a6f;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            background-color: #4e73df !important;
            color: white;
        }
        .navbar h1 {
            color: white;
        }
        .btn-primary {
            background-color: #231a6f;
            border-color: #4e73df;
        }
        .btn-primary:hover {
            background-color: #0f054c;
            border-color: #2653d4;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #f8f9fc;
            border-bottom: none;
            text-align: center;
        }
        .card-header h6 {
            color: #000000;
            font-size: 1.25rem;
            font-weight: bold;
        }
        .form-control {
            border-radius: 10px;
            padding: 10px;
        }
        .btn-block {
            border-radius: 10px;
            padding: 10px;
            font-weight: bold;
        }
        .modal-header {
            background-color: #4e73df;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .modal-footer {
            border-top: none;
        }
        .modal-footer .btn-primary {
            background-color: #1cc88a;
            border-color: #1cc88a;
        }
        .modal-footer .btn-primary:hover {
            background-color: #17a673;
            border-color: #13865d;
        }
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
        .dashboard-btn {
            margin-left: auto;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: bold;
        }
        .bg-dark {
            background-color: #00204a !important;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Change Your Password</h6>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="oldPassword">Old Password</label>
                            <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Change Password</button>
                    </form>
                    
                    <!-- Display error or success messages -->
                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger mt-3"><?php echo $errorMessage; ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($successMessage)): ?>
                        <div class="alert alert-success mt-3"><?php echo $successMessage; ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
