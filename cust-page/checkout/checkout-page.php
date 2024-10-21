<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/favicon.png" type="">

  <title> MyKakaks </title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
  
	<script>
    // Get today's date in YYYY-MM-DD format
    var today = new Date().toISOString().split('T')[0];
    // Set the minimum selectable date to today's date
    document.getElementById("service").setAttribute("min", today);
</script>
</head>

<body class="sub_page">

  <div class="hero_area">

    <div class="hero_bg_box">
      <div class="bg_img_box">
        <img src="images/hero-bg.png" alt="">
      </div>
    </div>

    <!-- header section strats -->
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
            <ul class="navbar-nav  ">
              <li class="nav-item ">
                <a class="nav-link" href="index.html">Home </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="service.html">Services</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="feedback.html">Feedback</a>
              </li>
			  <li class="nav-item">
                <a class="nav-link" href="about.html"> About Us</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="contact-us.html">Contact Us</a>
              </li>
			  <li class="nav-item dropdown no-arrow">
			        <div class="topbar-divider d-none d-sm-block"></div>
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline">USER&nbsp;</span>
                                <img class="nav-item img-profile rounded-circle" src="">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profile.php">
								<img src="images/user.png" style="width: 20px; height: 20px;">
                                    <i class="mr-2 text-gray-400"></i>
                                    Profile
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
            </ul>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->
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
	
  <!-- contact-us section -->
  <section class="team_section layout_padding">
    <div class="container-fluid">
      <div class="heading_container heading_center">
        <h2 class="">
          Booking <span> Details</span>
        </h2>
      </div>
      <div class="team_container">
        <div class="row">	
        <div class="col-lg-7 col-sm-6">
            <div class="box">
				  <div class="detail-box">
						<div class="map-part">
							<h4>Service Detail</h4><br>
							<p class="text-blk" id="w-c-s-fc_p-1-dm-id">
								
								<div class="form-group row">
										<div class="col-sm-6 mb-3 mb-sm-0">
											<label for="service" >Service Name</label>
											<input type="text" class="form-control contact-inputs" id="service" name="service" placeholder="service" readonly>
										</div>
										<div class="col-sm-6 mb-3 mb-sm-0">
										  <label for="session">Session</label><br>
										  <select id="session" name="session" class="form-control contact-inputs">
											<option value="one_maid">1 Maid</option>
											<option value="two_maid">2 Maids</option>
											<option value="three_maid">3 Maids</option>
											<option value="four_maid">4 Maids</option>
										  </select>
										</div>
								</div>
								<br>
								<div class="form-group row">
										<div class="col-sm-6 mb-3 mb-sm-0">
											<label for="date" >Select Date</label>
											<input type="date" class="form-control contact-inputs" id="date" name="date" placeholder="date" required min="<?= date('Y-m-d'); ?>">
										</div>
										<div class="col-sm-6 mb-3 mb-sm-0">
										  <label for="duration">Duration</label><br>
										  <select id="duration" name="duration" class="form-control contact-inputs">
											<option value="one_hour">1 Hours</option>
											<option value="two_hour">2 Hours</option>
											<option value="three_hour">3 Hours</option>
											<option value="four_hour">4 Hours</option>
										  </select>
										</div>
								</div>
								<br>
								<div class="form-group row">
									<div class="col-sm-6 mb-3 mb-sm-0">
										  <label for="slot">Select Slot</label><br>
										  <select id="slot" name="slot" class="form-control contact-inputs">
											<option value="eight">8:00 AM</option>
											<option value="nine">9:00 AM</option>
											<option value="ten">10:00 AM</option>
											<option value="twelve">12:00 PM</option>
											<option value="two">2:00 PM</option>
										  </select>
									</div>
									
								</div><br>
								<hr style="background-color:white">
								<br>
								<h4>Customer Detail</h4><br>
									<div class="form-group row">
										<div class="col-sm-6 mb-3 mb-sm-0">
												<label for="fname" >First Name</label>
												<input type="text" class="form-control contact-inputs col-sm-15" id="fname" name="fname" placeholder="Enter First Name" required>
										</div>
										<div class="col-sm-6 mb-3 mb-sm-0">
												<label for="lname" >Last Name</label>
												<input type="text" class="form-control contact-inputs col-sm-15" id="lname" name="lname" placeholder="Enter Last Name" required>
										</div>
									</div>	
								<br>
									<div class="form-group row">
										<div class="col-sm-6 mb-3 mb-sm-0">
												<label for="cnum" >Phone Number</label>
												<input type="tel" class="form-control contact-inputs col-sm-15" id="cnum" name="cnum" placeholder="Phone Number" required>
										</div>
										<div class="col-sm-6 mb-3 mb-sm-0">
												<label for="email" >Email</label>
												<input type="email" class="form-control contact-inputs col-sm-15" id="email" name="email" placeholder="Email Address" required>
										</div>
									</div>	
								<br>	
									<div class="form-group row">
										<div class="col-sm-6 mb-3 mb-sm-0">
												<label for="address" >Address</label>
												<input type="text" class="form-control contact-inputs col-sm-15" id="address" name="address" placeholder="Enter Your Address" required>
										</div>
										<div class="col-sm-6 mb-3 mb-sm-0">
												<label for="area" >Area</label>
												<select id="area" name="city" class="form-control contact-inputs">
													<option value="gombak">Gombak </option>
													<option value="wangsaMaju">Wangsa Maju</option>
													<option value="setiawangsa">Setiawangsa</option>
													<option value="gombakSetia">Gombak Setia</option>
												 </select>
										</div>
									</div><br>	
							</p>
						</div>
				  </div>
			</div>
		</div>
		
		<div class="col-lg-5 col-sm-6 ">
            <div class="box ">
              <div class="detail-box">
				  <div class="responsive-container-block container">
					<div class="info_contact">
							<h4>
							  Booking Summary
							  <hr style="background-color:white">
							</h4>
							<div class="contact_link_box">
								<label for="session" >Session :</label><br>
								<label for="duration" >Duration :</label><br>
								<hr style="background-color:white">
								<h4>Total :	</h4>
							</div>	
					</div><br>
				  </div>
              </div>
            </div>
			
			<div class="box ">
              <div class="detail-box">
				  <div class="responsive-container-block container">
					<div class="info_contact">
							<h4>
							  Payment Details
							  <hr style="background-color:white">
							</h4><br>
								<div class="contact_link_box">
									<div class="col-sm-6 mb-3 mb-sm-0">
											<input type="text" class="form-control contact-inputs col-sm-15" id="cardnum" name="cardnum" placeholder="Card Number" required>
									</div><br>
									<div class="col-sm-6 mb-3 mb-sm-0">
											<input type="text" class="form-control contact-inputs col-sm-15" id="cardname" name="cardname" placeholder="Name On Card" required>
									</div>
								</div><br>
								<div class="contact_link_box">
									<div class="form-group row">
												<div class="">
													<input type="date" class="form-control contact-inputs" id="date" name="date" placeholder="Valid Until" required>
												</div><br>
												<div class=""><br>
													<input type="text" class="form-control contact-inputs col-sm-15" id="cvc" name="cvc" placeholder="CVC" required> 
												</div>
									</div>	
								</div>	
					</div><br>
				  </div>
              </div>
            </div>
        </div>
  </section>
  <!-- end contact-us section -->

  <!-- info section -->

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
              <a class="active" href="index.html">
                Home
              </a>
              <a class="" href="service.html">
                Services
              </a>
              <a class="" href="feedback.html">
                Rating
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

  <!-- end info section -->

  <!-- footer section -->
  <section class="footer_section">
    <div class="container">
      <p>
        &copy; <span id="displayYear"></span> All Rights Reserved By
        <a href="https://html.design/">MyKakaks</a>
      </p>
    </div>
  </section>
  <!-- footer section -->

  <!-- jQery -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- custom js -->

</body>

</html>