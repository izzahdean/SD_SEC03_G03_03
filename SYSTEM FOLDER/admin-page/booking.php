<?php
include '../connect-db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to manage bookings.");
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

$sql = "SELECT b.booking_id, b.cust_id, b.service_id, b.booking_date, b.booking_slot, b.booking_status, b.maid_id, 
               c.fname AS customer_fname, c.lname AS customer_lname, s.name AS service_name, m.fname AS maid_fname, m.lname AS maid_lname
        FROM booking b
        LEFT JOIN customer c ON b.cust_id = c.id
        LEFT JOIN services s ON b.service_id = s.id
        LEFT JOIN maid m ON b.maid_id = m.id
        WHERE b.booking_status IN ('ongoing', 'pending', 'completed')";

$result = mysqli_query($conn, $sql);

$completed_sql = "SELECT b.booking_id, b.booking_date, b.booking_slot, b.total_price, b.amount, b.payment_method, c.fname AS customer_name, m.fname AS maid_name
                  FROM booking b
                  LEFT JOIN customer c ON b.cust_id = c.id
                  LEFT JOIN maid m ON b.maid_id = m.id
                  WHERE b.payment_status = 'completed' AND b.booking_status = 'completed'
                  ORDER BY b.payment_date DESC LIMIT 5"; 

$completed_result = $conn->query($completed_sql);

$alerts = [];
if ($completed_result->num_rows > 0) {
    while ($row = $completed_result->fetch_assoc()) {
        $alerts[] = $row;
    }
}

$maids_sql = "SELECT id, fname, lname FROM maid";
$maids_result = mysqli_query($conn, $maids_sql);

