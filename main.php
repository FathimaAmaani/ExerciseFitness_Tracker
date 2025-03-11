<?php
// Include configuration file
include 'config.php';

// Check if user is not logged in
/**
 * Checks if the user is logged in and redirects to the login page if not.
 * This code is used to ensure that only authenticated users can access the main application.
 */

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    
    // Redirect to login page if not authenticated
    header('Location: login.php');

    // Stop script execution
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log - Fitness Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { 
            background-color: #f0f8ff; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        }
        .btn-custom:hover { 
            background: linear-gradient(135deg, #218838, #1aa179);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }
        .list-group-item { 
            background-color: #ffffff;
            border-left: 4px solid #0d6efd;
            margin-bottom: 5px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .table thead {
            background-color: #0d6efd;
            color: white;
        }
        .container {
            padding-bottom: 40px;
        }
        h1, h3 {
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        .btn {
            border-radius: 5px;
            padding: 8px 20px;
            font-weight: 500;
        }
        
        .footer {
    background: linear-gradient(135deg, #0d6efd, #0dcaf0);
    color: white;
    padding: 30px 0;
    margin-top: 50px;
    border-radius: 20px 20px 0 0;
}
.footer h5 {
    font-weight: 600;
    margin-bottom: 20px;
}
.footer a {
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}
.footer a:hover {
    text-decoration: underline;
    opacity: 0.9;
}
.footer ul li {
    margin-bottom: 10px;
}
.footer hr {
    border-color: rgba(255,255,255,0.2);
    margin: 20px 0;
}
.footer .bi {
    margin-right: 5px;
}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="main.php">
                <i class="bi bi-heart-pulse-fill me-2"></i>Fitness Tracker
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="enter_data.php">
                            <i class="bi bi-pencil-square me-1"></i> Enter Data
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="record_activity.php">
                            <i class="bi bi-activity me-1"></i> Record Activity
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_log.php">
                            <i class="bi bi-journal-text me-1"></i> View Log
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="card p-4 mb-5">
            <h1 class="text-center mb-4">
                <i class="bi bi-speedometer2 me-2"></i>Your Fitness Dashboard
            </h1>
            <p class="lead text-center mb-5">Track your fitness journey and achieve your health goals</p>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="bi bi-pencil-square text-primary" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">Enter Data</h5>
                            <p class="card-text">Update your height and weight measurements</p>
                            <a href="enter_data.php" class="btn btn-outline-primary mt-2">
                                <i class="bi bi-arrow-right-circle me-1"></i> Go
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="bi bi-activity text-danger" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">Record Activity</h5>
                            <p class="card-text">Log your fitness activities and workouts</p>
                            <a href="record_activity.php" class="btn btn-outline-danger mt-2">
                                <i class="bi bi-arrow-right-circle me-1"></i> Go
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="bi bi-journal-text text-success" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">View Log</h5>
                            <p class="card-text">Check your progress and activity history</p>
                            <a href="view_log.php" class="btn btn-outline-success mt-2">
                                <i class="bi bi-arrow-right-circle me-1"></i> Go
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    <li><a href="main.php"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
                    <li><a href="view_log.php"><i class="bi bi-journal-text me-1"></i>Activity Log</a></li>
                    <li><a href="record_activity.php"><i class="bi bi-activity me-1"></i>Record Activity</a></li>
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
        <hr class="mt-4 mb-4">
        <div class="text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Fitness Tracker. All rights reserved.</p>
        </div>
    </div>
</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>