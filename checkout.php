<?php
session_start();
include("db.php");

// Initialize cart if not exists
if (!isset($_SESSION['sess_cart'])) {
	$_SESSION['sess_cart'] = array();
}

if (isset($_SESSION["sess_id"])) {
	$usr_id = $_SESSION["sess_id"];
	if ($_SESSION["sess_status"] == "admin") {
		header('location: admin/pnl_user');
	}
	if ($_SESSION["sess_status"] == "shop") {
		header('location: shop/pnl_order');
	}
}

if (isset($_GET['id'])) {
	$id = $_GET['id'];
}

if (isset($_GET['act'])) {

	if ($_GET['act'] == 'add' && isset($_GET['id'])) {
		$id = $_GET['id'];
		$_SESSION['sess_cart'][$id] = isset($_SESSION['sess_cart'][$id]) ? $_SESSION['sess_cart'][$id] + 1 : 1;
		header('location: checkout');
		exit();
	}

	if ($_GET['act'] == 'del' && isset($_GET['id'])) {
		$id = $_GET['id'];
		if (isset($_SESSION['sess_cart'][$id])) {
			$_SESSION['sess_cart'][$id] -= 1;
			if ($_SESSION['sess_cart'][$id] <= 0) {
				unset($_SESSION['sess_cart'][$id]);
			}
		}
		header('location: checkout');
		exit();
	}

	if ($_GET['act'] == 'payment') {
		if (!isset($_SESSION['sess_id'])) {
			header('Location: checkout?act=not_login');
			exit();
		}

		if (isset($_GET['flag']) && $_GET['flag'] == 'pay') {
			$query = "SELECT * from fds_ordr WHERE ordr_usrdt_id='$usr_id' AND ordr_stat!='Completed'";
			$result = mysqli_query($conn, $query);

			if (mysqli_num_rows($result) > 0) {
				unset($_SESSION['sess_cart']);
				header('location: checkout?act=error');
				exit();
			}

			if (empty($_SESSION['sess_cart'])) {
				header('location: checkout?act=empty_cart');
				exit();
			}

			$date = date('Y-m-d H:i:s');
			$total_amount = 0;

			// First verify all items exist
			foreach ($_SESSION['sess_cart'] as $key => $data) {
				$ctlog_id = decryptIt($key);
				$verify_query = "SELECT ctlog_id FROM fds_ctlog WHERE ctlog_id = '$ctlog_id'";
				$verify_result = mysqli_query($conn, $verify_query);
				
				if (!$verify_result || mysqli_num_rows($verify_result) == 0) {
					header('location: checkout?act=invalid_item');
					exit();
				}
			}

			// If all items exist, proceed with order
			foreach ($_SESSION['sess_cart'] as $key => $data) {
				$ctlog_id = decryptIt($key);
				
				// Get item price
				$price_query = "SELECT ctlog_prc FROM fds_ctlog WHERE ctlog_id = '$ctlog_id'";
				$price_result = mysqli_query($conn, $price_query);
				$price_row = mysqli_fetch_assoc($price_result);
				$total_amount += $price_row['ctlog_prc'] * $data;

				// Insert order
				$order_query = "INSERT INTO fds_ordr (ordr_usrdt_id, ordr_ctlog_id, ordr_qty, ordr_dte, ordr_stat) 
						VALUES('$usr_id', '$ctlog_id', '$data', '$date', 'Preparing')";
				mysqli_query($conn, $order_query);
				
				$inv_ordr_id = mysqli_insert_id($conn);

				// Insert invoice
				$payment_type = isset($_GET['return']) && $_GET['return'] == 'paypal' ? 'paypal' : 'cash';
				$payment_status = $payment_type == 'paypal' ? 'paid' : 'pending';
				
				$invoice_query = "INSERT INTO fds_inv (inv_ordr_id, inv_pay_stat, inv_amt, inv_type, inv_dte) 
						VALUES('$inv_ordr_id', '$payment_status', '$total_amount', '$payment_type', '$date')";
				mysqli_query($conn, $invoice_query);
			}
			
			unset($_SESSION['sess_cart']);
			header('location: checkout?act=success');
			exit();
		} else {
			header('location: checkout?act=cancel');
			exit();
		}
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="groceries.ico">
	<title>Hungry? - Food Delivery</title>
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

		.cart-table {
			background: var(--white);
			border-radius: 15px;
			box-shadow: var(--shadow-md);
			overflow: hidden;
		}

		.cart-table th {
			background: var(--gradient-primary);
			color: var(--white);
			font-weight: 500;
			padding: 1rem;
			border: none;
		}

		.cart-table td {
			padding: 1.5rem;
			vertical-align: middle;
			border-bottom: 1px solid rgba(0,0,0,0.05);
		}

		.cart-item {
			display: flex;
			align-items: center;
			gap: 1rem;
		}

		.cart-item img {
			width: 80px;
			height: 80px;
			object-fit: cover;
			border-radius: 10px;
		}

		.cart-item-info h4 {
			font-family: 'Montserrat', sans-serif;
			font-weight: 600;
			margin-bottom: 0.5rem;
			color: var(--text-color);
		}

		.cart-item-info p {
			color: #666;
			font-size: 0.9rem;
			margin: 0;
		}

		.quantity-control {
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}

		.quantity-btn {
			background: var(--light-blue);
			border: none;
			width: 30px;
			height: 30px;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			color: var(--primary-color);
			font-weight: 600;
			transition: all 0.3s ease;
		}

		.quantity-btn:hover {
			background: var(--primary-color);
			color: var(--white);
		}

		.quantity-display {
			background: var(--white);
			border: 2px solid var(--light-blue);
			width: 40px;
			height: 40px;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: 600;
			color: var(--text-color);
		}

		.price {
			font-family: 'Montserrat', sans-serif;
			font-weight: 700;
			color: var(--primary-color);
			font-size: 1.2rem;
		}

		.subtotal {
			font-family: 'Montserrat', sans-serif;
			font-weight: 600;
			color: var(--text-color);
		}

		.summary-box {
			background: var(--white);
			border-radius: 15px;
			padding: 1.5rem;
			box-shadow: var(--shadow-md);
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

		.summary-label {
			color: #666;
			font-size: 0.9rem;
		}

		.summary-value {
			font-weight: 600;
			color: var(--text-color);
		}

		.total-row {
			font-size: 1.2rem;
			font-weight: 700;
			color: var(--primary-color);
		}

		.btn-checkout {
			background: var(--gradient-primary);
			color: var(--white);
			border: none;
			padding: 1rem 2rem;
			border-radius: 50px;
			font-weight: 500;
			transition: all 0.3s ease;
			text-transform: uppercase;
			letter-spacing: 1px;
			font-size: 1rem;
			width: 100%;
			margin-top: 1rem;
		}

		.btn-checkout:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-md);
			color: var(--white);
		}

		.empty-cart {
			text-align: center;
			padding: 3rem;
			background: var(--white);
			border-radius: 15px;
			box-shadow: var(--shadow-md);
		}

		.empty-cart i {
			font-size: 4rem;
			color: var(--primary-color);
			margin-bottom: 1rem;
		}

		.empty-cart h3 {
			font-family: 'Montserrat', sans-serif;
			font-weight: 600;
			color: var(--text-color);
			margin-bottom: 1rem;
		}

		.empty-cart p {
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
				<li class="nav-item active">
					<a class="nav-link" href="#">Checkout<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="order">Status</a>
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
			<h1 class="display-4 text-white font-weight-bold mb-3">Your Cart</h1>
			<p class="lead text-white mb-0">Review your order and proceed to checkout</p>
		</div>
	</header>

	<!-- Content -->
	<div class="container mb-5">
		<?php
		if (isset($_GET['act'])) {
			if ($_GET['act'] == 'error') {
				echo '<div class="alert alert-danger">You have an active order. Please wait for it to complete.</div>';
			} else if ($_GET['act'] == 'success') {
				echo '<div class="alert alert-success">Order placed successfully!</div>';
			} else if ($_GET['act'] == 'not_login') {
				echo '<div class="alert alert-warning">Please login to place an order.</div>';
			} else if ($_GET['act'] == 'cancel') {
				echo '<div class="alert alert-info">Payment cancelled.</div>';
			} else if ($_GET['act'] == 'empty_cart') {
				echo '<div class="alert alert-warning">Your cart is empty.</div>';
			}
		}
		?>

		<?php if (!empty($_SESSION['sess_cart'])): ?>
			<div class="row">
				<div class="col-lg-8">
					<div class="cart-table">
						<table class="table mb-0">
							<thead>
								<tr>
									<th>Item</th>
									<th>Price</th>
									<th>Quantity</th>
									<th>Subtotal</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$tot_prc = 0;
								foreach ($_SESSION['sess_cart'] as $key => $data) {
									$ctlog_id = decryptIt($key);
									$query = "SELECT * from fds_ctlog WHERE ctlog_id = '$ctlog_id'";
									$result = mysqli_query($conn, $query);
									if ($row = mysqli_fetch_assoc($result)) {
								?>
										<tr>
											<td>
												<div class="cart-item">
													<?php
													if (isset($row['ctlog_img']) && $row['ctlog_img'] != null) {
														echo '<img src="img/menu/' . $row['ctlog_img'] . '" alt="' . $row['ctlog_nme'] . '">';
													} else {
														echo '<img src="https://dummyimage.com/100x100/f0f0f0/aaa" alt="' . $row['ctlog_nme'] . '">';
													}
													?>
													<div class="cart-item-info">
														<h4 class="text-capitalize"><?php echo isset($row['ctlog_nme']) ? $row['ctlog_nme'] : 'Unknown Item'; ?></h4>
														<p><?php echo isset($row['ctlog_desc']) ? $row['ctlog_desc'] : ''; ?></p>
													</div>
												</div>
											</td>
											<td class="price">₹<?php echo isset($row['ctlog_prc']) ? number_format((float)$row['ctlog_prc'], 2, '.', '') : '0.00'; ?></td>
											<td>
												<div class="quantity-control">
													<a href="checkout?act=del&id=<?php echo $key; ?>" class="quantity-btn">-</a>
													<span class="quantity-display"><?php echo $data; ?></span>
													<a href="checkout?act=add&id=<?php echo $key; ?>" class="quantity-btn">+</a>
												</div>
											</td>
											<td class="subtotal">₹<?php echo isset($row['ctlog_prc']) ? number_format((float)($row['ctlog_prc'] * $data), 2, '.', '') : '0.00'; ?></td>
										</tr>
								<?php
										if (isset($row['ctlog_prc'])) {
											$tot_prc = $tot_prc + ($row['ctlog_prc'] * $data);
										}
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="summary-box">
						<h4 class="mb-4">Order Summary</h4>
						<div class="summary-row">
							<span class="summary-label">Subtotal</span>
							<span class="summary-value">₹<?php echo number_format((float)($tot_prc), 2, '.', ''); ?></span>
						</div>
						<div class="summary-row">
							<span class="summary-label">Service Charge (10%)</span>
							<span class="summary-value">₹<?php echo number_format((float)(10 / 100 * $tot_prc), 2, '.', ''); ?></span>
						</div>
						<div class="summary-row total-row">
							<span>Total</span>
							<span>₹<?php echo number_format((float)(round($tot_prc + (10 / 100 * $tot_prc), 1)), 2, '.', ''); ?></span>
						</div>
						<div class="payment-options">
							<a href="checkout?act=payment&flag=pay&return=cash" class="btn btn-checkout mb-2">
								<i class="fas fa-money-bill-wave mr-2"></i>Cash on Delivery
							</a>
							<a href="checkout?act=payment&flag=pay&return=paypal" class="btn btn-checkout">
								<i class="fab fa-paypal mr-2"></i>Pay with PayPal
							</a>
						</div>
					</div>
				</div>
			</div>
		<?php else: ?>
			<div class="empty-cart">
				<i class="fas fa-shopping-basket"></i>
				<h3>Your cart is empty</h3>
				<p>Looks like you haven't added any items to your cart yet.</p>
				<a href="browse" class="btn-browse">Start Shopping</a>
			</div>
		<?php endif; ?>
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