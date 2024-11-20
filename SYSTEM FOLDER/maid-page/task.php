<?php
include '../connect-db.php';
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php'); 
    exit;
}

$email = $_SESSION['email'];
$sql = "SELECT id FROM maid WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($maid_id);
$stmt->fetch();
$stmt->close();

if (!$maid_id) {
    echo "Maid not found.";
    exit;
}

if (isset($_POST['complete_service']) && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $sql = "UPDATE booking SET booking_status = 'completed' WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    if ($stmt->execute()) {
        header('Location: task.php');
        exit;
    } else {
        echo "Error completing the service.";
    }
}
$sql = "
    SELECT 
        b.booking_id, c.fname, c.lname, c.address, c.cnum, s.name AS service_name, b.booking_slot, b.booking_date, b.booking_status
    FROM booking b
    JOIN customer c ON b.cust_id = c.id
    JOIN services s ON b.service_id = s.id
    WHERE b.booking_status = 'ongoing' AND b.maid_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $maid_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>MYKAKAKS</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/mykakaks-maid.css" rel="stylesheet">
	<link href="css/task.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body id="page-top">
	<div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                  <img src="img/logo.png" class="sidebar-logo">
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item ">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Menu
            </div>
            <li class="nav-item active">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-tasks"></i>
                    <span>Tasks</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
    				<div class="bg-white py-2 collapse-inner rounded">
        				<a class="collapse-item active" href="task.php">Service Schedules</a>
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
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">MAID'S</span>
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

				<div class="container-fluid" style="padding-left:20px;">
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h3 mb-0 text-gray-800">Task to Complete</h1>
					</div>
					<div class="appointments-container">
						<?php while ($row = $result->fetch_assoc()): ?>
						<div class="appointment-card">
							<div class="appointment-header">
								<span class="customer-name">
									<i class="fas fa-user-circle" 
										style="cursor: pointer; margin-right: 5px; color: #000;" 
										onclick="showCustomerDetails(
											'<?php echo addslashes($row['fname']); ?>',
											'<?php echo addslashes($row['lname']); ?>',
											'<?php echo addslashes($row['address'] ?? 'N/A'); ?>',
											'<?php echo addslashes($row['cnum'] ?? 'N/A'); ?>'
										)">
									</i>
									<?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?>
								</span>
								<span class="status ongoing">Ongoing</span>
							</div>
							<span class="service-name" style="color: blue;"><?php echo htmlspecialchars($row['service_name']); ?></span>
							<div class="appointment-info">
								<div class="time">
									<span class="icon"></span> <?php echo htmlspecialchars($row['booking_slot']); ?>
								</div>
							</div>
							<div class="date">
								<span class="icon"></span> <?php echo htmlspecialchars($row['booking_date']); ?>
							</div>
							<div class="button-group">
								<form method="POST" action="task.php">
									<input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
									<input type="checkbox" name="complete_service" value="1" onchange="this.form.submit()"> Mark as Completed
								</form>
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
	
	<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="customerModalLabel">Customer Details</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p><strong>First Name:</strong> <span id="customerFName"></span></p>
					<p><strong>Last Name:</strong> <span id="customerLName"></span></p>
					<p><strong>Address:</strong> <span id="customerAddress"></span></p>
					<p><strong>Phone:</strong> <span id="customerPhone"></span></p>
				</div>
			</div>
		</div>
	</div>

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
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
	<script>
    function showCustomerDetails(fname, lname, address, phone) {
        document.getElementById("customerFName").textContent = fname;
        document.getElementById("customerLName").textContent = lname;
        document.getElementById("customerAddress").textContent = address;
        document.getElementById("customerPhone").textContent = phone;

        $('#customerModal').modal('show');
    }
	</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
