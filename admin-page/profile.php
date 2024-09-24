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

// Fetch admin details from the database
$admin_email = $_SESSION['email']; // Assuming you stored email in session during login

$sql = "SELECT fname, lname, cnum, email FROM admin WHERE email='$admin_email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch data for display
    $row = $result->fetch_assoc();
    $fname = $row['fname'];
    $lname = $row['lname'];
    $cnum = $row['cnum'];
    $email = $row['email'];
} else {
    echo "No records found!";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <img src="img/logo.png" style="width: 100px; height: 33px;">
    </nav>

    <div class="container">
        <h1 class="mt-5">Admin Profile</h1>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.html">Dashboard</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <img class="img-profile rounded-circle" src="img/undraw_profile.svg" alt="Admin profile image" width="150">
            </div>
            <div class="col-lg-8">
                <form id="profileForm">
                    <div class="form-group">
                        <br><label for="fname">First Name</label>
                        <input type="text" class="form-control" id="fname" value="<?php echo $fname; ?>" readonly>
                    </div><br>
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" class="form-control" id="lname" value="<?php echo $lname; ?>" readonly>
                    </div><br>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="<?php echo $email; ?>" readonly>
                    </div><br>
                    <div class="form-group">
                        <label for="cnum">Phone Number</label>
                        <input type="number" class="form-control" id="cnum" value="<?php echo $cnum; ?>">
                    </div><br>
                    <button type="button" class="btn btn-primary mt-3" id="editButton">Edit Profile</button>
                    <button type="submit" class="btn btn-secondary mt-3 d-none" id="saveButton">Save Profile</button>
                    <button type="button" class="btn btn-danger mt-3 d-none" id="cancelButton">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const editButton = document.getElementById('editButton');
        const saveButton = document.getElementById('saveButton');
        const cancelButton = document.getElementById('cancelButton');
        const formInputs = document.querySelectorAll('#profileForm input');

        editButton.addEventListener('click', function() {
            formInputs.forEach(input => input.removeAttribute('readonly'));
            editButton.classList.add('d-none');
            saveButton.classList.remove('d-none');
            cancelButton.classList.remove('d-none');
        });

        cancelButton.addEventListener('click', function() {
            formInputs.forEach(input => input.setAttribute('readonly', true));
            saveButton.classList.add('d-none');
            cancelButton.classList.add('d-none');
            editButton.classList.remove('d-none');
        });
    </script>
</body>
</html>
