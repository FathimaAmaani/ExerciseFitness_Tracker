<?php
// Include configuration file
include 'config.php';

// Check if form was submitted using POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize username and password from POST data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    // Initialize login status flag
    $loginSuccessful = false;

     // Iterate through user list to check credentials
    // Check if the provided username and password match any in the list
    foreach ($ListofUsers as $user) {
        // Compare username and password case-insensitively
        if (strtolower($user['UserName']) == strtolower($username) && $user['Password'] == $password) {
            // Set login status to true if credentials match
            $loginSuccessful = true;
            break;
        }
    }

    if ($loginSuccessful) {
        // Set session variable to indicate the user is logged in
        $_SESSION['logged_in'] = true;
        // Store username status in session
        $_SESSION['username'] = $username;
        // Redirect to the main page (replace 'main.php' with your actual main page)
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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { margin-top: 50px; }
        .btn-primary { background-color: #007bff; border-color: #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Login</h3>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>