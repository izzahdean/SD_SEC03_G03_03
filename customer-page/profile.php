<?php
// Start the session
session_start();

// Include your database connection
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

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Fetch user details from the customer table
    $stmt = $conn->prepare("SELECT fname, lname, cnum, address, email FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($first_name, $last_name, $contact_number, $address, $email);
    $stmt->fetch();
    $stmt->close();
} else {
    // Redirect to login page if the user is not logged in
    header("Location: .../login.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
  <!-- Basic Meta Tags -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>MyKakaks Profile</title>

  <!-- CSS links -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  <link href="css/profilestyle.css" rel="stylesheet" />
  <link href="css/responsive.css" rel="stylesheet" />
</head>

<body>
<div class="container rounded bg-white mt-5">
    <div class="row">
        <div class="col-md-4 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img class="rounded-circle mt-5" src="images/profile.png" width="90"><br>
                <span class="font-weight-bold"><?php echo $first_name . " " . $last_name; ?></span>
                <span class="text-black-50"><?php echo $email; ?></span>
            </div>
        </div>
        <div class="col-md-8">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex flex-row align-items-center back"><i class="fa fa-long-arrow-left mr-1 mb-1"></i>
                        <a href="index.html"><b>Back to home</b></a>
                    </div>
                    <h6 class="text-right">Edit Profile</h6>
                </div>
                <br>
                <div class="row mt-2">
                    <div class="col-md-6"><input type="text" class="form-control" placeholder="First Name" value="<?php echo $first_name; ?>" readonly></div>
                    <div class="col-md-6"><input type="text" class="form-control" placeholder="Last Name" value="<?php echo $last_name; ?>" readonly></div>
                </div>
                <br>
                <div class="row mt-3">
                    <div class="col-md-6"><input type="number" class="form-control" placeholder="Phone number" value="<?php echo $contact_number; ?>" readonly></div>
                    <div class="col-md-6"><input type="text" class="form-control" placeholder="Email" value="<?php echo $email; ?>" readonly></div>
                </div>
                <br>
                <div class="row mt-3">
                    <div class="col-md-6"><input type="text" class="form-control" value="<?php echo $address; ?>" placeholder="Address" readonly></div>
                </div>
                <div class="mt-5 text-right"><button class="btn btn-primary profile-button" type="button">Edit Profile</button></div>
            </div>
        </div>
    </div>
</div>
</body>

</html>
