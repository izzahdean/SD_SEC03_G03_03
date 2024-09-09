<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$servername = "localhost";
$username = "wp2024";
$password = "@webprogramming"; // Update this with your MySQL password
$dbname = "mySister";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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

    // Password validation
    if ($password !== $repeat_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind SQL statement to insert into `customer` table
    $stmt = $conn->prepare("INSERT INTO customer (fname, lname, cnum, address, email, pass) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $first_name, $last_name, $contact_number, $address, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        // Prepare email verification
        $verification_code = md5($email . time()); // Unique verification code
        $stmt = $conn->prepare("INSERT INTO email_verification (email, code) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $verification_code);
        $stmt->execute();
        
        // Send verification email
        $to = $email;
		$subject = "Verify Your Email Address";
		$message = "Please click the following link to verify your email address:\n";
		$message .= "http://localhost/test-run/verify-email.php?code=$verification_code";
		$headers = "From: no-reply@yourdomain.com\r\n";
		$headers .= "Reply-To: no-reply@yourdomain.com\r\n";
		$headers .= "Content-type: text/plain; charset=UTF-8\r\n";

		if (mail($to, $subject, $message, $headers)) {
		header("Location: check-email.php");
		exit();
		}else {
			echo "Failed to send verification email.";
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
                                                <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
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
    </body>
    </html>
    <?php
}
?>
