<?php
session_start();
require_once '../../app/Middlewares/auth_check.php';
if (!isset($_SESSION['id'])) {
    header("Location: ../public/login.php");
    exit();
}

// Redirect delay remains 2 seconds
$redirect_delay_ms = 2000; 
$redirect_delay_s = $redirect_delay_ms / 1000;
$dashboard_page = "../pages/dashboard.php"; 

// Client-side redirect after the delay
header("refresh: {$redirect_delay_s}; url={$dashboard_page}");

// Include toast system (contains showToast function/CSS)
include 'toast.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading...</title>
    <link rel="stylesheet" href="styles/loader.css">
    <style>
        /* General Layout */
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;x`
            margin: 0;
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 5px;
        }
        
        /* Loader Container */
        .loader-container {
            width: 300px;
            margin-top: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        /* Progress Bar Styling */
        .progress-bar {
            width: 100%;
            height: 10px;
            background-color: #e0e0e0;
            border-radius: 5px;
            overflow: hidden; /* Important for animation containment */
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        
        /* Progress Indicator Animation */
        .progress-indicator {
            height: 100%;
            width: 0; /* Starts at 0 width */
            background-color: #3498db; /* Blue color */
            /* Key Animation Property: Match animation duration to PHP redirect delay */
            animation: progress-fill <?php echo $redirect_delay_s; ?>s linear forwards; 
        }
        
        /* Keyframe for the fill animation */
        @keyframes progress-fill {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        /* Spinner (optional, but good visual cue) */
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            margin-bottom: 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    
    <h2>Login Successful!</h2>
    <p>Preparing your dashboard...</p>

    <div class="loader-container">
        <div class="spinner"></div> 
        <div class="progress-bar">
            <div class="progress-indicator"></div>
        </div>
    </div>
    
</body>
</html>