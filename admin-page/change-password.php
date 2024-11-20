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

            $updateAdmin = "UPDATE admin SET pass = ? WHERE email = ?";
            $stmtAdmin = $conn->prepare($updateAdmin);
            $stmtAdmin->bind_param("ss", $hashedPassword, $email);
            $stmtAdmin->execute();

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
        html, body {
			height: 100%;
			margin: 0;
			background: linear-gradient(to bottom right, #00204a 0%, #660066 100%);
		}
		#wrapper {
			min-height: 100vh;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}
		
		.container {
            margin-top: 50px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
			max-width: 600px;
        }
		.form-control {
			width: 100%; 
			max-width: 600px;
		}
		.form-group label {
            font-weight: bold;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
		h3 {
			font-weight: bold;
			text-align: center;
            color: black;
        }
		.color {
            color: black;
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
		.btn-primary:disabled {
            background-color: #cccccc;
            border-color: #cccccc;
        }
        .valid {
			color: green;
		}

		invalid {
			color: red;
		}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark shadow-sm">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">
			<img src="img/logo.png" alt="Logo" style="width: 100px; height: 33px;">
			</a>
		</div>
	</nav>
    <div class="container">
        <h3 class="text-center">Change Password</h3>
        <div class="card-body">
            <?php if ($message): ?>
                <p class="<?= ($message === 'Password changed successfully!') ? 'message' : 'error' ?>">
                    <?= htmlspecialchars($message) ?>
                </p>
            <?php endif; ?>
            <form action="" method="POST" id="change-password-form">
                <div class="form-group">
                    <label for="oldPassword">Old Password</label>
                    <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                    <div id="password-requirements" class="password-requirement">
						<p id="length" class="invalid">At least 8 characters</p>
						<p id="uppercase" class="invalid">At least one uppercase letter</p>
						<p id="number" class="invalid">At least one number</p>
						<p id="special" class="invalid">At least one special character</p>
					</div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                </div>
                <div class="form-row">
                    <div class="col text-left ">
                        <input type="submit" value="Save" class="btn btn-primary save-btn" id="saveBtn" disabled>
                    </div>
                    <div class="col text-right">
                        <button type="button" class="btn btn-danger" onclick="window.location.href='index.php'">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script>
        function checkPasswordStrength() {
			const password = document.getElementById("newPassword").value;
			const conditions = [
				{ id: "length", regex: /.{8,}/ },
				{ id: "uppercase", regex: /[A-Z]/ },
				{ id: "number", regex: /[0-9]/ },
				{ id: "special", regex: /[!@#$%^&*(),.?":{}|<>]/ }
			];

			let allValid = true;

			conditions.forEach(condition => {
				const element = document.getElementById(condition.id);
				const isValid = condition.regex.test(password);
				element.classList.toggle("valid", isValid);
				element.classList.toggle("invalid", !isValid);
				if (!isValid) allValid = false;
			});

			document.getElementById("saveBtn").disabled = !allValid;
		}

		document.getElementById("newPassword").addEventListener("input", checkPasswordStrength);
    </script>
</body>
</html>
