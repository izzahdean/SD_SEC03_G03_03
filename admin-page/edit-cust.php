<?php
session_start();
include '../connect-db.php';

if (isset($_GET['id'])) {
    $customerId = $_GET['id'];

    $sql = "SELECT * FROM customer WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $customer = $result->fetch_assoc();
    } else {
        echo "<script>alert('Customer not found.'); window.location.href = 'customer.php';</script>";
        exit;
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'customer.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];

    $updateCustomerSql = "UPDATE customer SET status = ? WHERE id = ?";
    $updateCustomerStmt = $conn->prepare($updateCustomerSql);
    $updateCustomerStmt->bind_param("si", $status, $customerId);

    if ($updateCustomerStmt->execute()) {
        header("Location: customer.php");
            exit;
    } else {
        echo "<script>alert('Error updating customer: " . $conn->error . "');</script>";
    }

    $updateCustomerStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Customer Status</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/mykakaks-admin.css" rel="stylesheet">
	<link rel="shortcut icon" href="img/favicon.png" type="">
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
			max-width: 600px;
        }
		.form-control {
			width: 100%; 
			max-width: 600px;
		}
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
		h3 {
			font-weight: bold;
			text-align: center;
            color: black;
        }
		.color {
            color: black;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            width: auto%;
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
        <h3>Edit Customer Status</h3>
        <form method="POST" action="edit-cust.php?id=<?php echo $customerId; ?>">
            <div class="form-group">
                <label for="status" class="color">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="active" <?php echo ($customer['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($customer['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
			
			<div class="form-row">
                <div class="col text-left ">
                    <input type="submit" value="Update" class="btn btn-primary save-btn">
                </div>
                <div class="col text-right">
                    <button type="button" class="btn btn-danger" onclick="window.location.href='customer.php'">Cancel</button>
                </div>
            </div>
        </form>
    </div>

</body>
</html>
