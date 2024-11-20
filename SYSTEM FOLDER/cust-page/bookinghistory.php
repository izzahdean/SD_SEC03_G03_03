<?php
session_start();
include '../connect-db.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

// Fetch customer ID using email
$email = $_SESSION['email'];
$query = "SELECT id FROM customer WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['cust_id'] = $row['id'];
} else {
    die("Customer not found.");
}

// Fetch booking history
$cust_id = $_SESSION['cust_id'];
$sql = "SELECT b.booking_date, b.booking_slot, s.name AS service_name, b.total_price, b.booking_status
        FROM booking b
        JOIN services s ON b.service_id = s.id
        WHERE b.cust_id = ?
        ORDER BY b.booking_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cust_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.png" type="">
    <title>Booking History</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            background: linear-gradient(to bottom right, #00204a 0%, #660066 100%);
        }
        #wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .container {
            margin-top: 50px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
        }
        .form-control {
            width: 100%; 
            max-width: 600px;
        }
        .form-group label {
            font-weight: bold;
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
        .navbar {
            background-color: #4e73df !important;
            color: white;
        }
        .navbar h1 {
            color: white;
        }
        .btn-primary {
            background-color: #231a6f;
            border-color: #4e73df;
        }
        .btn-primary:hover {
            background-color: #0f054c;
            border-color: #2653d4;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #f8f9fc;
            border-bottom: none;
            text-align: center;
        }
        .card-header h6 {
            color: #000000;
            font-size: 1.25rem;
            font-weight: bold;
        }
        .form-control {
            border-radius: 10px;
            padding: 10px;
        }
        .btn-block {
            border-radius: 10px;
            padding: 10px;
            font-weight: bold;
        }
        .modal-header {
            background-color: #4e73df;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .modal-footer {
            border-top: none;
        }
        .modal-footer .btn-primary {
            background-color: #1cc88a;
            border-color: #1cc88a;
        }
        .modal-footer .btn-primary:hover {
            background-color: #17a673;
            border-color: #13865d;
        }
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
        .dashboard-btn {
            margin-left: auto;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: bold;
        }
        .bg-dark {
            background-color: #00204a !important;
        }
        .message {
            color: green;
            font-weight: bold;
            text-align: center;
        }
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
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
        <h3 class="text-center">Booking History</h3>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Booking Date</th>
                            <th>Slot</th>
                            <th>Service</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php $count = 1; ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $count++; ?></td>
                                    <td><?= htmlspecialchars($row['booking_date']); ?></td>
                                    <td><?= htmlspecialchars($row['booking_slot']); ?></td>
                                    <td><?= htmlspecialchars($row['service_name']); ?></td>
                                    <td>RM <?= number_format($row['total_price'], 2); ?></td>
                                    <td><?= ucfirst($row['booking_status']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No bookings found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="col text-right"><br>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='index.php'">Back</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
