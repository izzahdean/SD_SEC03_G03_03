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
</head>

<body>

    <div class="container mt-5">
        <h2>Edit Customer Status</h2>
        <form method="POST" action="edit-cust.php?id=<?php echo $customerId; ?>">
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="active" <?php echo ($customer['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($customer['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Status</button>
            <a href="customer.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

</body>
</html>
