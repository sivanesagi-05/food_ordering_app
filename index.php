<?php
session_start();

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

        .masthead {
            height: 100vh;
            min-height: 500px;
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                url('https://source.unsplash.com/-YHSwy6uqvk/1920x1080');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            overflow: hidden;
        }

        .masthead::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(76, 175, 80, 0.3), rgba(139, 195, 74, 0.3));
            z-index: 1;
        }

        .masthead::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(to top, var(--white), transparent);
            z-index: 2;
        }

        .masthead .container {
            position: relative;
            z-index: 3;
        }

        .btn-outline-light {
            border-width: 2px;
            font-weight: 500;
            padding: 0.75rem 2.5rem;
            transition: all 0.3s ease;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .btn-outline-light:hover {
            background: var(--gradient-primary);
            border-color: transparent;
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .section-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 2.5rem;
            position: relative;
            padding-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        .card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .card-body {
            padding: 2rem;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--white);
            font-size: 2rem;
            transition: all 0.3s ease;
        }

        .card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: var(--gradient-primary);
            color: var(--white);
            border: none;
            padding: 1.5rem;
        }

        .modal-body {
            padding: 2rem;
        }

        .input-group-text {
            background: var(--light-blue);
            border: none;
            color: var(--primary-color);
        }

        .form-control {
            border: 2px solid var(--light-blue);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.15);
        }

        .btn-default {
            background: var(--gradient-primary);
            color: var(--white);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .btn-default:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .alert {
            border-radius: 15px;
            border: none;
            box-shadow: var(--shadow-md);
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

<body class="content">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top shadow bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="#">Hungry?</a>
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="browse">Browse</a>
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
                        <a href="" class="btn btn-outline-success my-2 my-sm-0" data-toggle="modal" data-target="#modalLoginForm">Login</a>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Alert -->
    <div id="alert" style="position:absolute;z-index:1;" class="m-5">
    </div>

    <!-- Content -->
    <header class="masthead">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <?php
                if (isset($_SESSION['sess_id'])) {
                    echo '<div class="col-12 text-center text-white">';
                    echo '<h1 class="display-4 font-weight-bold mb-4">Welcome back, ' . $_SESSION["sess_username"] . '!</h1>';
                    echo '<p class="lead mb-5">Discover fresh groceries delivered to your doorstep</p>';
                    echo '<a href="profile?act=update" class="btn btn-outline-light btn-lg">Update Profile</a>';
                    echo '</div>';
                } else {
                    echo '<div class="col-12 text-center text-white">';
                    echo '<h1 class="display-4 font-weight-bold mb-4">Crave it. Tap it. Eat it.</h1>';
                    echo '<p class="lead mb-5">Shop from the comfort of your home</p>';
                    echo '<a href="browse" class="btn btn-outline-light btn-lg">Start Shopping</a>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section class="features py-5">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose Us?</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <i class="fas fa-motorcycle"></i>
                        <h3>Fast Delivery</h3>
                        <p>Get your food delivered within 30 minutes</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <i class="fas fa-utensils"></i>
                        <h3>Fresh Food</h3>
                        <p>Made with fresh ingredients daily</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <i class="fas fa-headset"></i>
                        <h3>24/7 Support</h3>
                        <p>We're always here to help you</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Model Signup -->
    <div class="modal fade" id="modalSignupForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="action.php" method="post" id="signup_form">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Join Our Community</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        <div class="row">
                            <div class="col">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter your name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-at"></i></div>
                                    </div>
                                    <input type="text" class="form-control" id="usr" name="username" placeholder="Create a username" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                    </div>
                                    <input type="password" class="form-control" id="pwd" name="password" placeholder="Choose a password" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                    </div>
                                    <input type="password" class="form-control" id="c_pwd" name="confirm_password" placeholder="Confirm password" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-map-marked-alt"></i></div>
                                    </div>
                                    <textarea class="form-control" rows="3" id="address" name="address" placeholder="Enter your delivery address" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <div class="col text-center">
                            <input type="hidden" name="signup" value="user">
                            <button type="submit" class="btn btn-default btn-block">Create Account</button>
                            <p class="mt-3 mb-0">Already have an account? <a href="#" data-toggle="modal" data-target="#modalLoginForm" data-dismiss="modal">Login here</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Model Vendor -->
    <div class="modal fade" id="modalVendorForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="action.php" method="post" id="vendor_form">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Become a Vendor</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        <div class="row">
                            <div class="col">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-store"></i></div>
                                    </div>
                                    <input type="text" class="form-control" id="shop_name" name="shop_name" placeholder="Enter shop name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" id="vendor_username" name="username" placeholder="Create a username" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                    </div>
                                    <input type="password" class="form-control" id="vendor_password" name="password" placeholder="Choose a password" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                    </div>
                                    <input type="password" class="form-control" id="vendor_confirm_password" name="confirm_password" placeholder="Confirm password" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-map-marked-alt"></i></div>
                                    </div>
                                    <textarea class="form-control" rows="3" id="vendor_address" name="address" placeholder="Enter shop address" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <div class="col text-center">
                            <input type="hidden" name="signup" value="shop">
                            <button type="submit" class="btn btn-default btn-block">Register Shop</button>
                            <p class="mt-3 mb-0">Already have an account? <a href="#" data-toggle="modal" data-target="#modalLoginForm" data-dismiss="modal">Login here</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Model Login -->
    <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="action.php" method="post" id="login_form">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Hello.</h4>
                    </div>
                    <div class="modal-body mx-3">
                        <div class="row">
                            <div class="col">
                                <label class="sr-only" for="inlineFormInputGroup1"></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="username" placeholder="Username">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label class="sr-only" for="inlineFormInputGroup2"></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                    </div>
                                    <input type="password" class="form-control" name="password" placeholder="Password">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <small class="text-muted text-center"> Don't have an account? <a href="" class="text-primary" data-toggle="modal" data-dismiss="modal" data-target="#modalSignupForm">Sign up</a> now!</small><br>
                                <small class="text-muted text-center"> Become a vendor, register <a href="" class="text-primary" data-toggle="modal" data-dismiss="modal" data-target="#modalVendorForm">here</a>.</small>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <div class="col text-center">
                            <input type="hidden" name="login">
                            <input type="submit" class="btn btn-default" value="Login">
                        </div>
                    </div>
                </form>
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

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Form validation and animation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        
                        // Add shake animation
                        field.style.animation = 'shake 0.5s';
                        setTimeout(() => {
                            field.style.animation = '';
                        }, 500);
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });

        // Add shake animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-10px); }
                75% { transform: translateX(10px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>