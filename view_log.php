<?php
include 'config.php';
include 'fileio.php';
include 'common.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Load user data
$defaultData = LoadDefaultData();
if (count($defaultData) == 4) {
    $startHeight = $defaultData[0];
    $startWeight = $defaultData[1];
    $startBMI = $defaultData[2];
    $units = $defaultData[3];
} else {
    $startHeight = 0;
    $startWeight = 0;
    $startBMI = 0;
    $units = 'KG';
}

// Load activity records
$activityData = LoadActivityRecords();

// Calculate totals
$totalDuration = 0;
$totalCalories = 0;
$totalWeightLoss = 0;
foreach ($activityData as $line) {
    $parts = explode(", ", $line);
    if (count($parts) == 5) {
        $duration = floatval($parts[1]);
        $calories = floatval($parts[2]);
        $weightLost = floatval($parts[3]);
        $totalDuration += $duration;
        $totalCalories += $calories;
        $totalWeightLoss += $weightLost;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log - Fitness Tracker</title>
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
        .list-group-item {
            background-color: #e9ecef;
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
        <h1>Activity Log</h1>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Duration (mins)</th>
                    <th>Calories Burned</th>
                    <th>Weight Lost (<?php echo $units; ?>)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activityData as $line): ?>
                    <?php $record = explode(", ", trim($line)); ?>
                    <?php if (count($record) == 5): ?>
                        <tr>
                            <td><?php echo $record[0]; ?></td>
                            <td><?php echo $record[1]; ?></td>
                            <td><?php echo $record[2]; ?></td>
                            <td><?php echo $record[3]; ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h3>Totals</h3>
        <ul class="list-group mb-4">
            <li class="list-group-item">the total activity duration: <?php echo $totalDuration; ?> mins</li>
            <li class="list-group-item">the total number of calories burned: <?php echo $totalCalories; ?></li>
            <li class="list-group-item">the starting weight: <?php echo $startWeight; ?> <?php echo $units; ?></li>
            <li class="list-group-item">the starting BMI:  <?php echo $startBMI; ?></li>
            <li class="list-group-item">the total weight lost in kg: <?php echo $units; ?>: <?php echo $totalWeightLoss; ?></li>
            <li class="list-group-item">the total weight lost in lbs: </li>
            <li class="list-group-item">the new BMI of the user: <?php echo BMICalculator($startWeight - $totalWeightLoss, $startHeight); ?></li>        
            <!-- <li class="list-group-item">Total weight lost in lbs: <?php echo KilosToPounds($totalWeightLoss); ?></li> -->
          
        </ul>
        <a href="main.php" class="btn btn-custom text-white">Back to Menu</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>