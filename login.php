<?php
// Include configuration file
include 'config.php';

$error = '';

// Check if form was submitted using POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize username and password from POST data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $authenticated = false;
    // Iterate through user list to check credentials
    // Check if the provided username and password match any in the list
    foreach ($ListofUsers as $user) {
        // Compare username case-insensitively and password case-sensitively
        if (strtolower($user['UserName']) == strtolower($username) && $user['Password'] == $password) {
            // Set login status to true if credentials match
            $authenticated = true;
            // Store the correct case username in session
            $username = $user['UserName'];
            break;
        }
    }

    if ($authenticated) {
        // Set session variable to indicate the user is logged in
        $_SESSION['logged_in'] = true;
        // Store username status in session
        $_SESSION['username'] = $username;
        // Redirect to the main page
        header('Location: main.php');
        // Stop script execution
        exit;
    } else {
        // Set error message for invalid credentials
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fitness Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { 
            background: linear-gradient(135deg, #f0f8ff, #e6f2ff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .login-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }
        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        .card-header {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: white;
            text-align: center;
            padding: 25px 15px;
            border-bottom: none;
        }
        .logo-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }
        .card-body {
            padding: 30px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            border-color: #86b7fe;
        }
        .input-group-text {
            border-radius: 8px 0 0 8px;
            background-color: #f8f9fa;
        }
        .btn-login {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #218838, #1aa179);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }
        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
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
    <div class="login-section">
        <div class="login-container">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-heart-pulse-fill logo-icon"></i>
                    <h3>Fitness Tracker</h3>
                    <p class="mb-0">Sign in to track your fitness journey</p>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text" class="form-control" name="username" placeholder="Username" required>
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                            <div class="form-text w-100">Password is case-sensitive</div>
                        </div>
                        
                        <button type="submit" class="btn btn-login text-white">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </button>
                    </form>
                   
                </div>
            </div>
            <p class="footer-text">
                <i class="bi bi-shield-lock me-1"></i> Secure login | &copy; <?php echo date('Y'); ?> Fitness Tracker
            </p>
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
                        <li><a href="index.php#features"><i class="bi bi-grid-3x3-gap-fill me-1"></i>Features</a></li>
                        <li><a href="index.php#testimonials"><i class="bi bi-chat-quote-fill me-1"></i>Testimonials</a></li>
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