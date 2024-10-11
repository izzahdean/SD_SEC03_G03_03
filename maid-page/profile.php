<?php
session_start();

include '../connect-db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_fname = trim($_POST['fname']);
    $new_lname = trim($_POST['lname']);
    $new_email = trim($_POST['email']);
    $new_phone = trim($_POST['cnum']);

    if (empty($new_fname) || empty($new_lname) || empty($new_email) || empty($new_phone)) {
        $_SESSION['message'] = "All fields are required!";
        header("Location: profile.php");
        exit();
    }

    $update_sql = "UPDATE maid SET fname = ?, lname = ?, cnum = ? WHERE email = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssss", $new_fname, $new_lname, $new_phone, $new_email);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Profile updated successfully!";
    } else {
        $_SESSION['message'] = "Failed to update profile.";
    }

    $stmt->close();
    header("Location: profile.php");
    exit();
}

$maid_email = $_SESSION['email']; 
$sql = "SELECT fname, lname, cnum, email, start_date, salary FROM maid WHERE email='$maid_email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fname = $row['fname'];
    $lname = $row['lname'];
    $cnum = $row['cnum'];
    $email = $row['email'];
    $start_date = $row['start_date'];
    $salary = $row['salary'];
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
    <title>MYKAKAKS Maid - User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style type="text/css">
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="img/logo.png" alt="Logo" width="100" height="33">
            </a>
        </div>
    </nav>

    <div class="container profile-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex flex-row align-items-center back"><i class="fa fa-long-arrow-left mr-1 mb-1"></i>
                <a href="index.html"><b>Back to home</b></a>
            </div>
        </div>
        <h1 class="text-center mb-5">Maid Profile</h1>
            
        <?php if ($message): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-4 text-center">
                <img class="img-profile profile-image" src="img/undraw_profile.svg" alt="User profile image" width="150">
                <div class="rating-stars mt-3">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star"></i> 
                </div>
            </div>
            <div class="col-lg-8">
                <form id="profileForm" method="POST" action="profile.php">
                    <div class="form-group mb-3">
                        <label for="fname">First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fname; ?>" readonly required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="lname">Last Name</label>
                        <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname; ?>" readonly required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" readonly required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="cnum">Phone Number</label>
                        <input type="text" class="form-control" id="cnum" name="cnum" value="<?php echo $cnum; ?>" readonly required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="start_date">Start Date</label>
                        <input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="salary">Salary</label>
                        <input type="text" class="form-control" id="salary" name="salary" value="<?php echo $salary; ?>" readonly>
                    </div>
                    <button type="button" class="btn btn-primary btn-custom" id="editButton">Edit Profile</button>
                    <button type="submit" class="btn btn-secondary btn-custom d-none" id="saveButton">Save</button>
                    <button type="button" class="btn btn-danger btn-custom d-none" id="cancelButton">Cancel</button>
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
                if (input.id !== 'email' && input.id !== 'start_date' && input.id !== 'salary') {
                    input.removeAttribute('readonly');
                }
            });
            editButton.classList.add('d-none');
            saveButton.classList.remove('d-none');
            cancelButton.classList.remove('d-none');
        });

        cancelButton.addEventListener('click', function() {
            formInputs.forEach(input => input.setAttribute('readonly', true));
            saveButton.classList.add('d-none');
            cancelButton.classList.add('d-none');
            editButton.classList.remove('d-none');
            document.getElementById('fname').value = '<?php echo $fname; ?>';
            document.getElementById('lname').value = '<?php echo $lname; ?>';
            document.getElementById('cnum').value = '<?php echo $cnum; ?>';
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
