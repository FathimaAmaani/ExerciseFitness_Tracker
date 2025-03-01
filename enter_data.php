<?php
include 'config.php';
include 'fileio.php';
include 'common.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Load current data from file
$defaultData = LoadDefaultData();

// Check if user data is available
if (count($defaultData) == 4) {
    $startHeight = $defaultData[0];
    $startWeight = $defaultData[1];
} else {
    $startHeight = '';
    $startWeight = '';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $height = floatval(trim($_POST['height']));
    $weight = floatval(trim($_POST['weight']));
    $bmi = BMICalculator($weight, $height);
    $units = 'KG';
    if (InsertNewUserData($height, $weight, $bmi, $units)) {
        header('Location: main.php');
        exit;
    } else {
        echo "Error saving data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter/Update Data - Fitness Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .nav-link {
            color: white !important;
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
        <h1>Enter/Update Your Data</h1>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="height" class="form-label">Height (m)</label>
                <input type="number" step="0.01" class="form-control" id="height" name="height" value="<?php echo htmlspecialchars($startHeight); ?>" required>
            </div>
            <div class="mb-3">
                <label for="weight" class="form-label">Weight (kg)</label>
                <input type="number" step="0.01" class="form-control" id="weight" name="weight" value="<?php echo htmlspecialchars($startWeight); ?>" required>
            </div>
            <button type="submit" class="btn btn-custom text-white">Save</button>
            <a href="main.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>