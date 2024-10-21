<?php
session_start();

include 'connect-db.php';

$error_message = "";
if (isset($_COOKIE['user'])) {
    list($email, $hashed_password) = explode('|', $_COOKIE['user']);

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

            $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE email = ?");
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                echo "Last login updated successfully.";
            } else {
                echo "Error updating last_login: " . $stmt->error;
            }
            $stmt->close();

            if (isset($_POST['remember_me'])) {
                $cookie_name = "user";
                $cookie_value = $email . "|" . $hashed_password;
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
            } else {
                if (isset($_COOKIE['user'])) {
                    setcookie('user', '', time() - 3600, '/'); 
                }
            }

            switch ($user_type) {
                case 'admin':
                    header("Location: admin-page/index.html");
                    exit();
                case 'customer':
                    header("Location: cust-page/index.html");
                    exit();
                case 'maid':
                    header("Location: maid-page/index.html");
                    exit();
                default:
                    $error_message = "Unknown user type!";
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    $stmt = $conn->prepare("SELECT pass, user_type FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password, $user_type);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['email'] = $email;
            $_SESSION['user_type'] = $user_type;

            $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE email = ?");
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                echo "Last login updated successfully.";
            } else {
                echo "Error updating last_login: " . $stmt->error;
            }
            $stmt->close();

            if ($remember_me) {
                $cookie_name = "user";
                $cookie_value = $email . "|" . $hashed_password;
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
            } else {
                if (isset($_COOKIE['user'])) {
                    setcookie('user', '', time() - 3600, '/'); 
                }
            }

            switch ($user_type) {
                case 'admin':
                    header("Location: admin-page/index.html");
                    exit();
                case 'customer':
                    header("Location: cust-page/index.html");
                    exit();
                case 'maid':
                    header("Location: maid-page/index.html");
                    exit();
                default:
                    $error_message = "Unknown user type!";
            }
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
	<link rel="shortcut icon" href="image/favicon.png" type="image/png">
    <title>Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="styling.css" rel="stylesheet">
    <script>
        function validateForm() {
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            var emailError = document.getElementById('email-error');
            var passwordError = document.getElementById('password-error');
            
            emailError.textContent = "";
            passwordError.textContent = "";

            var isValid = true;

            if (!email) {
                emailError.textContent = "Email is required!";
                isValid = false;
            }

            if (!password) {
                passwordError.textContent = "Password is required!";
                isValid = false;
            }

            return isValid;
        }

        <?php if (!empty($error_message)): ?>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('php-error-message').textContent = "<?php echo addslashes($error_message); ?>";
        });
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
                    </div>
                    <div class="col-lg-6 d-flex align-items-center justify-content-center">
                        <div class="p-4 w-100">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4"><b>Login</b></h1>
                                </div>
                                <form id="loginForm" class="user" action="login.php" method="POST" onsubmit="return validateForm();">
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email Address" required>
                                        <div id="email-error" class="text-danger"></div> <!-- Email error message -->
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                                        <div id="password-error" class="text-danger"></div> <!-- Password error message -->
                                    </div>
                                    <div id="php-error-message" class="text-danger"></div> <!-- PHP Error message container -->
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
