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

// Check if the form has been submitted to update the profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated values from the form
    $new_fname = $_POST['fname'];
    $new_lname = $_POST['lname'];
    $new_email = $_POST['email']; // This should ideally remain unchanged
    $new_phone = $_POST['cnum'];

    // Update the maid's information in the database
    $update_sql = "UPDATE maid SET fname = ?, lname = ?, cnum = ? WHERE email = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssss", $new_fname, $new_lname, $new_phone, $new_email);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Profile updated successfully!";
    } else {
        $_SESSION['message'] = "Failed to update profile.";
    }

    $stmt->close();

    // Redirect to the same page to refresh data and show the message
    header("Location: profile.php");
    exit();
}

// Fetch maid details from the database
$maid_email = $_SESSION['email']; // Assuming the email is stored in the session
$sql = "SELECT fname, lname, cnum, email FROM maid WHERE email='$maid_email'";
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

// Check for message in session
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); // Clear message after displaying
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MYKAKAKS Maid - User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style type="text/css">
        .ml-auto {}
        .rating-stars {
            color: gold;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <img src="img/logo.png" style="width: 100px; height: 33px;">
    </nav>

    <div class="container">
        <h1 class="mt-5">User Profile</h1>

        <!-- Display message if it exists -->
        <?php if ($message): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-lg-4 text-center">
                <img class="img-profile rounded-circle" src="img/undraw_profile.svg" alt="User profile image" width="150">
                <div class="rating-stars mt-3">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star"></i> <!-- Half-filled star -->
                </div>
            </div>
            <div class="col-lg-8">
                <form id="profileForm" method="POST" action="profile.php">
                    <div class="form-group">
                        <br><label for="fname">First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fname; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <br><label for="lname">Last Name</label>
                        <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <br><label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <br><label for="cnum">Phone Number</label>
                        <input type="text" class="form-control" id="cnum" name="cnum" value="<?php echo $cnum; ?>" readonly>
                    </div>
                    <button type="button" class="btn btn-primary mt-3" id="editButton">Edit Profile</button>
                    <button type="submit" class="btn btn-secondary mt-3 d-none" id="saveButton">Save Profile</button>
                    <button type="button" class="btn btn-danger mt-3 d-none" id="cancelButton">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Get elements
        const editButton = document.getElementById('editButton');
        const saveButton = document.getElementById('saveButton');
        const cancelButton = document.getElementById('cancelButton');
        const formInputs = document.querySelectorAll('#profileForm input');

        // Toggle read-only mode off and show Save/Cancel buttons
        editButton.addEventListener('click', function() {
            formInputs.forEach(input => input.removeAttribute('readonly'));
            editButton.classList.add('d-none');
            saveButton.classList.remove('d-none');
            cancelButton.classList.remove('d-none');
        });

        // Cancel editing, reset inputs and return to read-only mode
        cancelButton.addEventListener('click', function() {
            formInputs.forEach(input => input.setAttribute('readonly', true));
            saveButton.classList.add('d-none');
            cancelButton.classList.add('d-none');
            editButton.classList.remove('d-none');
            // Reset values to initial database values
            document.getElementById('fname').value = '<?php echo $fname; ?>';
            document.getElementById('lname').value = '<?php echo $lname; ?>';
            document.getElementById('email').value = '<?php echo $email; ?>';
            document.getElementById('cnum').value = '<?php echo $cnum; ?>';
        });
    </script>
</body>
</html>
