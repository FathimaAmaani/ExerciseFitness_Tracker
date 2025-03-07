<?php
include 'config.php';
include 'data_api.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $activity = trim($_POST['activity']);
    $met = floatval($_POST['met']);
    if (!empty($activity) && $met > 0 && !in_array($activity, array_column($_SESSION['listOfActivities'], 'Activity'))) {
        AddNewActivity($activity, $met);
        header('Location: main.php');
        exit;
    } else {
        echo "Invalid input or activity already exists.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Activity - Fitness Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #007bff; }
        .navbar-brand, .nav-link { color: white !important; }
        .btn-custom { background-color: #28a745; border-color: #28a745; }
        .btn-custom:hover { background-color: #218838; border-color: #218838; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="main.php">Fitness Tracker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="enter_data.php">Enter/Update Data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="record_activity.php">Record Activity</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_log.php">View Log</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>Add New Activity</h1>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="activity" class="form-label">Activity Name</label>
                <input type="text" class="form-control" id="activity" name="activity" required>
            </div>
            <div class="mb-3">
                <label for="met" class="form-label">MET Value</label>
                <input type="number" step="0.1" class="form-control" id="met" name="met" required min="0.1">
            </div>
            <button type="submit" name="add" class="btn btn-custom text-white">Add</button>
            <a href="main.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>