<?php
include 'config.php';
include 'fileio.php';
include 'common.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Load current data from file
$defaultData = LoadDefaultData();
if (count($defaultData) == 4) {
    $startHeight = $defaultData[0];
    $startWeight = $defaultData[1];
} else {
    $startHeight = '';
    $startWeight = '';
}

// Handle unit change
if (isset($_POST['set_units'])) {
    $_SESSION['units'] = $_POST['units'];
}

// Handle data submission
$error = ''; // To store validation errors
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $heightUnit = $_POST['height_unit'];
    $weight = trim($_POST['weight']);

    // Process height based on selected unit
    if ($heightUnit === 'meters') {
        $height = trim($_POST['height_m']);
        // Validate height in meters
        if (empty($height) || !is_numeric($height)) {
            $error = "Height must be a valid number.";
        } elseif ($height <= 0.5 || $height >= 2.5) {
            $error = "Height must be between 0.5m and 2.5m.";
        }
        $height = floatval($height);
    } else {
        $feet = trim($_POST['height_ft']);
        $inches = trim($_POST['height_in']);
        // Validate feet and inches
        if (!is_numeric($feet) || !is_numeric($inches) || $feet < 0 || $inches < 0) {
            $error = "Feet and inches must be non-negative numbers.";
        } elseif ($feet > 8 || $inches >= 12) {
            $error = "Feet must be between 0 and 8, and inches between 0 and 11.99.";
        } else {
            $height = FeetInchesToMeters(floatval($feet), floatval($inches));
        }
    }

    // Validate weight
    if (empty($weight) || !is_numeric($weight)) {
        $error = "Weight must be a valid number.";
    } elseif ($weight <= 0) {
        $error = "Weight must be greater than 0.";
    } elseif ($_SESSION['units'] == "KG" && ($weight < 20 || $weight > 500)) {
        $error = "Weight must be between 20kg and 500kg.";
    } elseif ($_SESSION['units'] == "LBS" && ($weight < 44 || $weight > 1100)) {
        $error = "Weight must be between 44lbs and 1100lbs.";
    }

    if (empty($error)) {
        $weight = floatval($weight);
        // Convert weight to KG for BMI API if in LBS
        $weightInKg = ($_SESSION['units'] == "LBS") ? PoundsToKilos($weight) : $weight;
        $bmi = BMICalculatorWebService($weightInKg, $height); // Use API for BMI
        $units = $_SESSION['units'];
        if (InsertNewUserData($height, $weight, $bmi, $units)) {
            header('Location: main.php');
            exit;
        } else {
            $error = "Error saving data.";
        }
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
        <div class="card p-4">
            <h1 class="text-center mb-4">
                <i class="bi bi-pencil-square me-2"></i>Enter/Update Your Data
            </h1>
            <?php if (!empty($error)) echo "<div class='alert alert-danger'><i class='bi bi-exclamation-triangle-fill me-2'></i>$error</div>"; ?>
            <form method="post" class="mt-4">
                <div class="mb-4">
                    <label for="units" class="form-label">
                        <i class="bi bi-gear me-1"></i> Units
                    </label>
                    <select class="form-select" id="units" name="units" onchange="this.form.submit()">
                        <option value="KG" <?php echo $_SESSION['units'] == "KG" ? "selected" : ""; ?>>KG</option>
                        <option value="LBS" <?php echo $_SESSION['units'] == "LBS" ? "selected" : ""; ?>>LBS</option>
                    </select>
                    <input type="hidden" name="set_units" value="1">
                </div>
                <div class="mb-4">
                    <label for="height_unit" class="form-label">
                        <i class="bi bi-rulers me-1"></i> Height Unit
                    </label>
                    <select class="form-select" id="height_unit" name="height_unit" onchange="toggleHeightInput(this)">
                        <option value="meters">Meters</option>
                        <option value="feet_inches">Feet & Inches</option>
                    </select>
                </div>
                <div class="mb-4" id="height_meters">
                    <label for="height_m" class="form-label">
                        <i class="bi bi-rulers me-1"></i> Height (meters)
                    </label>
                    <input type="number" step="0.01" class="form-control" id="height_m" name="height_m" value="<?php echo htmlspecialchars($startHeight); ?>" required>
                </div>
                <div class="mb-4" id="height_feet_inches" style="display:none;">
                    <label for="height_ft" class="form-label">
                        <i class="bi bi-rulers me-1"></i> Height (feet)
                    </label>
                    <input type="number" class="form-control" id="height_ft" name="height_ft" placeholder="Feet" min="0">
                    <label for="height_in" class="form-label mt-2">
                        <i class="bi bi-rulers me-1"></i> Height (inches)
                    </label>
                    <input type="number" step="0.01" class="form-control" id="height_in" name="height_in" placeholder="Inches" min="0">
                </div>
                <div class="mb-4">
                    <label for="weight" class="form-label">
                        <i class="bi bi-speedometer me-1"></i> Weight (<?php echo $_SESSION['units']; ?>)
                    </label>
                    <input type="number" step="0.01" class="form-control" id="weight" name="weight" value="<?php echo htmlspecialchars($startWeight); ?>" required>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" name="save" class="btn btn-custom text-white me-2">
                        <i class="bi bi-save me-1"></i> Save
                    </button>
                    <a href="main.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </form>
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
    <script>
        function toggleHeightInput(select) {
            const metersDiv = document.getElementById('height_meters');
            const feetInchesDiv = document.getElementById('height_feet_inches');
            if (select.value === 'meters') {
                metersDiv.style.display = 'block';
                feetInchesDiv.style.display = 'none';
            } else {
                metersDiv.style.display = 'none';
                feetInchesDiv.style.display = 'block';
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>