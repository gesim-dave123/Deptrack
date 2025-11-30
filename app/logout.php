<?php
// Start session
session_start();

// Store user info for logout message (optional)
$username = $_SESSION['username'] ?? null;

// Clear all session variables
$_SESSION = array();

// Delete the session cookie
if (isset($_COOKIES[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Prevent caching of this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Redirect to login page with success message
header("Location: ../../public/pages/login.php?success=Logged out successfully");
exit();
?>