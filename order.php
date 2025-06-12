<?php
session_start();
include("db.php");

if (isset($_SESSION["sess_id"])) {
	$usr_id = $_SESSION["sess_id"];
	if ($_SESSION["sess_status"] == "admin") {
		header('location: admin/pnl_user');
	}
	if ($_SESSION["sess_status"] == "shop") {
		header('location: shop/pnl_order');
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="groceries.ico">
	<title>Hungry? - Order Status</title>
	<link rel="stylesheet" href="bootstrap/css/all.min.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<script src="bootstrap/js/jquery-3.4.1.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<style type="text/css">
		:root {
			--primary-color: #4CAF50;
			--secondary-color: #45a049;
			--accent-color: #8BC34A;
			--light-blue: #E3F2FD;
			--text-color: #2c3e50;
			--white: #ffffff;
			--gradient-primary: linear-gradient(135deg, #4CAF50 0%, #8BC34A 100%);
			--gradient-light: linear-gradient(135deg, #E3F2FD 0%, #ffffff 100%);
			--shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
			--shadow-md: 0 4px 6px rgba(0,0,0,0.1);
			--shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
		}

		body {
			font-family: 'Poppins', sans-serif;
			color: var(--text-color);
			background: var(--gradient-light);
		}

		.navbar {
			background: var(--white) !important;
			backdrop-filter: blur(10px);
			padding: 1rem 2rem;
			box-shadow: var(--shadow-sm);
			transition: all 0.3s ease;
		}

		.navbar.scrolled {
			padding: 0.5rem 2rem;
			box-shadow: var(--shadow-md);
		}

		.navbar-brand {
			font-family: 'Montserrat', sans-serif;
			font-weight: 700;
			color: var(--primary-color) !important;
			font-size: 1.5rem;
			transition: all 0.3s ease;
		}

		.nav-link {
			font-weight: 500;
			color: var(--text-color) !important;
			transition: all 0.3s ease;
			margin: 0 0.5rem;
			position: relative;
		}

		.nav-link::after {
			content: '';
			position: absolute;
			bottom: 0;
			left: 50%;
			width: 0;
			height: 2px;
			background: var(--primary-color);
			transition: all 0.3s ease;
			transform: translateX(-50%);
		}

		.nav-link:hover::after {
			width: 100%;
		}

		.page-header {
			background: var(--gradient-primary);
			padding: 3rem 0;
			margin-bottom: 2rem;
			position: relative;
			overflow: hidden;
		}

		.page-header::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: url('https://source.unsplash.com/fdlZBWIP0aM/1920x1080') center/cover;
			opacity: 0.1;
			z-index: 1;
		}

		.page-header .container {
			position: relative;
			z-index: 2;
		}

		.order-card {
			background: var(--white);
			border-radius: 15px;
			box-shadow: var(--shadow-md);
			margin-bottom: 2rem;
			overflow: hidden;
		}

		.order-details {
			padding: 1.5rem;
		}

		.order-info {
			margin-bottom: 1.5rem;
		}

		.order-info h4 {
			font-family: 'Montserrat', sans-serif;
			font-weight: 600;
			color: var(--text-color);
			margin-bottom: 0.5rem;
		}

		.status-badge {
			display: inline-block;
			padding: 0.5rem 1rem;
			border-radius: 50px;
			font-size: 0.9rem;
			font-weight: 500;
			text-transform: uppercase;
		}

		.status-badge.preparing {
			background: var(--light-blue);
			color: #2196F3;
		}

		.status-badge.completed {
			background: #E8F5E9;
			color: var(--primary-color);
		}

		.order-items {
			margin-bottom: 1.5rem;
		}

		.order-item {
			display: flex;
			align-items: center;
			padding: 1rem;
			background: var(--gradient-light);
			border-radius: 10px;
			margin-bottom: 1rem;
		}

		.item-image {
			width: 80px;
			height: 80px;
			margin-right: 1rem;
		}

		.item-image img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			border-radius: 10px;
		}

		.item-details {
			flex: 1;
		}

		.item-details h5 {
			font-family: 'Montserrat', sans-serif;
			font-weight: 600;
			margin-bottom: 0.5rem;
			color: var(--text-color);
		}

		.item-details p {
			margin: 0;
			color: #666;
			font-size: 0.9rem;
		}

		.item-details .price {
			color: var(--primary-color);
			font-weight: 600;
			margin-top: 0.5rem;
		}

		.order-summary {
			background: var(--white);
			border-radius: 10px;
			padding: 1.5rem;
			box-shadow: var(--shadow-sm);
		}

		.summary-row {
			display: flex;
			justify-content: space-between;
			margin-bottom: 1rem;
			padding-bottom: 1rem;
			border-bottom: 1px solid rgba(0,0,0,0.05);
		}

		.summary-row:last-child {
			border-bottom: none;
			margin-bottom: 0;
			padding-bottom: 0;
		}

		.summary-row.total {
			font-size: 1.2rem;
			font-weight: 700;
			color: var(--primary-color);
		}

		.payment-info {
			margin-top: 1.5rem;
			padding-top: 1.5rem;
			border-top: 1px solid rgba(0,0,0,0.05);
		}

		.payment-info p {
			margin: 0.5rem 0;
			color: #666;
		}

		.payment-info strong {
			color: var(--text-color);
		}

		.empty-order {
			text-align: center;
			padding: 3rem;
			background: var(--white);
			border-radius: 15px;
			box-shadow: var(--shadow-md);
		}

		.empty-order i {
			font-size: 4rem;
			color: var(--primary-color);
			margin-bottom: 1rem;
		}

		.empty-order h3 {
			font-family: 'Montserrat', sans-serif;
			font-weight: 600;
			color: var(--text-color);
			margin-bottom: 1rem;
		}

		.empty-order p {
			color: #666;
			margin-bottom: 2rem;
		}

		.btn-browse {
			background: var(--gradient-primary);
			color: var(--white);
			border: none;
			padding: 0.75rem 2rem;
			border-radius: 50px;
			font-weight: 500;
			transition: all 0.3s ease;
			text-decoration: none;
		}

		.btn-browse:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-md);
			color: var(--white);
			text-decoration: none;
		}
	</style>
