<?php
session_start(); 
include '../connect-db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maid_id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $cnum = $_POST['cnum'];
    $email = $_POST['email'];

    $update_maid_query = "UPDATE maid SET fname = '$fname', lname = '$lname', cnum = '$cnum', email = '$email' WHERE id = $maid_id";
    
    if (mysqli_query($conn, $update_maid_query)) {
        $update_user_query = "UPDATE users SET email = '$email' WHERE user_type = 'maid' AND email = (SELECT email FROM maid WHERE id = $maid_id)";
        
        if (mysqli_query($conn, $update_user_query)) {
            header("Location: maid.php");
            exit;
        } else {
            echo "<div class='alert alert-warning'>Maid updated, but error updating user: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error updating maid: " . mysqli_error($conn) . "</div>";
    }
} else {
    if (isset($_GET['id'])) {
        $maid_id = $_GET['id'];

        $query = "SELECT * FROM maid WHERE id = $maid_id";
        $result = mysqli_query($conn, $query);
        $maid = mysqli_fetch_assoc($result);

        if (!$maid) {
            echo "<div class='alert alert-danger'>Maid not found.</div>";
            exit;
        }
    } else {
        echo "<div class='alert alert-danger'>No ID specified.</div>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Maid</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #231a6f;
        }
        .container {
            margin-top: 50px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            width: 100%;
        }
        .bg-dark {
            background-color: #00204a !important;
        }
		.save-btn {
        width: auto;
        white-space: nowrap;
        padding-left: 10px;
        padding-right: 10px;
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
        <h1 class="text-center">Edit Maid</h1>
        <form action="edit-maid.php?id=<?php echo $maid['id']; ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo $maid['id']; ?>">
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" name="fname" class="form-control" value="<?php echo $maid['fname']; ?>" required>
            </div>

            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" name="lname" class="form-control" value="<?php echo $maid['lname']; ?>" required>
            </div>

            <div class="form-group">
                <label for="cnum">Phone Number:</label>
                <input type="text" name="cnum" class="form-control" value="<?php echo $maid['cnum']; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" value="<?php echo $maid['email']; ?>" required>
            </div>

            <div class="form-row">
                <div class="col text-left ">
                    <input type="submit" value="Save" class="btn btn-primary save-btn">
                </div>
                <div class="col text-right">
                    <button type="button" class="btn btn-danger" onclick="window.location.href='maid.php'">Cancel</button>
                </div>
            </div>
        </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
