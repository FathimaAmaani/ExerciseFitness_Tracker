<?php
include_once 'config.php';
include_once 'fileio.php';
include_once 'common.php';
include_once 'data_api.php';


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

// Calculate totals and current metrics
$totalDuration = 0;
$totalCalories = 0;
$totalWeightLossKg = 0; // Total in KG for consistency

foreach ($activityData as $line) {
    $parts = explode(", ", trim($line));
    if (count($parts) == 5) {
        $duration = floatval($parts[1]);
        $calories = floatval($parts[2]);
        $weightLost = floatval($parts[3]);
        $recordUnits = trim($parts[4]);

        // Convert all weight loss to KG for consistent summation
        $weightLostKg = ($recordUnits == "KG") ? $weightLost : PoundsToKilos($weightLost);

        // Add to totals
        $totalDuration += $duration;
        $totalCalories += $calories;
        $totalWeightLossKg += $weightLostKg;
    }
}

// Convert total weight loss to LBS for display
$totalWeightLossLbs = KilosToPounds($totalWeightLossKg);

// Calculate current weight and new BMI
$currentWeight = $startWeight - ($units == "KG" ? $totalWeightLossKg : $totalWeightLossLbs);
$weightForBMI = ($units == "KG") ? $currentWeight : PoundsToKilos($currentWeight);
$newBMI = BMICalculator($weightForBMI, $startHeight);

$viewType = $_GET['type'] ?? 'basic';
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
        <div class="card mb-4 p-3">
            <h1 class="text-center mb-4">
                <i class="bi bi-journal-check me-2"></i>
                <?php echo $viewType == 'extended' ? 'Extended Activity Log' : 'Basic Activity Log'; ?>
            </h1>
            <div class="mb-4 text-center">
                <a href="view_log.php?type=basic" class="btn <?php echo $viewType == 'basic' ? 'btn-primary' : 'btn-outline-primary'; ?> me-2">
                    <i class="bi bi-list-ul me-1"></i> Basic Log
                </a>
                <a href="view_log.php?type=extended" class="btn <?php echo $viewType == 'extended' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                    <i class="bi bi-list-check me-1"></i> Extended Log
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover mt-4">
                    <thead>
                        <tr>
                            <th><i class="bi bi-activity me-1"></i> Activity</th>
                            <th><i class="bi bi-clock me-1"></i> Duration (mins)</th>
                            <th><i class="bi bi-fire me-1"></i> Calories Burned</th>
                            <th><i class="bi bi-arrow-down-circle me-1"></i> Weight Lost (KG)</th>
                            <th><i class="bi bi-arrow-down-circle me-1"></i> Weight Lost (LBS)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activityData as $line): ?>
                            <?php $record = explode(", ", trim($line)); ?>
                            <?php if (count($record) == 5): ?>
                                <?php
                                $weightLost = floatval($record[3]);
                                $recordUnits = trim($record[4]);
                                $weightLostKg = ($recordUnits == "KG") ? $weightLost : PoundsToKilos($weightLost);
                                $weightLostLbs = ($recordUnits == "LBS") ? $weightLost : KilosToPounds($weightLost);
                                ?>
                                <tr>
                                    <td><?php echo $record[0]; ?></td>
                                    <td><?php echo $record[1]; ?></td>
                                    <td><?php echo $record[2]; ?></td>
                                    <td><?php echo round($weightLostKg, 4); ?></td>
                                    <td><?php echo round($weightLostLbs, 4); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <h3 class="mt-5 mb-3"><i class="bi bi-graph-up me-2"></i>Summary</h3>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-group mb-4">
                        <li class="list-group-item"><i class="bi bi-clock-history me-2"></i> Total activity duration: <span class="badge bg-primary rounded-pill"><?php echo round($totalDuration, 1); ?> mins</span></li>
                        <li class="list-group-item"><i class="bi bi-fire me-2"></i> Total calories burned: <span class="badge bg-danger rounded-pill"><?php echo round($totalCalories, 1); ?></span></li>
                        <li class="list-group-item"><i class="bi bi-arrow-up-circle me-2"></i> Starting weight: <span class="badge bg-secondary rounded-pill"><?php echo $startWeight; ?> <?php echo $units; ?></span></li>
                        <li class="list-group-item"><i class="bi bi-arrow-down-circle me-2"></i> Current weight: <span class="badge bg-success rounded-pill"><?php echo round($currentWeight, 2); ?> <?php echo $units; ?></span></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-group mb-4">
                        <li class="list-group-item"><i class="bi bi-calculator me-2"></i> Starting BMI: <span class="badge bg-secondary rounded-pill"><?php echo $startBMI; ?></span></li>
                        <li class="list-group-item"><i class="bi bi-calculator-fill me-2"></i> Current BMI: <span class="badge bg-info rounded-pill"><?php echo round($newBMI, 1); ?></span></li>
                        <li class="list-group-item"><i class="bi bi-arrow-down me-2"></i> Total weight lost (KG): <span class="badge bg-success rounded-pill"><?php echo round($totalWeightLossKg, 4); ?></span></li>
                        <li class="list-group-item"><i class="bi bi-arrow-down me-2"></i> Total weight lost (LBS): <span class="badge bg-success rounded-pill"><?php echo round($totalWeightLossLbs, 4); ?></span></li>
                    </ul>
                </div>
            </div>
            
            <?php if ($viewType == 'extended'): ?>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-group mb-4">
                        <li class="list-group-item"><i class="bi bi-bar-chart me-2"></i> Average calories burned: <span class="badge bg-warning text-dark rounded-pill"><?php echo round(CalculateAverageCalories($activityData), 1); ?></span></li>
                        <li class="list-group-item"><i class="bi bi-trophy me-2"></i> Largest calories burned: <span class="badge bg-warning text-dark rounded-pill"><?php echo round(CalculateLargestCalories($activityData), 1); ?></span></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-group mb-4">
                        <li class="list-group-item"><i class="bi bi-award me-2"></i> Biggest weight loss (KG): <span class="badge bg-success rounded-pill"><?php echo round(CalculateBiggestWeightLossInterval($activityData), 4); ?></span></li>
                        <li class="list-group-item"><i class="bi bi-award me-2"></i> Biggest weight loss (LBS): <span class="badge bg-success rounded-pill"><?php echo round(KilosToPounds(CalculateBiggestWeightLossInterval($activityData)), 4); ?></span></li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="text-center mt-4">
                <a href="main.php" class="btn btn-custom text-white">
                    <i class="bi bi-house-door me-1"></i> Back to Dashboard
                </a>
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