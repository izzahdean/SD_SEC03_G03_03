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

  <title> MyKakaks </title>

  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  <link href="css/responsive.css" rel="stylesheet" />
  
	<style>
	.shadow{
		box-shadow: 
		3px 7px 7px 1px rgba(30, 64, 106, 1);
	}
	</style>
</head>

<body>

	<div class="hero_area">
		<div class="hero_bg_box">
			<div class="bg_img_box">
				<img src="images/hero-bg.png" alt="">
			</div>
		</div>


		<header class="header_section">
			<div class="container-fluid">
				<nav class="navbar navbar-expand-lg custom_nav-container">
					<a class="navbar-brand" href="index.php">
						<span>
							<img src="images/logo.png" alt="Logo">
						</span>
					</a>

					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class=""> </span>
					</button>

					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav">
							<li class="nav-item active">
								<a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="service.php">Services</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="feedback.html">Feedback</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="about.html">About Us</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="contact-us.html">Contact Us</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="../register.php">
									<i class="fa fa-user" aria-hidden="true"></i> Sign Up
								</a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>

		<section class="slider_section ">
			<div id="customCarousel1" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner">
					<div class="carousel-item active">
						<div class="container ">
							<div class="row">
								<div class="col-md-6 ">
									<div class="detail-box">
										<h1>
											Professional <br>
											Cleaners
										</h1>
										<p>
											MyKakaks offers a professional house cleaning service in Kuala Lumpur, with a seamless online booking system that lets you schedule cleanings anytime, anywhere. Our website makes it easy to choose the service you need, set an appointment, and securely pay—all from the comfort of your home or while on the move, giving you complete flexibility and peace of mind.
										</p>
										<div class="btn-box">
											<button class="nav-link btn btn-primary" data-toggle="modal" data-target="#signupModal">Book Now</button>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="img-box">
										<img src="images/maid.png" alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="carousel-item ">
						<div class="container ">
							<div class="row">
								<div class="col-md-6 ">
									<div class="detail-box">
										<h1>
											Booking anytime, anywhere
										</h1>
										<p>
											The cleaning service you can trust. Since 2014
										</p>
									</div>
								</div>
								<div class="col-md-6">
									<div class="img-box">
										<img src="images/girl.png" alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<ol class="carousel-indicators">
					<li data-target="#customCarousel1" data-slide-to="0" class="active"></li>
					<li data-target="#customCarousel1" data-slide-to="1"></li>
				</ol>
			</div>
		</section>
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
						<?php 
							$counter = 0; 
							while ($row = $result->fetch_assoc()): 
								if ($counter >= 3) 
							break; 
						?>
						<div class="col-md-4">
							<div class="box">
								<div class="img-box">
									<img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Service Image for <?php echo htmlspecialchars($row['name']); ?>">
								</div>
								<div class="detail-box">
									<h5><?php echo htmlspecialchars($row['name']); ?></h5>
									<p><?php echo htmlspecialchars($row['description']); ?></p>
								</div>
							</div>
						</div>
						<?php 
							$counter++;
							endwhile; 
						?>
						<?php else: ?>
							<p>No services available at the moment.</p>
						<?php endif; ?>
					
				</div>
				<div class="btn-box">
					<a href="service.php">View More</a>
				</div>
			</div>
		</div>
	</section>


  <!-- feedback section -->
	<section class="service_section layout_padding">
		<div class="service_container">
			<div class="container ">
				<div class="heading_container heading_center">
					<h2>
					  Customer <span>Feedback</span>
					</h2>
					<p>See what our happy customers have to say about us!</p>
				</div>
				<div class="row">
					<div class="col-md-4 ">
						<div class="box shadow">
							<div class="img-box">
								<img src="images/cust.png" alt="Expert Management">
							</div>
							<div class="detail-box">
								<h5>Expert Management</h5>
								<p>
									"The team is extremely professional and managed our needs efficiently. Highly recommend for anyone looking for trustworthy cleaning services."
								</p>
							</div>
						</div>
					</div>
			
					<div class="col-md-4 ">
						<div class="box shadow">
							<div class="img-box">
								<img src="images/cust.png" alt="Secure Investment">
							</div>
							<div class="detail-box">
								<h5>Excellent Service</h5>
								<p>
									"MyKakaks provides excellent service. Their commitment to quality has been consistent, making it a great investment."
								</p>
							</div>
						</div>
					</div>
			
					<div class="col-md-4 ">
						<div class="box shadow">
							<div class="img-box">
								<img src="images/cust.png" alt="Instant Trading">
							</div>
							<div class="detail-box">
								<h5>Instant Booking</h5>
								<p>
									"Booking a cleaning session is quick and easy. They’re reliable and always deliver top-notch service."
								</p>
							</div>
						</div>
					</div>
			
					<div class="col-md-4 ">
						<div class="box shadow">
							<div class="img-box">
								<img src="images/cust.png" alt="Happy Customers">
							</div>
							<div class="detail-box">
								<h5>Happy Customers</h5>
								<p>
									"Our home has never felt this clean! The MyKakaks team goes above and beyond. We’re very happy with their service."
								</p>
							</div>
						</div>
					</div>
				</div> 	
			</div> 
	  
			<div class="btn-box">
				<a href="feedback.html">
					Read More
				</a>
			</div>
		</div>
	</section>
	
	<section class="about_section layout_padding">
		<div class="container  ">
			<div class="heading_container heading_center">
				<h2>
					About <span>Us</span>
				</h2>
				<p>
          
				</p>
			</div>
			<div class="row">
				<div class="box shadow">
					<div class="col-md-6 ">
						<div class="img-box">
							<img src="images/abtus.jpg" alt="">
						</div>
					</div>
					<div class="col-md-6">
						<div class="detail-box">
							<p>
								At MyKakaks, we take pride in delivering top-quality cleaning services that make your space shine. Our professional
								team is committed to ensuring a spotless and hassle-free experience, every time.
							</p>
							<a href="about.html">
								Read More
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="team_section layout_padding">
		<div class="container-fluid">
			<div class="heading_container heading_center">
				<h2 class="">
					Contact <span> Us</span>
				</h2>
				<div class="team_container">
					<div class="row">
						<div class="detail-box">
							<div class="responsive-container-block container">
								<div class="info_contact heading_container heading_center"><br>
									<h4>
										Our Address
									</h4>
									<div class="contact_link_box">
										<a href="">
											<i class="fa fa-map-marker" aria-hidden="true"></i>
											<span>
												Location<br>
												Office 2, Ground Floor, Chan Sow Lin,
												55200 Kuala Lumpur
											</span>
										</a><br>
										<a href="">
											<i class="fa fa-phone" aria-hidden="true"></i>
											<span>
												Call +03-84085545
											</span>
										</a><br>
										<a href="">
											<i class="fa fa-envelope" aria-hidden="true"></i>
											<span>
												info@mykakaks.com
											</span>
										</a><br>	
									</div>	
								</div><br>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
	</section>
	
	<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="signupModalLabel">Signup or Login to Book</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Please login or create an account to book this service.</p>
					<a href="login.php" class="btn btn-primary">Login</a>
					<a href="../register.php" class="btn btn-secondary">Sign Up</a>
				</div>
			</div>
		</div>
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
							<a class="" href="service.php">
								Services
							</a>
							<a class="" href="feedback.html">
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
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
	</script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
	</script>
	<script type="text/javascript" src="js/custom.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
	</script>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>