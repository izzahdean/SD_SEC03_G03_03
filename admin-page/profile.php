<?php
session_start();
include '../connect-db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_fname = trim($_POST['fname']);
    $new_lname = trim($_POST['lname']);
    $new_cnum = trim($_POST['cnum']);
    
	if (empty($new_fname) || empty($new_lname) || empty($new_cnum)) {
        $_SESSION['message'] = "All fields are required!";
        header("Location: profile.php");
        exit();
    }

    $update_sql = "UPDATE admin SET fname = ?, lname = ?, cnum = ? WHERE email = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssss", $new_fname, $new_lname, $new_cnum, $admin_email);
     
    if ($stmt->execute()) {
        $_SESSION['message'] = "Profile updated successfully!";
    } else {
        $_SESSION['message'] = "Failed to update profile.";
    }

    $stmt->close();
    header("Location: profile.php");
    exit();
}

$admin_email = $_SESSION['email'];
$sql = "SELECT fname, lname, cnum, email FROM admin WHERE email='$admin_email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fname = $row['fname'];
    $lname = $row['lname'];
    $cnum = $row['cnum'];
    $email = $row['email'];
} else {
    echo "No records found!";
}
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); 
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="img/favicon.png" type="">
    <title>Admin Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #231a6f;
        }
        .profile-container {
            margin-top: 50px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .img-profile {
            border-radius: 50%;
            border: 4px solid #007bff;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            font-size: 0.875em;
            margin-top: 5px;
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

    <div class="container profile-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex flex-row align-items-center back">
                <a href="index.html"><b>Back to home</b></a>
            </div>
        </div>
        <h2 class="text-center mb-5">ADMIN PROFILE</h2>

        <?php if ($message): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4 text-center">
                <img class="img-profile" src="img/undraw_profile.svg" alt="Admin profile image" width="150">
            </div>
            <div class="col-md-8">
                <form id="profileForm" method="POST" action="profile.php">
                    <div class="form-group mb-3">
                        <label for="fname">First Name</label>
                        <input type="text" class="form-control" name="fname" id="fname" value="<?php echo htmlspecialchars($fname); ?>" readonly required>
                        <span class="error-message" id="fname-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="lname">Last Name</label>
                        <input type="text" class="form-control" name="lname" id="lname" value="<?php echo htmlspecialchars($lname); ?>" readonly required>
                        <span class="error-message" id="lname-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="cnum">Phone Number</label>
                        <input type="number" class="form-control" name="cnum" id="cnum" value="<?php echo htmlspecialchars($cnum); ?>" readonly required>
                        <span class="error-message" id="cnum-error"></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-primary" id="editButton">Edit Profile</button>
                        <div>
							<button type="button" class="btn btn-danger d-none" id="cancelButton">Cancel</button>
                            <button type="submit" class="btn btn-primary d-none" id="saveButton">Update</button>
                        </div>
                    </div>
                </form>
                <br>
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
            formInputs.forEach(input => {
                if (input.id !== 'email') {
                    input.removeAttribute('readonly');
                }
            });
            editButton.classList.add('d-none');
            saveButton.classList.remove('d-none');
            cancelButton.classList.remove('d-none');
        });

        cancelButton.addEventListener('click', function() {
            formInputs.forEach(input => {
                if (input.id !== 'email') {
                    input.setAttribute('readonly', true);
                }
            });
            saveButton.classList.add('d-none');
            cancelButton.classList.add('d-none');
            editButton.classList.remove('d-none');
            document.querySelector("input[name='fname']").value = '<?php echo htmlspecialchars($fname); ?>';
            document.querySelector("input[name='lname']").value = '<?php echo htmlspecialchars($lname); ?>';
            document.querySelector("input[name='cnum']").value = '<?php echo htmlspecialchars($cnum); ?>';
        });

        document.getElementById('profileForm').addEventListener('submit', function(event) {
            const fname = document.getElementById('fname').value.trim();
            const lname = document.getElementById('lname').value.trim();
            const cnum = document.getElementById('cnum').value.trim();

            if (fname === '' || lname === '' || cnum === '') {
                alert('All fields are required!');
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
