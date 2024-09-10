<?php
session_start();

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

// Initialize error message variable
$error_message = "";

if (isset($_COOKIE['user'])) {
    list($email, $hashed_password) = explode('|', $_COOKIE['user']);

    // Verify cookie credentials
    $stmt = $conn->prepare("SELECT pass, user_type FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($stored_password, $user_type);
        $stmt->fetch();
        
        if (password_verify($hashed_password, $stored_password)) {
            $_SESSION['email'] = $email;
            $_SESSION['user_type'] = $user_type;

            // Redirect based on user type
            if ($user_type === 'admin') {
                header("Location: admin-page/index.html");
            } elseif ($user_type === 'customer') {
                header("Location: customer-page/index.html");
            } elseif ($user_type === 'maid') {
                header("Location: maid-page/index.html");
            } else {
                $error_message = "Unknown user type!";
            }
            exit();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    // Prepare and execute query to check credentials
    $stmt = $conn->prepare("SELECT pass, user_type FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password, $user_type);
        $stmt->fetch();
        
        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables
            $_SESSION['email'] = $email;
            $_SESSION['user_type'] = $user_type;

            // Set a cookie for "Remember Me"
            if ($remember_me) {
                $cookie_name = "user";
                $cookie_value = $email . "|" . $hashed_password;
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 30 days
            } else {
                if (isset($_COOKIE['user'])) {
                    setcookie('user', '', time() - 3600, '/'); // Delete cookie if "Remember Me" is not checked
                }
            }

            // Redirect based on user type
            if ($user_type === 'admin') {
                header("Location: admin-page/index.html");
            } elseif ($user_type === 'customer') {
                header("Location: customer-page/index.html");
            } elseif ($user_type === 'maid') {
                header("Location: maid-page/index.html");
            } else {
                $error_message = "Unknown user type!";
            }
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "No user found with this email!";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="styling.css" rel="stylesheet">
    <script>
        // Display error message if there is one
        <?php if (!empty($error_message)): ?>
        window.onload = function() {
            alert("<?php echo addslashes($error_message); ?>");
        };
        <?php endif; ?>
    </script>
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
                                    <h1 class="h4 text-gray-900 mb-4"><b>Login</b></h1>
                                </div>
                                <form id="loginForm" class="user" action="login.php" method="POST">
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email Address" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="rememberMe" name="remember_me">
                                                <label class="custom-control-label" for="rememberMe">Remember Me</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mb-3">
                                        <a class="small" href="forgot-password.php">Forgot Password?</a>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                </form>
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
