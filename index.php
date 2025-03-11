<?php
// Include configuration file
include 'config.php';

// Check if user is already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: main.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Fitness Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f0f8ff, #e6f2ff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1517836357463-d25dfeac3438?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .navbar { 
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand, .nav-link { 
            color: white !important; 
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            transform: translateY(-2px);
            color: #f0f0f0 !important;
        }
        .btn-custom { 
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            color: white;
            font-weight: 500;
            padding: 12px 30px;
            border-radius: 8px;
        }
        .btn-custom:hover { 
            background: linear-gradient(135deg, #218838, #1aa179);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            padding: 25px;
        }
        .card-title {
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            display: block;
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .footer {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: white;
            padding: 30px 0;
            margin-top: 50px;
            border-radius: 20px 20px 0 0;
        }
        .footer a {
            color: white;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .testimonial {
            background-color: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .testimonial-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }
        .testimonial-text {
            font-style: italic;
            color: #6c757d;
        }
        .testimonial-name {
            font-weight: 600;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-heart-pulse-fill me-2"></i>Fitness Tracker
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">
                            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Features
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">
                            <i class="bi bi-chat-quote-fill me-1"></i> Testimonials
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Track Your Fitness Journey</h1>
            <p class="lead mb-5">Monitor your activities, track your progress, and achieve your fitness goals with our comprehensive fitness tracking solution.</p>
            <a href="login.php" class="btn btn-custom btn-lg">
                <i class="bi bi-box-arrow-in-right me-2"></i>Get Started
            </a>
        </div>
    </section>

    <section class="container mb-5" id="features">
        <h2 class="text-center mb-5">
            <i class="bi bi-stars me-2"></i>Key Features
        </h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-pencil-square feature-icon"></i>
                        <h5 class="card-title">Easy Data Entry</h5>
                        <p class="card-text">Quickly enter and update your height and weight measurements with our intuitive interface.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-activity feature-icon"></i>
                        <h5 class="card-title">Activity Tracking</h5>
                        <p class="card-text">Record various fitness activities and automatically calculate calories burned and weight lost.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-graph-up feature-icon"></i>
                        <h5 class="card-title">Progress Monitoring</h5>
                        <p class="card-text">View detailed logs of your activities and track your progress over time with comprehensive statistics.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container mb-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="mb-4">
                    <i class="bi bi-calculator me-2"></i>BMI Tracking
                </h2>
                <p class="lead">Our application automatically calculates your BMI (Body Mass Index) based on your height and weight measurements.</p>
                <p>Track changes in your BMI as you progress through your fitness journey. The system provides both starting and current BMI values to help you monitor your health improvements.</p>
                <a href="login.php" class="btn btn-custom mt-3">
                    <i class="bi bi-arrow-right-circle me-2"></i>Try It Now
                </a>
            </div>
            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1576678927484-cc907957088c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="BMI Tracking" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </section>

    <section class="container mb-5" id="testimonials">
        <h2 class="text-center mb-5">
            <i class="bi bi-chat-quote-fill me-2"></i>What Our Users Say
        </h2>
        <div class="row">
            <div class="col-md-4">
                <div class="testimonial">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="testimonial-img">
                        <div>
                            <p class="testimonial-name mb-0">John D.</p>
                            <small>Lost 15kg in 6 months</small>
                        </div>
                    </div>
                    <p class="testimonial-text">"This fitness tracker has been instrumental in my weight loss journey. Being able to see my progress keeps me motivated every day."</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="testimonial-img">
                        <div>
                            <p class="testimonial-name mb-0">Sarah M.</p>
                            <small>Fitness enthusiast</small>
                        </div>
                    </div>
                    <p class="testimonial-text">"I love how easy it is to log my activities and track calories burned. The BMI tracking feature helps me maintain my ideal weight."</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="User" class="testimonial-img">
                        <div>
                            <p class="testimonial-name mb-0">Michael T.</p>
                            <small>Marathon runner</small>
                        </div>
                    </div>
                    <p class="testimonial-text">"As a runner, I need to track my activities precisely. This app gives me all the data I need to improve my performance and maintain my health."</p>
                </div>
            </div>
        </div>
    </section>

    <section class="container text-center mb-5">
        <div class="card p-5">
            <h2 class="mb-4">Ready to Start Your Fitness Journey?</h2>
            <p class="lead mb-4">Join thousands of users who are already tracking their fitness progress and achieving their health goals.</p>
            <div class="d-flex justify-content-center">
                <a href="login.php" class="btn btn-custom btn-lg">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login Now
                </a>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-heart-pulse-fill me-2"></i>Fitness Tracker</h5>
                    <p>Your companion for a healthier lifestyle. Track activities, monitor progress, and achieve your fitness goals.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php"><i class="bi bi-house-door me-1"></i>Home</a></li>
                        <li><a href="#features"><i class="bi bi-grid-3x3-gap-fill me-1"></i>Features</a></li>
                        <li><a href="#testimonials"><i class="bi bi-chat-quote-fill me-1"></i>Testimonials</a></li>
                        <li><a href="login.php"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-envelope me-1"></i>support@fitnesstracker.com</li>
                        <li><i class="bi bi-telephone me-1"></i>+1 (555) 123-4567</li>
                        <li><i class="bi bi-geo-alt me-1"></i>123 Fitness Street, Health City</li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4 mb-4" style="border-color: rgba(255,255,255,0.2);">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> Fitness Tracker. All rights reserved.</p>
        </div>
    </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>