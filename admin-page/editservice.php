<?php
session_start();
include '../connect-db.php';

if (isset($_GET['edit_id'])) {
    $service_id = $_GET['edit_id'];

    // Fetch existing service details
    $sql = "SELECT * FROM services WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();

    if (!$service) {
        echo "Service not found!";
        exit();
    }
} else {
    header("Location: service.php");
    exit();
}

// Handle form submission for updating service
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $status = isset($_POST['status']) ? 1 : 0;

    // Check if a new image is uploaded
    if ($_FILES['image']['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . time() . '_' . basename($_FILES["image"]["name"]);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    } else {
        $image = $service['image']; // Use existing image if no new image is uploaded
    }

    // Update service details in database
    $update_sql = "UPDATE services SET name = ?, description = ?, price = ?, image = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssdsii", $name, $description, $price, $image, $status, $service_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = true;
        header("Location: service.php"); // Redirect to service.php after updating
        exit();
    } else {
        echo "Error updating service: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
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
        h3 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .btn-secondary {
            width: 15%;
        }
        .btn-secondary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Edit Service</h3>
        <form action="editservice.php?edit_id=<?php echo $service_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Service Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($service['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" id="description" name="description" value="<?php echo htmlspecialchars($service['description']); ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($service['price']); ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Upload Service Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <small>Current image: <?php echo htmlspecialchars($service['image']); ?></small>
            </div>
            <div class="form-group">
                <label for="status">Status [checked=hidden , un-checked=visible]</label>
                <br>
                <input type="checkbox" style="width:30px;height:30px" id="status" name="status" <?php echo $service['status'] ? 'checked' : ''; ?>>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="service.php" class="btn btn-secondary">Cancel</a>
        </form>  
    </div>
</body>
</html>
