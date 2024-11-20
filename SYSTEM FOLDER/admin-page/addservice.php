<?php 
session_start();
include '../connect-db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $status = isset($_POST['status']) ? 1 : 0; // Checkbox is checked, set status as 1
    $image = $_FILES['image']['name'];
    
    $target_dir = "../uploads/";
    $target_file = $target_dir . time() . '_' . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    } else {
        $uploadOk = 0;
        $_SESSION['message'] = "Sorry, there was an error uploading your file.";
        $_SESSION['message_type'] = "error";
    }

    if ($uploadOk == 1) {
        $sql = "INSERT INTO services (name, description, price, image, status) 
                VALUES ('$name', '$description', '$price', '$target_file', '$status')";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Service added successfully!";
            $_SESSION['message_type'] = "success";
            header("Location: addservice.php");
            exit();
        } else {
            $_SESSION['message'] = "Error adding service: " . mysqli_error($conn);
            $_SESSION['message_type'] = "error";
        }
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
             background: linear-gradient(to bottom right, #00204a 0%, #660066 80%);
        }
        .container {
            margin-top: 50px;
			margin-bottom: 50px;
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
    
        .alert-box {
            display: none;
            padding: 15px;
            margin-top: 10px;
            text-align: center;
            border-radius: 5px;
            color: white;
        }
        .alert-success {
            background-color: #28a745;
        }
        .alert-error {
            background-color: #dc3545;
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
        <h3>Add a New Service</h3>

        <div id="alertBox" class="alert-box"></div>

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
                <label for="status">Status [checked=hidden, unchecked=visible]</label>
                <br>
                <input type="checkbox" style="width:30px;height:30px" id="status" name="status">
            </div>
            <div class="form-row">
                <div class="col text-left">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                <div class="col text-right">
                    <button type="button" class="btn btn-danger" onclick="window.location.href='service.php'">Cancel</button>
                </div>
            </div>
        </form>  
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($_SESSION['message'])): ?>
                let alertBox = document.getElementById("alertBox");
                alertBox.textContent = "<?php echo $_SESSION['message']; ?>";
                alertBox.classList.add("alert-" + "<?php echo $_SESSION['message_type']; ?>");
                alertBox.style.display = "block";
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                setTimeout(() => { alertBox.style.display = "none"; }, 3000);
            <?php endif; ?>
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
