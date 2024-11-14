<?php
session_start();

include '../connect-db.php';

if (isset($_GET['delete_id'])) {
    $customerId = $_GET['delete_id'];

    $emailSql = "SELECT email FROM customer WHERE id = ?";
    $emailStmt = $conn->prepare($emailSql);
    $emailStmt->bind_param("i", $customerId);
    $emailStmt->execute();
    $emailResult = $emailStmt->get_result();
    
    if ($emailResult->num_rows > 0) {
        $row = $emailResult->fetch_assoc();
        $customerEmail = $row['email'];

        $deleteCustomerSql = "DELETE FROM customer WHERE id = ?";
        $customerStmt = $conn->prepare($deleteCustomerSql);
        $customerStmt->bind_param("i", $customerId);
        $customerStmt->execute();

        $deleteUserSql = "DELETE FROM users WHERE email = ?";
        $userStmt = $conn->prepare($deleteUserSql);
        $userStmt->bind_param("s", $customerEmail);
        $userStmt->execute();

        if ($customerStmt->affected_rows > 0 && $userStmt->affected_rows > 0) {
            echo "<script>alert('Customer and corresponding user deleted successfully.'); window.location.href = 'customer.php';</script>";
        } else {
            echo "<script>alert('Error deleting customer or user: " . $conn->error . "');</script>";
        }

        $customerStmt->close();
        $userStmt->close();
    } else {
        echo "<script>alert('Customer not found.');</script>";
    }
    
    $emailStmt->close();
}

$sql = "SELECT id, fname, lname, email, address, status FROM customer";
$result = $conn->query($sql);
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

            <li class="nav-item">
                <a class="nav-link" href="index.html">
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
                    <i class="fas fa-fw fa-cog"></i>
                    <span>User Management</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="maid.php">Maid</a>
                        <a class="collapse-item active" href="customer.php">Customer</a>
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
                        <a class="collapse-item" href="booking.html">Book Services</a>
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
                <a class="nav-link" href="salesreport.html">
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
								<span class="badge badge-danger badge-counter">3+</span>
							</a>
						</li>


						<!-- Alerts Modal -->
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
										<!-- First Alert -->
										<div class="alert-item">
											<div class="d-flex align-items-center">
												<div class="icon-circle bg-primary mr-3">
													<i class="fas fa-file-alt text-white"></i>
												</div>
												<div>
													<div class="small text-gray-500">December 12, 2019</div>
													<span class="font-weight-bold">A new monthly report is ready to download!</span>
												</div>
											</div>
										</div>
										<hr>
										<!-- Second Alert -->
										<div class="alert-item">
											<div class="d-flex align-items-center">
												<div class="icon-circle bg-success mr-3">
													<i class="fas fa-donate text-white"></i>
												</div>
												<div>
													<div class="small text-gray-500">December 7, 2019</div>
													$290.29 has been deposited into your account!
												</div>
											</div>
										</div>
										<hr>
										<!-- Third Alert -->
										<div class="alert-item">
											<div class="d-flex align-items-center">
												<div class="icon-circle bg-warning mr-3">
													<i class="fas fa-exclamation-triangle text-white"></i>
												</div>
												<div>
													<div class="small text-gray-500">December 2, 2019</div>
													Spending Alert: We've noticed unusually high spending for your account.
												</div>
											</div>
										</div>
										<hr>
										<!-- Fourth Alert -->
										<div class="alert-item">
											<div class="d-flex align-items-center">
												<div class="icon-circle bg-success mr-3">
													<i class="fas fa-donate text-white"></i>
												</div>
												<div>
													<div class="small text-gray-500">January 1, 2019</div>
													$50.00 has been deposited into your account!
												</div>
											</div>
										</div>
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
                        <h1 class="h3 mb-0 text-gray-800">MyKakaks Customer</h1>
                    </div>
					<div class="container-fluid">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">List of Customer&nbsp;</h6>
							</div>
					
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Address</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Display fetched data in the table
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row['fname']) . " " . htmlspecialchars($row['lname']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                                    echo "<td>
													    <a href='edit-cust.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'><i class='fas fa-edit'></i></a>
                                                        <a href='customer.php?delete_id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this customer?');\" class='btn btn-danger btn-sm'>
                                                            <i class='fas fa-trash'></i>
														</a>
                                                      </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>No customers found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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

    <script src="vendor/chart.js/Chart.min.js"></script>

    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>