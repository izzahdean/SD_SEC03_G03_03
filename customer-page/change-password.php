<?php
session_start();
include '../connect-db.php';

$message = "";  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $email = $_SESSION['email'];  

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (password_verify($oldPassword, $user['pass'])) {
        if ($newPassword === $confirmPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateUser = "UPDATE users SET pass = ? WHERE email = ?";
            $stmtUser = $conn->prepare($updateUser);
            $stmtUser->bind_param("ss", $hashedPassword, $email);
            $stmtUser->execute();

            $updateCustomer = "UPDATE customer SET pass = ? WHERE email = ?";
            $stmtCustomer = $conn->prepare($updateCustomer);
            $stmtCustomer->bind_param("ss", $hashedPassword, $email);
            $stmtCustomer->execute();

            $message = "Password changed successfully!";
        } else {
            $message = "New passwords do not match.";
        }
    } else {
        $message = "Old password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.png" type="">
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
        .message {
            color: green;
            font-weight: bold;
            text-align: center;
        }
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-dark topbar mb-4 static-top shadow">
                    <h1><img src="img/logo.png" style="width: 100px; height: 33px;"></h1>
                    <button class="btn btn-primary ml-auto" onclick="window.location.href='index.html'">
                        Back to Dashboard
                    </button>
                </nav>
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold">Change Your Password</h6>
                                </div>
                                <div class="card-body">
                                    <?php if ($message): ?>
                                        <p class="<?= ($message === 'Password changed successfully!') ? 'message' : 'error' ?>">
                                            <?= htmlspecialchars($message) ?>
                                        </p>
                                    <?php endif; ?>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
