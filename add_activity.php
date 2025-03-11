<?php
include 'config.php';
include 'data_api.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Define additional activities with MET values
$additionalActivities = [
    ["Activity" => "Walking (slow)", "METvalue" => 2.0],
    ["Activity" => "Walking (moderate)", "METvalue" => 3.5],
    ["Activity" => "Walking (brisk)", "METvalue" => 5.0],
    ["Activity" => "Hiking", "METvalue" => 6.0],
    ["Activity" => "Jogging", "METvalue" => 7.0],
    ["Activity" => "Swimming (leisure)", "METvalue" => 6.0],
    ["Activity" => "Swimming (vigorous)", "METvalue" => 9.8],
    ["Activity" => "Cycling (leisure)", "METvalue" => 4.0],
    ["Activity" => "Cycling (moderate)", "METvalue" => 8.0],
    ["Activity" => "Cycling (vigorous)", "METvalue" => 10.0],
    ["Activity" => "Dancing", "METvalue" => 4.8],
    ["Activity" => "Zumba", "METvalue" => 6.5],
    ["Activity" => "Pilates", "METvalue" => 3.0],
    ["Activity" => "Tai Chi", "METvalue" => 3.0],
    ["Activity" => "Basketball", "METvalue" => 6.5],
    ["Activity" => "Soccer", "METvalue" => 7.0],
    ["Activity" => "Tennis", "METvalue" => 7.3],
    ["Activity" => "Volleyball", "METvalue" => 4.0],
    ["Activity" => "Golf", "METvalue" => 4.8],
    ["Activity" => "Gardening", "METvalue" => 3.8],
    ["Activity" => "Housework", "METvalue" => 3.5],
    ["Activity" => "Stair climbing", "METvalue" => 4.0],
    ["Activity" => "Elliptical trainer", "METvalue" => 5.0],
    ["Activity" => "Skiing", "METvalue" => 7.0],
    ["Activity" => "Snowboarding", "METvalue" => 4.3],
    ["Activity" => "Rock climbing", "METvalue" => 8.0],
    ["Activity" => "Martial arts", "METvalue" => 10.3],
    ["Activity" => "Kickboxing", "METvalue" => 8.5],
    ["Activity" => "CrossFit", "METvalue" => 8.0],
    ["Activity" => "HIIT workout", "METvalue" => 8.0]
];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_predefined'])) {
        // Adding a predefined activity
        $selectedActivity = json_decode($_POST['predefined_activity'], true);
        $activityName = $selectedActivity['Activity'];
        $metValue = $selectedActivity['METvalue'];
        
        // Check if activity already exists
        $exists = false;
        foreach ($_SESSION['listOfActivities'] as $act) {
            if ($act['Activity'] == $activityName) {
                $exists = true;
                break;
            }
        }
        
        if (!$exists) {
            AddNewActivity($activityName, $metValue);
            $success = "Activity '$activityName' with MET value $metValue has been added successfully!";
        } else {
            $error = "Activity '$activityName' already exists in your list.";
        }
    } elseif (isset($_POST['add_custom'])) {
        // Adding a custom activity
        $activityName = trim($_POST['activity_name']);
        $metValue = floatval($_POST['met_value']);
        
        if (empty($activityName)) {
            $error = "Activity name cannot be empty.";
        } elseif ($metValue <= 0) {
            $error = "MET value must be greater than 0.";
        } else {
            // Check if activity already exists
            $exists = false;
            foreach ($_SESSION['listOfActivities'] as $act) {
                if ($act['Activity'] == $activityName) {
                    $exists = true;
                    break;
                }
            }
            
            if (!$exists) {
                AddNewActivity($activityName, $metValue);
                $success = "Custom activity '$activityName' with MET value $metValue has been added successfully!";
            } else {
                $error = "Activity '$activityName' already exists in your list.";
            }
        }
    }
}

