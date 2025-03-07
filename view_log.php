<?php
include 'config.php';
include 'fileio.php';
include 'common.php';
include 'data_api.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Load user data
$defaultData = LoadDefaultData();
if (count($defaultData) == 4) {
    $startHeight = floatval($defaultData[0]);
    $startWeight = floatval($defaultData[1]);
    $startBMI = floatval($defaultData[2]);
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
    $parts = explode(", ", trim($line));
    if (count($parts) == 5) {
        $totalDuration += floatval($parts[1]);
        $totalCalories += floatval($parts[2]);
        $totalWeightLoss += floatval($parts[3]);
    }
}
$totalWeightLoss = round($totalWeightLoss, 4);
$viewType = $_GET['type'] ?? 'basic';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log - Fitness Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #007bff; }
        .navbar-brand, .nav-link { color: white !important; }
        .btn-custom { background-color: #28a745; border-color: #28a745; }
        .btn-custom:hover { background-color: #218838; border-color: #218838; }
        .list-group-item { background-color: #e9ecef; }
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
        <h1><?php echo $viewType == 'extended' ? 'Extended Activity Log' : 'Basic Activity Log'; ?></h1>
        <div class="mb-3">
            <a href="view_log.php?type=basic" class="btn btn-primary">Basic Log</a>
            <a href="view_log.php?type=extended" class="btn btn-primary">Extended Log</a>
        </div>
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
            <li class="list-group-item">Total activity duration: <?php echo $totalDuration; ?> mins</li>
            <li class="list-group-item">Total calories burned: <?php echo $totalCalories; ?></li>
            <li class="list-group-item">Starting weight: <?php echo $startWeight; ?> <?php echo $units; ?></li>
            <li class="list-group-item">Starting BMI: <?php echo $startBMI; ?></li>
            <li class="list-group-item">Total weight lost in <?php echo $units; ?>: <?php echo $totalWeightLoss; ?></li>
            <li class="list-group-item">Total weight lost in lbs: <?php echo $units == "KG" ? KilosToPounds($totalWeightLoss) : $totalWeightLoss; ?></li>
            <li class="list-group-item">New BMI: <?php echo $units == "KG" ? BMICalculator($startWeight - $totalWeightLoss, $startHeight) : BMICalculatorWeightInPounds($startWeight - $totalWeightLoss, $startHeight); ?></li>
            <?php if ($viewType == 'extended'): ?>
                <li class="list-group-item">Average calories burned: <?php echo CalculateAverageCalories($activityData); ?></li>
                <li class="list-group-item">Largest calories burned: <?php echo CalculateLargestCalories($activityData); ?></li>
                <li class="list-group-item">Biggest weight loss interval: <?php echo CalculateBiggestWeightLossInterval($activityData); ?></li>
            <?php endif; ?>
        </ul>
        <a href="main.php" class="btn btn-custom text-white">Back to Menu</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>