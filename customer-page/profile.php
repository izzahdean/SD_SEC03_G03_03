<?php
// Start the session
session_start();

include '../connect-db.php';

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Check if the form has been submitted to update the profile
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get updated values from the form
        $new_fname = $_POST['fname'];
        $new_lname = $_POST['lname'];
        $new_phone = $_POST['cnum'];
        $new_address = $_POST['address'];
        
        // Update the customer's information in the database
        $update_sql = "UPDATE customer SET fname = ?, lname = ?, cnum = ?, address = ? WHERE email = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssss", $new_fname, $new_lname, $new_phone, $new_address, $email);
        
        if ($stmt->execute()) {
            // Redirect to the same page to refresh data
            header("Location: profile.php");
            exit();
        } else {
            echo "<script>alert('Failed to update profile.');</script>";
        }

        $stmt->close();
    }

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
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>MyKakaks Customer Profile</title>

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
                </div>
                <form id="profileForm" method="POST" action="profile.php">
                    <div class="row mt-2">
                        <div class="col-md-6"><input type="text" class="form-control" name="fname" value="<?php echo $first_name; ?>" readonly></div>
                        <div class="col-md-6"><input type="text" class="form-control" name="lname" value="<?php echo $last_name; ?>" readonly></div>
                    </div>
                    <br>
                    <div class="row mt-3">
                        <div class="col-md-6"><input type="number" class="form-control" name="cnum" value="<?php echo $contact_number; ?>" readonly></div>
                        <div class="col-md-6"><input type="text" class="form-control" value="<?php echo $email; ?>" readonly></div>
                    </div>
                    <br>
                    <div class="row mt-3">
                        <div class="col-md-6"><input type="text" class="form-control" name="address" value="<?php echo $address; ?>" readonly></div>
                    </div>
                    <br>
                    <div class="mt-5 text-right">
                        <button type="button" class="btn btn-primary profile-button" id="editButton">Edit Profile</button>
                        <button type="submit" class="btn btn-success d-none" id="saveButton">Save Profile</button>
                        <button type="button" class="btn btn-danger d-none" id="cancelButton">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
        // Reset values to initial (static example values)
        document.getElementById('profileForm').reset();
        document.querySelector("input[name='fname']").value = '<?php echo $first_name; ?>';
        document.querySelector("input[name='lname']").value = '<?php echo $last_name; ?>';
        document.querySelector("input[name='cnum']").value = '<?php echo $contact_number; ?>';
        document.querySelector("input[name='address']").value = '<?php echo $address; ?>';
    });
</script>

</body>
</html>
