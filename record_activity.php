<?php
include 'config.php';
include 'fileio.php';
include 'common.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $activity = $_POST['activity'];
    $duration = $_POST['duration'] === 'custom' ? floatval($_POST['custom_duration']) : floatval($_POST['duration']);
    
    // Find MET value for the activity
    $METvalue = 0;
    foreach ($_SESSION['listOfActivities'] as $act) {
        if ($act['Activity'] == $activity) {
            $METvalue = $act['METvalue'];
            break;
        }
    }
    if ($METvalue == 0) {
        echo "Activity not found.";
        exit;
    }

    // Load current weight
    $defaultData = LoadDefaultData();
    if (count($defaultData) < 2) {
        echo "No user data found.";
        exit;
    }
    $startWeight = floatval($defaultData[1]);
    $units = $_SESSION['units'];

    // Calculate calories burned and weight lost based on duration
    $caloriesBurned = ($units == "KG") ?
        CaloriesBurnedInCustomTime($METvalue, $startWeight, $duration) :
        CaloriesBurnedInCustomTimeLb($METvalue, $startWeight, $duration);
    $weightLost = ($units == "KG") ?
        WeightLostInKilosInCustomTime($METvalue, $startWeight, $duration) :
        WeightLostInPoundsInCustomTime($METvalue, $startWeight, $duration);

    // Save the activity record
    if (AddNewActivityRecord($activity, $duration, $caloriesBurned, $weightLost, $units)) {
        header('Location: main.php');
        exit;
    } else {
        echo "Error recording activity.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Activity - Fitness Tracker</title>
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
        <h1>Record Activity</h1>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="activity" class="form-label">Select Activity</label>
                <select class="form-select" id="activity" name="activity" required>
                    <?php foreach ($_SESSION['listOfActivities'] as $act): ?>
                        <option value="<?php echo $act['Activity']; ?>"><?php echo $act['Activity']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Duration (minutes)</label>
                <select class="form-select" id="duration" name="duration" onchange="toggleCustom(this)">
                    <option value="15">15</option>
                    <option value="30" selected>30</option>
                    <option value="60">60</option>
                    <option value="custom">Custom</option>
                </select>
                <input type="number" class="form-control mt-2" id="custom_duration" name="custom_duration" placeholder="Enter custom minutes" style="display:none;" min="1">
            </div>
            <button type="submit" class="btn btn-custom text-white">Record</button>
            <a href="main.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script>
        function toggleCustom(select) {
            document.getElementById('custom_duration').style.display = select.value == 'custom' ? 'block' : 'none';
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>