// Filter out activities that are already in the user's list
$filteredAdditionalActivities = array_filter($additionalActivities, function($activity) {
    foreach ($_SESSION['listOfActivities'] as $existingActivity) {
        if ($existingActivity['Activity'] == $activity['Activity']) {
            return false;
        }
    }
    return true;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Activity - Fitness Tracker</title>
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
        .nav-tabs .nav-link {
            border-radius: 8px 8px 0 0;
            padding: 10px 20px;
            font-weight: 500;
            color: #495057;
        }
        .nav-tabs .nav-link.active {
            background-color: #fff;
            color: #0d6efd;
            border-bottom: none;
            font-weight: 600;
        }
        .tab-content {
            background-color: #fff;
            border-radius: 0 0 10px 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .activity-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .badge-met {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: white;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 20px;
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
        <div class="card mb-4">
            <div class="card-body">
                <h1 class="text-center mb-4">
                    <i class="bi bi-plus-circle-fill me-2"></i>Add New Activity
                </h1>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i><?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <ul class="nav nav-tabs mb-4" id="activityTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="predefined-tab" data-bs-toggle="tab" data-bs-target="#predefined" type="button" role="tab" aria-controls="predefined" aria-selected="true">
                            <i class="bi bi-list-check me-1"></i> Predefined Activities
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="custom-tab" data-bs-toggle="tab" data-bs-target="#custom" type="button" role="tab" aria-controls="custom" aria-selected="false">
                            <i class="bi bi-pencil-square me-1"></i> Custom Activity
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="current-tab" data-bs-toggle="tab" data-bs-target="#current" type="button" role="tab" aria-controls="current" aria-selected="false">
                            <i class="bi bi-card-list me-1"></i> Your Activities
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="activityTabContent">
                    <!-- Predefined Activities Tab -->
                    <div class="tab-pane fade show active" id="predefined" role="tabpanel" aria-labelledby="predefined-tab">
                        <h3 class="mb-4"><i class="bi bi-list-check me-2"></i>Select from Predefined Activities</h3>
                        
                        <?php if (empty($filteredAdditionalActivities)): ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle-fill me-2"></i>You've already added all predefined activities to your list.
                            </div>
                        <?php else: ?>
                            <form method="post" class="mb-4">
                                <div class="mb-3">
                                    <label for="predefined_activity" class="form-label">
                                        <i class="bi bi-activity me-1"></i> Select Activity
                                    </label>
                                    <select class="form-select" id="predefined_activity" name="predefined_activity" required>
                                        <?php foreach ($filteredAdditionalActivities as $activity): ?>
                                            <option value='<?php echo htmlspecialchars(json_encode($activity)); ?>'>
                                                <?php echo $activity['Activity']; ?> (MET: <?php echo $activity['METvalue']; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" name="add_predefined" class="btn btn-custom text-white">
                                    <i class="bi bi-plus-circle me-1"></i> Add Selected Activity
                                </button>
                            </form>
                            
                            <div class="activity-list mt-4">
                                <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Available Predefined Activities</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Activity</th>
                                                <th>MET Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($filteredAdditionalActivities as $activity): ?>
                                                <tr>
                                                    <td><?php echo $activity['Activity']; ?></td>
                                                    <td><span class="badge badge-met"><?php echo $activity['METvalue']; ?></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Custom Activity Tab -->
                    <div class="tab-pane fade" id="custom" role="tabpanel" aria-labelledby="custom-tab">
                        <h3 class="mb-4"><i class="bi bi-pencil-square me-2"></i>Add Custom Activity</h3>
                        <form method="post">
                            <div class="mb-3">
                                <label for="activity_name" class="form-label">
                                    <i class="bi bi-tag me-1"></i> Activity Name
                                </label>
                                <input type="text" class="form-control" id="activity_name" name="activity_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="met_value" class="form-label">
                                    <i class="bi bi-speedometer2 me-1"></i> MET Value
                                </label>
                                <input type="number" step="0.1" class="form-control" id="met_value" name="met_value" min="0.1" required>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i> MET (Metabolic Equivalent of Task) values typically range from 1 to 20. 
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#metInfoModal">Learn more about MET values</a>
                                </div>
                            </div>
                            <button type="submit" name="add_custom" class="btn btn-custom text-white">
                                <i class="bi bi-plus-circle me-1"></i> Add Custom Activity
                            </button>
                        </form>
                    </div>
                    
                    <!-- Current Activities Tab -->
                    <div class="tab-pane fade" id="current" role="tabpanel" aria-labelledby="current-tab">
                        <h3 class="mb-4"><i class="bi bi-card-list me-2"></i>Your Current Activities</h3>
                        
                        <?php if (empty($_SESSION['listOfActivities'])): ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle-fill me-2"></i>You haven't added any activities yet.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Activity</th>
                                            <th>MET Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($_SESSION['listOfActivities'] as $activity): ?>
                                            <tr>
                                                <td><?php echo $activity['Activity']; ?></td>
                                                <td><span class="badge badge-met"><?php echo $activity['METvalue']; ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="record_activity.php" class="btn btn-primary me-2">
                        <i class="bi bi-activity me-1"></i> Go to Record Activity
                    </a>
                    <a href="main.php" class="btn btn-secondary">
                        <i class="bi bi-house-door me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- MET Info Modal -->
    <div class="modal fade" id="metInfoModal" tabindex="-1" aria-labelledby="metInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="metInfoModalLabel">
                        <i class="bi bi-info-circle me-2"></i>Understanding MET Values
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>MET (Metabolic Equivalent of Task) is a measure of the energy cost of physical activities. One MET is defined as the energy expended while sitting at rest, which is approximately 1 kcal/kg/hour.</p>
                    
                    <h5 class="mt-4">Common MET Value Ranges:</h5>
                    <ul>
                        <li><strong>Light activities (1-3 METs):</strong> Sitting, standing, walking slowly, light housework</li>
                        <li><strong>Moderate activities (3-6 METs):</strong> Brisk walking, cycling at a moderate pace, gardening</li>
                        <li><strong>Vigorous activities (6+ METs):</strong> Running, swimming laps, aerobics, heavy lifting</li>
                    </ul>
                    
                    <h5 class="mt-4">Examples of MET Values:</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Activity</th>
                                    <th>MET Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Sleeping</td><td>0.9</td></tr>
                                <tr><td>Sitting quietly</td><td>1.0</td></tr>
                                <tr><td>Walking slowly (2 mph)</td><td>2.5</td></tr>
                                <tr><td>Cycling leisurely</td><td>4.0</td></tr>
                                <tr><td>Brisk walking (4 mph)</td><td>5.0</td></tr>
                                <tr><td>Tennis (singles)</td><td>7.3</td></tr>
                                <tr><td>Running (6 mph)</td><td>10.0</td></tr>
                                <tr><td>Running (10 mph)</td><td>16.0</td></tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <p class="mt-3">When adding a custom activity, try to estimate its intensity relative to these examples to determine an appropriate MET value.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Got it</button>
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