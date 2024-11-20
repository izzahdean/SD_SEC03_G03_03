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

$query = "SELECT 
              COUNT(*) AS total_bookings,
              SUM(booking_status = 'completed') AS completed_bookings 
          FROM booking 
          WHERE maid_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $maid_id);
$stmt->execute();
$stmt->bind_result($total_bookings, $completed_bookings);
$stmt->fetch();
$stmt->close();

$percentage = $total_bookings > 0 ? ($completed_bookings / $total_bookings) * 100 : 0;

$query_pending = "SELECT COUNT(*) AS pending_requests 
                  FROM booking 
                  WHERE maid_id = ? AND booking_status = 'ongoing'";
$stmt = $conn->prepare($query_pending);
$stmt->bind_param("i", $maid_id);
$stmt->execute();
$stmt->bind_result($pending_requests);
$stmt->fetch();
$stmt->close();

$query_services = "SELECT 
                      services.name AS service_name, 
                      COUNT(*) AS service_count 
                   FROM booking 
                   INNER JOIN services ON booking.service_id = services.id
                   WHERE booking.maid_id = ? 
                   GROUP BY services.name";
$stmt = $conn->prepare($query_services);
$stmt->bind_param("i", $maid_id);
$stmt->execute();
$stmt->bind_result($service_name, $service_count);

$services_data = [];
$total_requests = 0; 
while ($stmt->fetch()) {
    $services_data[$service_name] = $service_count;
    $total_requests += $service_count; 
}
$stmt->close();

$query_all_services = "SELECT name FROM services WHERE status = 0"; 
$stmt = $conn->prepare($query_all_services);
$stmt->execute();
$stmt->bind_result($service_name_from_db);
$all_services = [];
while ($stmt->fetch()) {
    $all_services[] = $service_name_from_db;
}
$stmt->close();

$query_earnings = "SELECT SUM(total_price) AS monthly_earnings
                   FROM booking 
                   WHERE maid_id = ? 
                   AND booking_status = 'completed' 
                   AND MONTH(booking_date) = MONTH(CURRENT_DATE)
                   AND YEAR(booking_date) = YEAR(CURRENT_DATE)";

$stmt = $conn->prepare($query_earnings);
$stmt->bind_param("i", $maid_id); 
$stmt->execute();
$stmt->bind_result($monthly_earnings);
$stmt->fetch();
$stmt->close();

$monthly_earnings = $monthly_earnings ? $monthly_earnings : 0;

$query_earnings_overview = "SELECT 
                                DATE(booking_date) AS date, 
                                SUM(total_price) AS daily_earnings
                            FROM booking
                            WHERE maid_id = ? 
                            AND booking_status = 'completed' 
                            AND booking_date >= CURDATE() - INTERVAL 30 DAY
                            GROUP BY DATE(booking_date)
                            ORDER BY DATE(booking_date) ASC";

$stmt = $conn->prepare($query_earnings_overview);
$stmt->bind_param("i", $maid_id); 
$stmt->execute();
$stmt->bind_result($date, $daily_earnings);

$dates = [];
$earnings = [];
while ($stmt->fetch()) {
    $dates[] = $date;
    $earnings[] = $daily_earnings;
}
$stmt->close();

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
                    <i class="fas fa-fw fa-tasks"></i>
                    <span>Tasks</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
    				<div class="bg-white py-2 collapse-inner rounded">
        				<a class="collapse-item" href="task.php">Service Schedules</a>
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
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
							<div class="card border-left-success shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
												Earnings (Monthly)
											</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800">
												RM<?php echo number_format($monthly_earnings, 2); ?>
											</div>
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
											<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks (completed)</div>
											<div class="row no-gutters align-items-center">
												<div class="col-auto">
													<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
														<?php echo round($percentage); ?>%
													</div>
												</div>
												<div class="col">
													<div class="progress progress-sm mr-2">
														<div class="progress-bar bg-info" role="progressbar"
															style="width: <?php echo $percentage; ?>%" aria-valuenow="<?php echo round($percentage); ?>"
															aria-valuemin="0" aria-valuemax="100">
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
												<?php echo $pending_requests; ?>
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
									<?php
										foreach ($all_services as $service) {
											$count = isset($services_data[$service]) ? $services_data[$service] : 0;
											$progress = $total_requests > 0 ? ($count / $total_requests) * 100 : 0;
											echo "
											<h4 class='small font-weight-bold'>$service <span class='float-right'>$count Requests</span></h4>
											<div class='progress mb-4'>
												<div class='progress-bar' role='progressbar' style='width: $progress%' aria-valuenow='$progress' aria-valuemin='0' aria-valuemax='100'></div>
											</div>";
										}
									?>
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
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
var dates = <?php echo json_encode($dates); ?>;
var earnings = <?php echo json_encode($earnings); ?>;

var ctx = document.getElementById('myAreaChart').getContext('2d');
var myAreaChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: dates,
        datasets: [{
            label: 'Earnings (RM)',
            data: earnings,
            backgroundColor: 'rgba(28, 200, 138, 0.2)',
            borderColor: 'rgba(28, 200, 138, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,  
        maintainAspectRatio: false,  
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Date'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Earnings (RM)'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        }
    }
});
</script>
</body>
</html>