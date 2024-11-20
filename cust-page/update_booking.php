<?php
include '../connect-db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $payment_status = $_POST['payment_status'];
    $payment_method = $_POST['payment_method'];
    $amount = $_POST['total_price'];

    $sql = "UPDATE booking SET payment_status = ?, payment_method = ?, amount = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $payment_status, $payment_method, $amount, $booking_id);

    if ($stmt->execute()) {
        echo "Payment status updated successfully.";
    } else {
        echo "Error updating payment status: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
