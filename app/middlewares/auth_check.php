<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['id'])) {
    // User is not logged in, redirect to login
    header("Location: login.php");
    exit();
}

// Optional: Check for session timeout (30 minutes)
$timeout_duration = 1800; // 30 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    // Session expired
    session_unset();
    session_destroy();
    header("Location: login.php?error=Session expired");
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();
?>