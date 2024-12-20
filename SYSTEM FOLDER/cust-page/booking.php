<?php
session_start();
include '../connect-db.php';

$service_id = $service_name = $total_price = $booking_date = $slot = $fname = $lname = $cnum = $address = '';
$customer_email = $_SESSION['email'] ?? null;

if ($customer_email) {
    $sql = "SELECT fname, lname, cnum, address FROM customer WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $customer_email);
        $stmt->execute();
        $stmt->bind_result($fname, $lname, $cnum, $address);
        $stmt->fetch();
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = intval($_POST['service_id']);
    $booking_date = $_POST['date'];
    $slot = $_POST['slot'];

    $sql = "SELECT price, name FROM services WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $stmt->bind_result($total_price, $service_name);
        $stmt->fetch();
        $stmt->close();
    }

    $sql = "INSERT INTO booking (cust_id, service_id, booking_date, booking_slot, total_price) 
            SELECT id, ?, ?, ?, ? FROM customer WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
		$stmt->bind_param("issds", $service_id, $booking_date, $slot, $total_price, $customer_email);
        if ($stmt->execute()) {
            $stmt->close();

            $_SESSION['booking_details'] = [
                'service_name' => $service_name,
                'booking_date' => $booking_date,
                'slot' => $slot,
                'total_price' => $total_price,
            ];

            header("Location: payment.php");
            exit;
        } else {
            $message = "Error in booking. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/favicon.png" type="">
  <title> MyKakaks </title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <link href="csscheckout/style.css" rel="stylesheet" />
  <link href="css/responsive.css" rel="stylesheet" /> 
  <style>
    .submitbutton {
      font-family: "Open Sans", sans-serif;
      color: #fff !important;
      font-size: 16px;
      text-shadow: 0px 0px 0px #7CACDE;
      padding: 10px 25px;
      border-radius: 20px;
      border: 0px solid #3866A3;
      background: #00bbf0;
    }

    .submitbutton:hover {
      color: #fff !important;
      background: #224abe;
    }
	.topbar .topbar-divider {
	  width: 0;
	  border-right: 1px solid #e3e6f0;
	  height: calc(4.375rem - 2rem);
	  margin: auto 1rem;
	  color: #fff !important;
	}
	.nav-link:hover .fa-shopping-cart {
    color: #00bbf0;
	}
  </style>
</head>

<body class="sub_page">

  <div class="hero_area">
    <div class="hero_bg_box">
      <div class="bg_img_box">
        <img src="images/hero-bg.png" alt="">
      </div>
    </div>

    <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.html">
            <span>
              <img src="images/logo.png">
            </span>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
              <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="service.php">Services</a></li>
              <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
              <li class="nav-item"><a class="nav-link" href="about.html">About Us</a></li>
              <li class="nav-item"><a class="nav-link" href="contact-us.html">Contact Us</a></li>
			  
			  <li class="nav-item dropdown no-arrow">
			        <div class="topbar-divider d-none d-sm-block"></div>
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline">USER&nbsp;</span>
                                <img class="nav-item img-profile rounded-circle" src="">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profile.php">
								<img src="images/user.png" style="width: 20px; height: 20px;">
                                    <i class="mr-2 text-gray-400"></i>
                                    Profile
                                </a>
								<a class="dropdown-item" href="bookinghistory.php">
									<img src="images/Booking.png" style="width: 20px; height: 20px;">
									<i class="mr-2 text-gray-400"></i>
									Booking History
								</a>
								<a class="dropdown-item" href="change-password.html">
								<img src="images/pass.png" style="width: 20px; height: 20px;">
                                   <i class="mr-2 text-gray-400"></i>
                                    Change Password
                                </a>
                                <div class="dropdown-divider"></div>
								
                                <a class="dropdown-item" href="login.html" data-toggle="modal" data-target="#logoutModal">
                                    <img src="images/logout.png" style="width: 20px; height: 20px;">
									<i class="mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                </li>
				<div class="topbar-divider d-none d-sm-block"></div>
				<li class="nav-item active"><a class="nav-link" href="checkout-page.php">
					<i class="fas fa-shopping-cart" style='font-size:20px;color:white' aria-hidden="true"></i>
					</a>
				</li>
            </ul>
          </div>
        </nav>
      </div>
    </header>
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
	
	<section class="team_section layout_padding">
		<div class="container-fluid">
			<div class="heading_container heading_center">
				<h2>Booking <span>Details</span></h2>
			</div>
			<div class="team_container">
				<div class="row">  
					<div class="col-lg-7 col-sm-6">
						<div class="box">
							<div class="detail-box">
								<div class="map-part">
									<h4>Service Detail</h4><br>
									<p class="text-blk" id="w-c-s-fc_p-1-dm-id">
										<form method="POST" action="booking.php">
											<div class="form-group row">
												<div class="col-md-6">
													<label for="service">Select Service</label>
													<select id="service" name="service_id" class="form-control" required>
														<?php
															$sql = "SELECT id, name FROM services";
															$result = $conn->query($sql);
															while ($row = $result->fetch_assoc()) {
																echo "<option value='{$row['id']}'>{$row['name']}</option>";
															}
														?>
													</select>
												</div>
											</div>
											<br>
											<div class="form-group row">
												<div class="col-sm-6 mb-3 mb-sm-0">
													<label for="date">Select Date</label>
													<input type="date" class="form-control" id="date" name="date" required min="<?= date('Y-m-d'); ?>">
												</div>
												<div class="col-sm-6 mb-3 mb-sm-0">
													<label for="slot">Select Time Slot</label>
													<select id="slot" name="slot" class="form-control">
														<option value="8:00 a.m">8:00 AM</option>
														<option value="10:00 a.m">10:00 AM</option>
														<option value="12:00 p.m">12:00 PM</option>
													<option value="2:00 p.m">2:00 PM</option>
													</select>
												</div>
											</div>
											<br>
											<hr style="background-color:white">
											<br>
											<h4>Customer Detail</h4><br>
											<div class="form-group row">
												<div class="col-sm-6 mb-3 mb-sm-0">
													<label for="fname">First Name</label>
													<input type="text" class="form-control contact-inputs" id="fname" name="fname" value="<?php echo $fname; ?>" readonly>
												</div>
												<div class="col-sm-6 mb-3 mb-sm-0">
													<label for="lname">Last Name</label>
													<input type="text" class="form-control contact-inputs" id="lname" name="lname" value="<?php echo $lname; ?>" readonly>
												</div>
											</div>
											<br>
											<div class="form-group row">
												<div class="col-sm-6 mb-3 mb-sm-0">
													<label for="cnum">Phone Number</label>
													<input type="tel" class="form-control contact-inputs" id="cnum" name="cnum" value="<?php echo $cnum; ?>" readonly>
												</div>
												<div class="col-sm-6 mb-3 mb-sm-0">
													<label for="address">Adress</label>
													<input type="text" class="form-control contact-inputs" id="address" name="address" value="<?php echo $address; ?>" readonly>
												</div>
											</div>
											<div id="paypal-button-container"></div>
											<br>
											<button type="submit" class="btn btn-primary mt-3 submitbutton">Proceed</button>
										</form>
										<?php if (isset($message)): ?>
										<div class="alert alert-warning"><?= htmlspecialchars($message); ?></div>
										<?php endif; ?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
				
	<section class="footer_section">
    <div class="container">
      <p>&copy; <span id="displayYear"></span> All Rights Reserved By <a href="https://html.design/">MyKakaks</a></p>
    </div>
  </section>
  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
</body>
</html>