</head>

<body>
	<!-- Navbar -->
	<nav class="navbar navbar-expand-lg navbar-light sticky-top">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
			<a class="navbar-brand" href="#">Hungry?</a>
			<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
				<li class="nav-item">
					<a class="nav-link" href="index">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="browse">Browse</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="checkout">Checkout</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="#">Status<span class="sr-only">(current)</span></a>
				</li>
			</ul>
			<div class="my-2 my-lg-0">
				<ul class="navbar-nav ml-auto">
					<?php
					if (isset($_SESSION['sess_id'])) {
					?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-333" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-user"></i> </a>
							<div class="dropdown-menu dropdown-menu-right dropdown-default" aria-labelledby="navbarDropdownMenuLink-333">
								<a class="dropdown-item" href="profile">Profile</a>
								<a class="dropdown-item" href="action?act=lgout">Logout</a>
							</div>
						</li>
					<?php
					} else {
					?>
						<a href="index?act=login" class="btn btn-outline-success my-2 my-sm-0">Login</a>
					<?php
					}
					?>
				</ul>
			</div>
		</div>
	</nav>

	<!-- Page Header -->
	<header class="page-header">
		<div class="container">
			<h1 class="display-4 text-white font-weight-bold mb-3">Order Status</h1>
			<p class="lead text-white mb-0">Track your current orders</p>
		</div>
	</header>

	<!-- Content -->
	<div class="container mb-5">
		<?php
		if (isset($usr_id)) {
			$query = "SELECT * from fds_ordr JOIN fds_inv ON fds_ordr.ordr_id=fds_inv.inv_ordr_id WHERE fds_ordr.ordr_usrdt_id='$usr_id' AND fds_ordr.ordr_stat!='Completed'";
			$result = mysqli_query($conn, $query);
			$tot_prc = 0;

			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					$ordr_ctlog_id = $row['ordr_ctlog_id'];
					$query = "SELECT * from fds_ctlog WHERE ctlog_id = '$ordr_ctlog_id'";
					$row_data = mysqli_fetch_assoc(mysqli_query($conn, $query));
					$tot_prc += $row_data['ctlog_prc'] * $row['ordr_qty'];
					$payment_type = $row['inv_type'];
				}
				$tot_svc = number_format((float)(10 / 100 * $tot_prc), 2, '.', '');
		?>
				<div class="order-card">
					<div class="order-header">
						<h4>Current Order</h4>
					</div>
					<div class="order-body">
						<?php
						mysqli_data_seek($result, 0);
						while ($row = mysqli_fetch_assoc($result)) {
							$ordr_ctlog_id = $row['ordr_ctlog_id'];
							$query = "SELECT * from fds_ctlog WHERE ctlog_id = '$ordr_ctlog_id'";
							$row_data = mysqli_fetch_assoc(mysqli_query($conn, $query));
						?>
							<div class="order-details">
								<div class="order-info">
									<h4>Order #<?php echo $row['ordr_id']; ?></h4>
									<p class="text-muted">Placed on <?php echo date('F j, Y, g:i a', strtotime($row['ordr_dte'])); ?></p>
									<div class="status-badge <?php echo strtolower($row['ordr_stat']); ?>">
										<?php echo $row['ordr_stat']; ?>
									</div>
								</div>
								<div class="order-items">
									<?php
									$query = "SELECT o.*, c.ctlog_nme, c.ctlog_prc, c.ctlog_img 
											FROM fds_ordr o 
											JOIN fds_ctlog c ON o.ordr_ctlog_id = c.ctlog_id 
											WHERE o.ordr_id = '" . $row['ordr_id'] . "'";
									$result = mysqli_query($conn, $query);
									while ($item = mysqli_fetch_assoc($result)) {
									?>
										<div class="order-item">
											<div class="item-image">
												<?php if ($item['ctlog_img'] != null): ?>
													<img src="img/menu/<?php echo $item['ctlog_img']; ?>" alt="<?php echo $item['ctlog_nme']; ?>">
												<?php else: ?>
													<img src="https://dummyimage.com/100x100/f0f0f0/aaa" alt="<?php echo $item['ctlog_nme']; ?>">
												<?php endif; ?>
											</div>
											<div class="item-details">
												<h5><?php echo $item['ctlog_nme']; ?></h5>
												<p>Quantity: <?php echo $item['ordr_qty']; ?></p>
												<p class="price">₹<?php echo number_format($item['ctlog_prc'] * $item['ordr_qty'], 2); ?></p>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="order-summary">
									<?php
									$query = "SELECT * FROM fds_inv WHERE inv_ordr_id = '" . $row['ordr_id'] . "'";
									$result = mysqli_query($conn, $query);
									if ($invoice = mysqli_fetch_assoc($result)) {
										$subtotal = $invoice['inv_amt'];
										$service_charge = $subtotal * 0.1;
										$total = $subtotal + $service_charge;
									?>
										<div class="summary-row">
											<span>Subtotal</span>
											<span>₹<?php echo number_format($subtotal, 2); ?></span>
										</div>
										<div class="summary-row">
											<span>Service Charge (10%)</span>
											<span>₹<?php echo number_format($service_charge, 2); ?></span>
										</div>
										<div class="summary-row total">
											<span>Total</span>
											<span>₹<?php echo number_format($total, 2); ?></span>
										</div>
										<div class="payment-info">
											<p><strong>Payment Method:</strong> <?php echo ucfirst($invoice['inv_type']); ?></p>
											<p><strong>Payment Status:</strong> <?php echo ucfirst($invoice['inv_pay_stat']); ?></p>
										</div>
									<?php } ?>
								</div>
							</div>
						<?php
						}
						?>
					</div>
				</div>

				<div class="order-summary">
					<div class="summary-row">
						<span class="summary-label">Subtotal</span>
						<span class="summary-value">₹<?php echo number_format((float)($tot_prc), 2, '.', ''); ?></span>
					</div>
					<div class="summary-row">
						<span class="summary-label">Service Charge (10%)</span>
						<span class="summary-value">₹<?php echo $tot_svc; ?></span>
					</div>
					<div class="summary-row total-row">
						<span>Total</span>
						<span>₹<?php echo number_format((float)(round($tot_prc + $tot_svc, 1)), 2, '.', ''); ?></span>
					</div>
					<div class="payment-info">
						<div class="payment-type">
							<?php echo $payment_type == 'paypal' ? '<i class="fab fa-paypal mr-2"></i>Paid via PayPal' : '<i class="fas fa-wallet mr-2"></i>Pay on Delivery'; ?>
						</div>
						<div class="payment-status">
							<?php echo $payment_type == 'paypal' ? 'Payment Completed' : 'Payment Pending'; ?>
						</div>
					</div>
				</div>
		<?php
			} else {
		?>
				<div class="empty-order">
					<i class="fas fa-clipboard-list"></i>
					<h3>No Active Orders</h3>
					<p>You don't have any active orders at the moment.</p>
					<a href="browse" class="btn-browse">Start Ordering</a>
				</div>
		<?php
			}
		} else {
		?>
			<div class="empty-order">
				<i class="fas fa-clipboard-list"></i>
				<h3>No Active Orders</h3>
				<p>Please login to view your orders.</p>
				<a href="index?act=login" class="btn-browse">Login Now</a>
			</div>
		<?php
		}
		?>
	</div>

	<!-- Script -->
	<script src="bootstrap/js/app.js"></script>
	<script>
		// Navbar scroll effect
		window.addEventListener('scroll', function() {
			const navbar = document.querySelector('.navbar');
			if (window.scrollY > 50) {
				navbar.classList.add('scrolled');
			} else {
				navbar.classList.remove('scrolled');
			}
		});
	</script>
</body>

</html>