if ($maids_result) { 
    while ($maid = mysqli_fetch_assoc($maids_result)) {
        $maids[] = $maid; 
    }
} else {
    echo "Error fetching maids: " . mysqli_error($conn); 
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="shortcut icon" href="img/favicon.png" type="">
    <title>MYKAKAKS</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
    <link href="css/mykakaks-admin.css" rel="stylesheet">
	<link href="css/booking.css" rel="stylesheet">
</head>
<body id="page-top">

    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                  <img src="img/logo.png" class="sidebar-logo">
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Menu
            </div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>User Management</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="maid.php">Maid</a>
                        <a class="collapse-item" href="customer.php">Customer</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-cubes"></i>
                    <span>Content Management</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="service.php">Services</a>
                    </div>
                </div>
            </li>
            <li class="nav-item active">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseService"
                    aria-expanded="true" aria-controls="collapseService">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Service Status</span>
                </a>
                <div id="collapseService" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item active" href="booking.html">Book Services</a>
                    </div>
                </div>
            </li>
            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Others
            </div>
            <li class="nav-item">
                <a class="nav-link" href="feedback.php">
                    <i class="fas fa-fw fa-star"></i>
                    <span>Feedback</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="salesreport.php">
                    <i class="fas fa-fw fa-download"></i>
                    <span>Generate Report</span></a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle" onclick="toggleSidebar()"></button>
            </div>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
						<li class="nav-item">
							<a class="nav-link" href="#" id="alertsDropdown" data-toggle="modal" data-target="#alertsModal">
								<i class="fas fa-bell fa-fw"></i>
								<span class="badge badge-danger badge-counter"><?php echo count($alerts) > 0 ? count($alerts) : ''; ?>+</span>
							</a>
						</li>
						<div class="modal fade" id="alertsModal" tabindex="-1" role="dialog" aria-labelledby="alertsModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="alertsModalLabel">Alerts Center</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<?php foreach ($alerts as $alert): ?>
											<div class="alert-item">
												<div class="d-flex align-items-center">
													<div class="icon-circle bg-success mr-3">
														<i class="fas fa-check-circle text-white"></i>
													</div>
													<div>
														<div class="small text-gray-500"><?php echo date('F d, Y', strtotime($alert['booking_date'])); ?></div>
														<span class="font-weight-bold"><?php echo $alert['customer_name']; ?> has completed a booking with maid <?php echo $alert['maid_name']; ?>.</span>
														<p>Booking Slot: <?php echo $alert['booking_slot']; ?> | Total Price: $<?php echo number_format($alert['total_price'], 2); ?></p>
													</div>
												</div>
											</div>
											<hr>
										<?php endforeach; ?>
										<?php if (count($alerts) == 0): ?>
											<div class="alert-item">
												<div class="d-flex align-items-center">
													<div class="icon-circle bg-warning mr-3">
														<i class="fas fa-info-circle text-white"></i>
													</div>
													<div>
														<span>No completed transactions yet.</span>
													</div>
												</div>
											</div>
										<?php endif; ?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">ADMIN&nbsp;</span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
								<a class="dropdown-item" href="change-password.php">
                                   <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Change Password
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>
                </nav>
				<div class="container-fluid">
					<h1 class="h3 mb-1 text-gray-800">Manage Bookings</h1>
					<p class="mb-4"></p>
					<div class="appointments-container">
						<?php while ($booking = mysqli_fetch_assoc($result)): ?>
						<div class="appointment-card">
							<div class="appointment-header">
								<span class="doctor-name">
									<i class="fas fa-user-circle" style="margin-right: 5px; color: #000;"></i>
									<?php echo $booking['customer_fname'] . ' ' . $booking['customer_lname']; ?>
								</span>
								<span class="status <?php echo $booking['booking_status'] == 'ongoing' ? 'ongoing' : ''; ?>">
									<?php echo ucfirst($booking['booking_status']); ?>
								</span>
							</div>
							<span class="service-name" style="color: blue;"><?php echo $booking['service_name']; ?></span>
							<div class="appointment-info">
								<div class="time">
									<span class="icon"></span> <?php echo $booking['booking_slot']; ?>
								</div>
							</div>
							<div class="date">
								<span class="icon"></span> <?php echo $booking['booking_date']; ?>
							</div>
							<div class="time">
								<span class="icon"></span> Maid Assigned: 
								<?php echo $booking['maid_fname'] ? $booking['maid_fname'] . ' ' . $booking['maid_lname'] : 'Not Assigned'; ?>
							</div>

							<div class="button-group">
								<?php if ($user_type == 'admin'): ?>
								<form action="booking.php" method="post" class="assign-maid">
									<select name="maid_id">
										<option value="">Select Maid</option>
										<?php foreach ($maids as $maid): ?>
										<option value="<?php echo $maid['id']; ?>" <?php echo ($maid['id'] == $booking['maid_id']) ? 'selected' : ''; ?>>
											<?php echo $maid['fname'] . ' ' . $maid['lname']; ?>
										</option>
										<?php endforeach; ?>
									</select>
									<input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
									<button type="submit" name="assign_maid">Assign Maid</button>
								</form>
								<?php endif; ?>
							</div>
						</div>
						<?php endwhile; ?>
					</div>
				</div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; MyKakaks Website 2024</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

<?php
if (isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $booking_status = $_POST['booking_status'];

    $update_sql = "UPDATE booking 
                   SET booking_status = ? 
                   WHERE booking_id = ? AND maid_id = ?";

    if ($stmt = mysqli_prepare($conn, $update_sql)) {
        mysqli_stmt_bind_param($stmt, 'sii', $booking_status, $booking_id, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo "<script>alert('Booking status updated successfully!');</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if (isset($_POST['assign_maid'])) {
    $booking_id = $_POST['booking_id'];
    $maid_id = $_POST['maid_id'];

    $assign_sql = "UPDATE booking 
                   SET maid_id = ? 
                   WHERE booking_id = ?";

    if ($stmt = mysqli_prepare($conn, $assign_sql)) {
        mysqli_stmt_bind_param($stmt, 'ii', $maid_id, $booking_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo "<script>
                alert('Maid assigned successfully!');
                window.location.href = '" . $_SERVER['PHP_SELF'] . "';
              </script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="js/sb-admin-2.min.js"></script>
	<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertCount = <?php echo isset($newAlertCount) ? $newAlertCount : 0; ?>; 
    const badge = document.querySelector('.badge-counter');
    
    if (alertCount > 0) {
        badge.textContent = alertCount > 3 ? '3+' : alertCount; 
    }
});
</script>

</body>
</html>
