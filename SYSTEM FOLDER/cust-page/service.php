<?php
session_start();
include '../connect-db.php';

if ($conn) {
    $stmt = $conn->prepare("SELECT * FROM services WHERE status = ?");
    $status = 0;
    $stmt->bind_param("i", $status);
    $stmt->execute();
    $result = $stmt->get_result();
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
	<title> MyKakaks</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
	<link href="css/font-awesome.min.css" rel="stylesheet" />
	<link href="css/style.css" rel="stylesheet" />
	<link href="css/responsive.css" rel="stylesheet" />
	<style>
	.shadow{
		box-shadow: 2px 3px 18px 3px rgba(0, 32, 74, 0.9);
	}
		.floating-container {
		position: fixed;
		bottom: 20px;
		right: 20px;
		z-index: 1000;
		}
		.floating-button {
		background-color: #007bff;
		color: white;
		padding: 10px 20px;
		border-radius: 50px;
		text-align: center;
		box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px; /* Spacing between text and icon */
		transition: background-color 0.3s ease;
		}
		.floating-button:hover {
		background-color: #0056b3;
		}
		.icon {
		font-size: 1.2em; /* Adjust icon size */
		font-weight: bold;
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
					<a class="navbar-brand" href="index.php">
						<span>
							<img src="images/logo.png">
						</span>
					</a>

					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class=""> </span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav  ">
							<li class="nav-item ">
								<a class="nav-link" href="index.php">Home </a>
							</li>
							<li class="nav-item active">
								<a class="nav-link" href="service.php">Services<span class="sr-only">(current)</span></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="feedback.php">Feedback</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="about.html"> About Us</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="contact-us.html">Contact Us</a>
							</li>
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
									<a class="dropdown-item" href="change-password.php">
										<img src="images/pass.png" style="width: 20px; height: 20px;">
										<i class="mr-2 text-gray-400"></i>
										Change Password
									</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="../login.php" data-toggle="modal" data-target="#logoutModal">
										<img src="images/logout.png" style="width: 20px; height: 20px;">
										<i class="mr-2 text-gray-400"></i>
										Logout
									</a>
								</div>
							</li>
							<div class="topbar-divider d-none d-sm-block"></div>
							<li class="nav-item">
								<a class="nav-link" href="booking.php">
									<i class="fas fa-shopping-cart" style='font-size:20px;color:white' aria-hidden="true"></i>
								</a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
	</div>

	<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

	<section class="service_section layout_padding">
		<div class="service_container">
			<div class="container">
				<div class="heading_container heading_center">
					<h2>Our <span>Services</span></h2>
					<p>The cleaning services we offer come in different varieties.</p>
				</div>
				<div class="row">
					<?php if (isset($result) && $result->num_rows > 0): ?>
					<?php while ($row = $result->fetch_assoc()): ?>
					<div class="col-md-4">
						<div class="box shadow">
							<div class="img-box">
								<?php
									$image_url = "http://localhost/master/uploads/" . htmlspecialchars($row['image']);
								?>
								<img src="<?php echo $image_url; ?>" alt="Service Image for <?php echo htmlspecialchars($row['name']); ?>">
							</div>
							<div class="detail-box">
								<h5><?php echo htmlspecialchars($row['name']); ?></h5>
								<p><?php echo htmlspecialchars($row['description']); ?></p>
								<p><i> RM <?php echo htmlspecialchars($row['price']); ?>/session</i></p>
							</div>
						</div>
					</div>
					<?php endwhile; ?>
					<?php else: ?>
					<p>No services available at the moment.</p>
					<?php endif; ?>
				</div>
				<div class="btn-box">
					<a href="index.php">Back</a>
				</div>
			</div>
		</div>
	</section>
	
	<div class="floating-container">
		<a class="active" href="booking.php">
        <div class="floating-button">Book Now <span class="icon">&gt;</span></div>       
        </a>
	</div>

	<section class="info_section layout_padding2">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-lg-3 info_col">
					<div class="info_contact">
						<h4>
							Address
						</h4>
						<div class="contact_link_box">
							<a href="">
								<i class="fa fa-map-marker" aria-hidden="true"></i>
								<span>
									Location<br>
									Office 2, Ground Floor, Chan Sow Lin,
									55200 Kuala Lumpur
								</span>
							</a>
							<a href="">
								<i class="fa fa-phone" aria-hidden="true"></i>
								<span>
									Call +03-84085545
								</span>
							</a>
							<a href="">
								<i class="fa fa-envelope" aria-hidden="true"></i>
								<span>
									info@mykakaks.com
								</span>
							</a>
						</div>
					</div>
					<div class="info_social">
						<a href="https://www.facebook.com/share/PD2qhTrcmcLgzUrr/?mibextid=LQQJ4d">
							<i class="fa fa-facebook" aria-hidden="true"></i>
						</a>
						<a href="https://api.whatsapp.com/send?phone=60198885313&text=Hi%20MyKakaks,%20I%20want%20to%20book%20an%20appointment.">
							<i class="fa fa-whatsapp" aria-hidden="true"></i>
						</a>
						<a href="https://www.instagram.com/mykakaks.hq/">
							<i class="fa fa-instagram" aria-hidden="true"></i>
						</a>
					</div>
				</div>
				<div class="col-md-6 col-lg-2 mx-auto info_col">
					<div class="info_link_box">
						<h4>
							Links
						</h4>
						<div class="info_links">
							<a class="active" href="index.php">
								Home
							</a>
							<a class="" href="service.html">
								Services
							</a>
							<a class="" href="feedback.php">
								Feedback
							</a>
							<a class="" href="about.html">
								About Us
							</a>
							<a class="" href="contact-us.html">
								Contact Us
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
  
	<section class="footer_section">
		<div class="container">
			<p>
				&copy; <span id="displayYear"></span> All Rights Reserved By
				<a href="https://html.design/">MyKakaks</a>
			</p>
		</div>
	</section>
	<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>
