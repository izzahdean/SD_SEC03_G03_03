<?php
session_start();
include '../connect-db.php';

if ($conn) {
    $stmt = $conn->prepare("
        SELECT feedback.service_name, feedback.comments, feedback.date_submitted, customer.fname, customer.lname
        FROM feedback
        INNER JOIN customer ON feedback.customer_email = customer.email
        ORDER BY feedback.date_submitted DESC
    ");
    $stmt->execute();
    $feedback_result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="keywords" content="cleaning services, feedback" />
  <meta name="description" content="Read feedback from our satisfied customers" />
  <meta name="author" content="MyKakaks" />
  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

  <title>MyKakaks</title>

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

<body class="sub_page">

  <div class="hero_area">

    <div class="hero_bg_box">
      <div class="bg_img_box">
        <img src="images/hero-bg.png" alt="Background">
      </div>
    </div>

    <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.php">
            <span>
              <img src="images/logo.png" alt="MyKakaks Logo">
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
			aria-expanded="false" aria-label="Toggle navigation">
            <span class=""></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="service.php">Services</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="feedback.php">Feedback<span class="sr-only">(current)</span></a>
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
  </div>

	<section class="service_section layout_padding">
		<div class="service_container">
			<div class="container">
				<div class="heading_container heading_center">
					<h2>
						Customer <span>Feedback</span>
					</h2>
					<p>See what our happy customers have to say about us!</p>
				</div>
				<div class="row">
					<?php
					if ($feedback_result && $feedback_result->num_rows > 0) {
						while ($row = $feedback_result->fetch_assoc()) {
							$customerName = htmlspecialchars($row['fname'] . ' ' . $row['lname']);
							$serviceName = htmlspecialchars($row['service_name']);
							$comments = htmlspecialchars($row['comments']);
                        
							echo '<div class="col-md-4">';
							echo '  <div class="box shadow">';
							echo '    <div class="img-box">';
							echo '      <img src="images/cust.png" alt="Customer Feedback">';
							echo '    </div>';
							echo '    <div class="detail-box">';
							echo '      <h5>' . $serviceName . '</h5>';  // Display the service name
							echo '      <p>"' . $comments . '"</p>';     // Display the customer comment
							echo '      <p><small>By: ' . $customerName . '</small></p>';  // Display the customer's name
							echo '    </div>';
							echo '  </div>';
							echo '</div>';
						}
					} else {
						echo '<p>No feedback available yet.</p>';
					}
					?>
				</div>
			</div>
        
			<div class="btn-box">
				<a href="index.php">
					Back
				</a>
			</div>
		</div>
	</section>
  
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
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <script type="text/javascript" src="js/custom.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap"></script>
</body>

</html>
