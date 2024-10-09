<?php
session_start();

include '../connect-db.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
	$price = $_POST['price'];

    $sql = "INSERT INTO services (name, description, start_date, price) VALUES ('$name', '$description', '$start_date', '$price')";

    if ($conn->query($sql) === TRUE) {
        echo "New service added successfully";
        header("Location: index.php"); // Redirect back to the maid list page
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
	<link rel="shortcut icon" href="img/favicon.png" type="">
    <title>Add Service</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
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
        <h2>Add a New Service</h2>
        <form action="addservice.php" method="POST">
            <div class="form-group">
                <label for="name">Service Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter service name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Enter service description" required>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>
			<div class="form-group">
                <label for="start_date">Price:</label>
                <input type="text" class="form-control" id="price" name="price" required>
            </div>
			
			<div class="form-row">
                <div class="col text-left ">
				<button type="submit" class="btn btn-secondary">Add</button>
				</div>
				<div class="col text-right ">
				<button type="button" class="btn btn-cancel">Cancel</button>
				</div>
			</div>
			
        </form>	
    </div>
	
		<br>
		

    <!-- Bootstrap JS and dependencies (Optional for interactive features) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>

