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

if (isset($_GET['id'])) {
	$id = $_GET['id'];
}

if (isset($_GET['act'])) {
	if ($_GET['act'] == 'add') {
		if (!isset($_SESSION['sess_cart'])) {
			$_SESSION['sess_cart'] = array();
		}
		$_SESSION['sess_cart'][$id] += 1;
		header('location: browse');
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="groceries.ico">
	<title>Hungry? - Browse Menu</title>
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
			padding: 4rem 0;
			margin-bottom: 3rem;
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

		.search-box {
			background: var(--white);
			padding: 1rem;
			border-radius: 15px;
			box-shadow: var(--shadow-md);
			transition: all 0.3s ease;
		}

		.search-box:focus-within {
			transform: translateY(-2px);
			box-shadow: var(--shadow-lg);
		}

		.search-input {
			border: 2px solid var(--light-blue);
			padding: 0.75rem 1rem;
			border-radius: 10px;
			transition: all 0.3s ease;
		}

		.search-input:focus {
			border-color: var(--primary-color);
			box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.15);
		}

		.card {
			border: none;
			border-radius: 15px;
			transition: all 0.3s ease;
			overflow: hidden;
			margin-bottom: 2rem;
		}

		.card:hover {
			transform: translateY(-5px);
			box-shadow: var(--shadow-lg);
		}

		.card-img-top {
			height: 200px;
			object-fit: cover;
			transition: all 0.3s ease;
		}

		.card:hover .card-img-top {
			transform: scale(1.05);
		}

		.card-body {
			padding: 1.5rem;
		}

		.card-title {
			font-family: 'Montserrat', sans-serif;
			font-weight: 600;
			color: var(--text-color);
			margin-bottom: 1rem;
		}

		.card-text {
			color: #666;
			font-size: 0.9rem;
			margin-bottom: 0.5rem;
		}

		.shop-name {
			color: var(--primary-color);
			font-weight: 500;
		}

		.card-footer {
			background: var(--white);
			border-top: 1px solid rgba(0,0,0,0.05);
			padding: 1rem 1.5rem;
		}

		.price {
			font-family: 'Montserrat', sans-serif;
			font-weight: 700;
			color: var(--primary-color);
			font-size: 1.2rem;
		}

		.btn-add {
			background: var(--gradient-primary);
			color: var(--white);
			border: none;
			padding: 0.5rem 1.5rem;
			border-radius: 50px;
			font-weight: 500;
			transition: all 0.3s ease;
			text-transform: uppercase;
			letter-spacing: 1px;
			font-size: 0.9rem;
		}

		.btn-add:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-md);
			color: var(--white);
		}

		/* Animation Classes */
		.fade-up {
			opacity: 0;
			transform: translateY(20px);
			transition: all 0.6s ease;
		}

		.fade-up.active {
			opacity: 1;
			transform: translateY(0);
		}

		.slide-in {
			opacity: 0;
			transform: translateX(-20px);
			transition: all 0.6s ease;
		}

		.slide-in.active {
			opacity: 1;
			transform: translateX(0);
		}

		/* Custom Scrollbar */
		::-webkit-scrollbar {
			width: 10px;
		}

		::-webkit-scrollbar-track {
			background: var(--light-blue);
		}

		::-webkit-scrollbar-thumb {
			background: var(--primary-color);
			border-radius: 5px;
		}

		::-webkit-scrollbar-thumb:hover {
			background: var(--secondary-color);
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
				<li class="nav-item active">
					<a class="nav-link" href="#">Browse <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="checkout">Checkout</a>
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
			<div class="row align-items-center">
				<div class="col-lg-8 mb-4 mb-lg-0">
					<h1 class="display-4 text-white font-weight-bold mb-3">Browse Our Products</h1>
					<p class="lead text-white mb-0">Discover fresh groceries and household essentials</p>
				</div>
				<div class="col-lg-4">
					<div class="search-box">
						<div class="input-group">
							<input type="text" id="search_query" class="form-control search-input" placeholder="Search products...">
							<div class="input-group-append">
								<span class="input-group-text bg-transparent border-0">
									<i class="fas fa-search text-primary"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>

	<!-- Content -->
	<div class="container mb-5">
		<div class="row">
			<div class="col-12">
				<div class="row" id="display_area">
					<?php
					// Updated query to show specific food items
					$food_items = [
						[
							'name' => 'Briyani',
							'image' => 'briyani.jpg',
							'price' => 250.00,
							'description' => 'Delicious aromatic rice dish with tender meat and special spices',
							'shop' => 'Spice Garden'
						],
						[
							'name' => 'Fried Rice',
							'image' => 'friedrice.jpg',
							'price' => 180.00,
							'description' => 'Classic Chinese-style fried rice with fresh vegetables and choice of protein',
							'shop' => 'Wok & Roll'
						],
						[
							'name' => 'Naan & Butter Chicken',
							'image' => 'naanbutterchicken.jpg',
							'price' => 320.00,
							'description' => 'Soft, fluffy naan bread served with rich, creamy butter chicken curry',
							'shop' => 'Tandoori House'
						],
						[
							'name' => 'Momos',
							'image' => 'momos.jpg',
							'price' => 150.00,
							'description' => 'Steamed dumplings filled with spiced vegetables or meat, served with dipping sauce',
							'shop' => 'Himalayan Delights'
						]
					];

					foreach ($food_items as $item) {
					?>
						<div class="col-md-6 col-lg-4 fade-up">
							<div class="card">
								<img class="card-img-top" src="img/menu/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
								<div class="card-body">
									<h5 class="card-title text-capitalize"><?php echo $item['name']; ?></h5>
									<p class="card-text"><?php echo $item['description']; ?></p>
									<p class="card-text shop-name"><?php echo $item['shop']; ?></p>
								</div>
								<div class="card-footer d-flex justify-content-between align-items-center">
									<span class="price">₹<?php echo number_format($item['price'], 2); ?></span>
									<a href="browse?act=add&id=<?php echo encryptIt($item['name']); ?>" class="btn btn-add">
										<i class="fas fa-shopping-basket mr-2"></i>Add to Cart
									</a>
								</div>
							</div>
						</div>
					<?php
					}
					?>
				</div>
			</div>
		</div>
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

		// Animation on scroll
		function animateOnScroll() {
			const elements = document.querySelectorAll('.fade-up, .slide-in');
			elements.forEach(element => {
				const elementTop = element.getBoundingClientRect().top;
				const elementBottom = element.getBoundingClientRect().bottom;
				const isVisible = (elementTop < window.innerHeight) && (elementBottom >= 0);
				
				if (isVisible) {
					element.classList.add('active');
				}
			});
		}

		// Initial check for elements in view
		window.addEventListener('load', animateOnScroll);
		// Check on scroll
		window.addEventListener('scroll', animateOnScroll);

		// Search functionality
		const searchInput = document.getElementById('search_query');
		const displayArea = document.getElementById('display_area');
		const cards = displayArea.getElementsByClassName('card');

		searchInput.addEventListener('keyup', function() {
			const searchTerm = this.value.toLowerCase();
			
			Array.from(cards).forEach(card => {
				const title = card.querySelector('.card-title').textContent.toLowerCase();
				const description = card.querySelector('.card-text').textContent.toLowerCase();
				const shopName = card.querySelector('.shop-name').textContent.toLowerCase();
				
				if (title.includes(searchTerm) || description.includes(searchTerm) || shopName.includes(searchTerm)) {
					card.closest('.col-md-6').style.display = '';
				} else {
					card.closest('.col-md-6').style.display = 'none';
				}
			});
		});
	</script>
</body>

</html>