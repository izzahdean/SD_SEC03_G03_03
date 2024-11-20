<?php
session_start();
include '../connect-db.php';

$sql = "SELECT service_id, COUNT(*) AS service_count
        FROM booking
        WHERE booking_status IN ('completed', 'ongoing')
        GROUP BY service_id";
$result = $conn->query($sql);

$serviceCounts = [];
$totalBookings = 0;

while ($row = $result->fetch_assoc()) {
    $serviceCounts[$row['service_id']] = $row['service_count'];
    $totalBookings += $row['service_count'];  // Calculate total bookings
}

$servicesQuery = "SELECT id, name FROM services WHERE status = 0"; // Only visible services
$servicesResult = $conn->query($servicesQuery);

$services = [];
while ($row = $servicesResult->fetch_assoc()) {
    $services[$row['id']] = $row['name']; // Store service ID and name
}

$sql = "SELECT COUNT(*) AS total, 
               SUM(CASE WHEN booking_status = 'completed' THEN 1 ELSE 0 END) AS completed,
               SUM(CASE WHEN booking_status = 'ongoing' THEN 1 ELSE 0 END) AS ongoing
        FROM booking";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalBookings = $row['total'];
    $completedBookings = $row['completed'];
    $ongoingBookings = $row['ongoing'];

    if ($totalBookings > 0) {
        $completionPercentage = ($completedBookings / $totalBookings) * 100;
    } else {
        $completionPercentage = 0;
    }
} else {
    $completionPercentage = 0;
    $ongoingBookings = 0;
}

$currentMonth = date('m');
$currentYear = date('Y');

$sql = "SELECT SUM(b.amount) AS total_earnings
        FROM booking b
        JOIN services s ON b.service_id = s.id
        WHERE b.booking_status = 'completed'
        AND MONTH(b.booking_date) = '$currentMonth'
        AND YEAR(b.booking_date) = '$currentYear'";

$result = $conn->query($sql);
$totalEarnings = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalEarnings = $row['total_earnings'];
}

$sql = "SELECT SUM(b.amount) AS annual_earnings
        FROM booking b
        WHERE b.booking_status = 'completed'
        AND YEAR(b.booking_date) = '$currentYear'";

$result = $conn->query($sql);
$annualEarnings = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $annualEarnings = $row['annual_earnings'];
}

$servicePercentages = [];
foreach ($services as $id => $serviceName) {
    if ($totalBookings > 0 && isset($serviceCounts[$id])) {
        $servicePercentages[$id] = ($serviceCounts[$id] / $totalBookings) * 100;
    } else {
        $servicePercentages[$id] = 0;
    }
}

$sql = "SELECT b.booking_id, b.booking_date, b.booking_slot, b.total_price, b.amount, b.payment_method, c.fname AS customer_name, m.fname AS maid_name
        FROM booking b
        LEFT JOIN customer c ON b.cust_id = c.id
        LEFT JOIN maid m ON b.maid_id = m.id  -- Changed from 'maids' to 'maid'
        WHERE b.payment_status = 'completed' AND b.booking_status = 'completed'
        ORDER BY b.payment_date DESC LIMIT 5"; // Fetch the latest 5 completed bookings

$result = $conn->query($sql);

$alerts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alerts[] = $row;
    }
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
	<style>
	.sidebar-brand {
	  display: flex;
	  align-items: center;
	  justify-content: center;
	  transition: all 0.3s ease; /* Smooth transition for changes */
	}
	.sidebar-logo {
	  max-width: 700px;
	  max-height: 45px;
	  width: 100%;
	  height: auto;
	  transition: max-width 0.3s ease, max-height 0.3s ease;
	}
	#sidebarToggle:checked ~ .sidebar-brand .sidebar-logo {
	  max-width: 35px; 
	  max-height: 35px; 
	}
	</style>
</head>

<body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                  <img src="img/logo.png" class="sidebar-logo">
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="index.html">
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
			
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseService"
                    aria-expanded="true" aria-controls="collapseService">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Service Status</span>
                </a>
                <div id="collapseService" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="booking.php">Book Services</a>
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
										<!-- Loop through alerts to show completed bookings -->
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
												Earnings (Monthly)</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800">
												RM<?php echo number_format($totalEarnings, 2); ?></div>
										</div>
										<div class="col-auto">
											<i class="fas fa-calendar fa-2x text-gray-300"></i>
										</div>
									</div>
								</div>
							</div>
						</div>	
                        <div class="col-xl-3 col-md-6 mb-4">
							<div class="card border-left-success shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
												Earnings (Annual)</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800">
												RM<?php echo number_format($annualEarnings, 2); ?></div>
										</div>
										<div class="col-auto">
											<i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
										</div>
									</div>
								</div>
							</div>
						</div>

                        <div class="col-xl-3 col-md-6 mb-4">
							<div class="card border-left-info shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks</div>
											<div class="row no-gutters align-items-center">
												<div class="col-auto">
													<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
														<?php echo round($completionPercentage, 2); ?>%
													</div>
												</div>
												<div class="col">
													<div class="progress progress-sm mr-2">
														<div class="progress-bar bg-info" role="progressbar"
															style="width: <?php echo $completionPercentage; ?>%" 
															aria-valuenow="<?php echo $completionPercentage; ?>" 
															aria-valuemin="0" 
															aria-valuemax="100">
														</div>
													</div>
												</div>
											</div>
										</div>
											<div class="col-auto">
												<i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
											</div>
									</div>
								</div>
							</div>
						</div>

                        <div class="col-xl-3 col-md-6 mb-4">
							<div class="card border-left-warning shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
												Pending Requests
											</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800">
												<?php echo $ongoingBookings; ?>
											</div>
										</div>
										<div class="col-auto">
											<i class="fas fa-comments fa-2x text-gray-300"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
                    </div>
                    <div class="row">
						<div class="col-lg-6 col-xl-6">
							<div class="card shadow mb-4">
								<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
								</div>
								<div class="card-body">
									<div class="chart-area">
										<canvas id="myAreaChart"></canvas>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-6 col-xl-6 mb-4">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Service Request</h6>
								</div>
								<div class="card-body">
									<?php foreach ($services as $serviceId => $serviceName): ?>
										<h4 class="small font-weight-bold"><?php echo $serviceName; ?> <span class="float-right"><?php echo round($servicePercentages[$serviceId], 2); ?>%</span></h4>
										<div class="progress mb-4">
											<div class="progress-bar" role="progressbar" style="width: <?php echo $servicePercentages[$serviceId]; ?>%" aria-valuenow="<?php echo round($servicePercentages[$serviceId], 2); ?>" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
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
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const monthlyEarnings = <?php echo $totalEarnings; ?>;
    const annualEarnings = <?php echo $annualEarnings; ?>;

    const earningsData = {
        labels: ['Monthly Earnings', 'Annual Earnings'],
        datasets: [{
            label: 'Earnings',
            data: [monthlyEarnings, annualEarnings],
            backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)'],
            borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
            borderWidth: 1
        }]
    };

    const config = {
		type: 'bar',
		data: earningsData,
		options: {
			responsive: true, 
			maintainAspectRatio: false, 
			scales: {
				y: {
					beginAtZero: true
				}
			}
		}
	};	

    var myAreaChart = new Chart(document.getElementById('myAreaChart'), config);
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertCount = <?php echo $newAlertCount; ?>;
    const badge = document.querySelector('.badge-counter');
    
    if (alertCount > 0) {
        badge.textContent = alertCount + '+'; 
    }
});
</script>
</body>
</html>
</body>
</html>
