<?php
session_start();
include '../connect-db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$customer_email = $_SESSION['email'];

$sql = "SELECT id, fname, lname FROM customer WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customer_email);
$stmt->execute();
$stmt->bind_result($customer_id, $fname, $lname);
$stmt->fetch();
$stmt->close();

$sql = "SELECT b.booking_id, b.booking_date, b.booking_slot, s.name AS service_name, s.price 
        FROM booking b
        JOIN services s ON b.service_id = s.id
        WHERE b.cust_id = ? 
        ORDER BY b.booking_date DESC, b.booking_slot ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->bind_result($booking_id, $booking_date, $booking_slot, $service_name, $service_price);

$services = [];
$total_price = 0;

while ($stmt->fetch()) {
    $services[] = [
        'booking_id' => $booking_id,
        'service_name' => $service_name,
        'service_price' => $service_price
    ];
    $total_price += $service_price;  // Add the price of each service to the total
}
$stmt->close();

if (empty($services)) {
    echo "No booking found for this customer.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="images/favicon.png" type="">
    <title>MyKakaks - Payment</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css" />
	<style>
	.custom-page {
	background-color: #00204a; /* Consistent background color for the whole page */
	}
	.team_section .btn-box {
	  display: -webkit-box;
	  display: -ms-flexbox;
	  display: flex;
	  -webkit-box-pack: center;
		  -ms-flex-pack: center;
			  justify-content: center;
	  margin-top: 45px;
	}

	.team_section .btn-box a {
	  display: inline-block;
	  padding: 10px 45px;
	  background-color: #00bbf0;
	  color: #ffffff;
	  border-radius: 0;
	  -webkit-transition: all 0.3s;
	  transition: all 0.3s;
	  border: none;
	}

	.team_section .btn-box a:hover {
	  background-color: #007fa4;
	}
	.team_section {
	  text-align: center;
	  background-color: #00204a;
	  color: #ffffff;
	}
	</style>
</head>
<body class="sub_page custom-page">
	<div class="hero_area">
		<div class="hero_bg_box">
			<div class="bg_img_box">
				<img src="images/hero-bg.png" alt="">
			</div>
		</div>
	
		<section class="team_section layout_padding">
			<div class="container-fluid">
				<div class="heading_container heading_center">
					<h2>
						Payment <span>Details</span>
					</h2>
				</div>
				<div class="team_container">
					<div class="row">
						<div class="col-lg-6 col-sm-6" style="padding-left: 200px;">
							<div class="box">
								<div class="detail-box">
									<div class="responsive-container-block container">
										<div class="info_contact">
											<h4>
												Billing Details
												<hr style="background-color:white">
											</h4>
											<div class="contact_link_box">
												<label for="fname"><b>First Name: </b></label>
												<span><?php echo htmlspecialchars($fname); ?></span><br>

												<label for="lname"><b>Last Name: </b></label>
												<span><?php echo htmlspecialchars($lname); ?></span><br>
												<br>
												<label for="services"><b>Services: </b></label><br>
												<ul>
													<?php foreach ($services as $service): ?>
														<li><?php echo htmlspecialchars($service['service_name']) . " - RM" . number_format($service['service_price'], 2); ?></li>
													<?php endforeach; ?>
												</ul><br>
												<label for="booking_date"><b>Booking Date: </b></label>
												<span><?php echo htmlspecialchars($booking_date); ?></span><br>

												<label for="slot"><b>Time Slot: </b></label>
												<span><?php echo htmlspecialchars($booking_slot); ?></span><br>

												<hr style="background-color:white">
												<label>Total: </label>
												<span id="billingTotal">RM <?php echo number_format($total_price, 2); ?></span>
											</div>
										</div>
									</div>
									<div class="btn-box">
										<a href="booking.php">Back</a>
									</div>
								</div>
							</div>	
						</div>
						
						<div class="col-lg-6 col-sm-6" style="padding-right: 200px;">
							<div class="box">
								<div class="detail-box" style="padding-left:120px; padding-right: 120px; padding-bottom: 200px;">
									<div class="responsive-container-block container">
										<div class="info_contact">
											<h4>
												Payment Methods
												<hr style="background-color:white">
											</h4>
											<div class="contact_link_box">
												<div id="paypal-button-container"></div> 
											</div>
										</div>
									</div>
								</div>
							</div>
						</div> 
					</div> 
				</div> 
			</div>
		</section>
	</div>

<script src="https://www.paypal.com/sdk/js?client-id=AcDLgmqL01BgcXPwgg4hUwUm5pJE-iUKTY38YlJMy-1K9GDHubzDcb72oZyQHuGv-ycWKS8JeEgIrgqP&currency=USD"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        paypal.Buttons({
            style: {
                shape: 'rect',
                color: 'gold',
                layout: 'vertical',
                label: 'paypal'
            },
            createOrder: function(data, actions) {
                const totalPriceText = document.getElementById('billingTotal').textContent;
                const amount = parseFloat(totalPriceText.replace('RM ', '').replace(',', ''));

                if (isNaN(amount) || amount <= 0) {
                    alert('Invalid amount. Please check billing details.');
                    return actions.reject();
                }

                return actions.order.create({
                    purchase_units: [{
                        amount: { value: amount.toFixed(2) }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Transaction completed by ' + details.payer.name.given_name + '. Thank you for your payment!');
                    fetch('update_booking.php', {
						method: 'POST',
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
						body: `booking_id=${details.purchase_units[0].reference_id}&total_price=${details.purchase_units[0].amount.value}&transaction_id=${details.id}&payment_status=${details.status}&payment_method=PayPal`
					})
                    .then(response => response.text())
                    .then(data => alert('Payment successfully recorded!'))
                    .catch(error => alert('Error recording payment.'));
                });
            },
            onCancel: function() { alert('Payment was canceled.'); },
            onError: function(err) { alert('An error occurred.'); }
        }).render('#paypal-button-container');
    });
</script>


</body>
</html>