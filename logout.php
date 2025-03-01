<?php
// Start the session to access session variables
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: login.php');

// Stop script execution
exit;
?>