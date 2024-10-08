<?php
session_start();

include '../connect-db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $cnum = $_POST['cnum'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT); 
    $start_date = $_POST['start_date'];
    $salary = $_POST['salary'];

    $sql = "INSERT INTO maid (fname, lname, cnum, email, pass, start_date, salary) 
            VALUES ('$fname', '$lname', '$cnum', '$email', '$pass', '$start_date', '$salary')";

    if ($conn->query($sql) === TRUE) {
        echo "New maid added successfully";
        header("Location: index.php");
        exit(); 
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Maid</title>
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

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .btn-secondary {
            width: 10%;
        }
		
		.btn-cancel {
            background-color: #ed3c3b;
            border-color: #d62321;
			color: white;
            width: 10%;
			margin-left: 1150px;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-secondary:hover {
            background-color: #0056b3;
        }
		
		.bg-dark {
		background-color: #00204a !important;
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
        <h2>Add a New Maid</h2>
        <form action="addmaid.php" method="POST">
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter first name" required>
            </div>
            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter last name" required>
            </div>
            <div class="form-group">
                <label for="cnum">Contact Number:</label>
                <input type="text" class="form-control" id="cnum" name="cnum" placeholder="Enter contact number" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="form-group">
                <label for="pass">Password:</label>
                <input type="password" class="form-control" id="pass" name="pass" placeholder="Enter password" required>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>
            <div class="form-group">
                <label for="salary">Salary:</label>
                <input type="text" class="form-control" id="salary" name="salary" placeholder="Enter salary" required>
            </div>
            <button type="submit" class="btn btn-secondary">Add</button>
        </form>	
    </div>
	<br>
	<button type="button" class="btn btn-cancel" onclick="window.location.href='maid.html'">Cancel</button>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
