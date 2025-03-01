<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Fitness Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('fitness-background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .overlay {
            background: rgba(0, 0, 0, 0.6);
            padding: 30px;
            border-radius: 15px;
        }
        .btn-custom {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-custom:hover {
            background-color: #218838;
            border-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <div class="overlay">
            <h1>Welcome to Fitness Tracker</h1>
            <p class="lead">Track your fitness journey, log activities, and achieve your goals!</p>
            <a href="login.php" class="btn btn-custom btn-lg text-white">Get Started</a>
        </div>
    </div>
</body>
</html>