<?php
session_start();

include '../connect-db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="img/favicon.png" type="">
    <title>Add Service</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom right, #00204a 0%, #660066 100%);
        }

        .container {
            margin-top: 50px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
			max-width: 800px;
        }
		.form-control {
			width: 100%; 
			max-width: 800px;
		}
        h3 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .btn-secondary {
            width: 15%;
        }
		
		.btn-cancel {
            background-color: #ed3c3b;
            border-color: #d62321;
			color: white;
            width: auto;
			
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
	<nav class="navbar navbar-expand-lg navbar-light bg-dark shadow-sm ">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="img/logo.png" alt="Logo" style="width: 100px; height: 33px;">
            </a>
        </div>
    </nav>
	
    <div class="container">
        <h3>Add a New Service</h3>
        <form action="addservice.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Service Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter service name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Enter service description" required>
            </div>
			<div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" required>
            </div>
			<div class="form-group">
                <label for="image">Upload Service Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
			<div class="form-group">
                <label for="status">Status [checked=hidden , un-checked=visible]</label>
				<br>
                <input type="checkbox" style="width:30px;height:30px" id="status" name="status" required>
            </div>
			<div class="form-row">
                <div class="col text-left ">
				<button type="submit" class="btn btn-primary">Save</button>
				</div>
				<div class="col text-right ">
				<button type="button" class="btn btn-danger" onclick="window.location.href='service.html'">Cancel</button>
				</div>
			</div>
			
        </form>	
    </div>
	<br>
	<br>
	<br>
	<br>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

