<?php

require_once '../../app/Middlewares/auth_check.php';

if (isset($_SESSION['role']) && isset($_SESSION['id'])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Dashboard</title>
    <link rel="stylesheet" href="../styles/dashboard.css?v=1.0">
    <link rel="stylesheet" href="../styles/nav.css?v=2.0">
</head>
<body>
    <?php include '../inc/nav.php'; ?>
   

    <div class="main-content">
        <h1 class="page-title">Dashboard</h1>
        
        <div class="dashboard-grid">
            <div class="card card-large">
                <h2 class="card-title">Task Status Overview</h2>
                
                <div class="chart-container">
                    <svg class="donut-chart" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#5a6c7d" stroke-width="20" 
                                stroke-dasharray="62.83 251.33" stroke-dashoffset="0"></circle>
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#f39c12" stroke-width="20" 
                                stroke-dasharray="75.4 251.33" stroke-dashoffset="-62.83"></circle>
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#27ae60" stroke-width="20" 
                                stroke-dasharray="113.1 251.33" stroke-dashoffset="-138.23"></circle>
                    </svg>
                    <div class="chart-center">
                        <div class="chart-number">120</div>
                        <div class="chart-label">Total Tasks</div>
                    </div>
                </div>

                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-left">
                            <div class="legend-color" style="background-color: #27ae60;"></div>
                            <span class="legend-label">Completed</span>
                        </div>
                        <div class="legend-right">
                            <span class="legend-count">54</span>
                            <span class="legend-percent">45%</span>
                        </div>
                    </div>
                    
                    <div class="legend-item">
                        <div class="legend-left">
                            <div class="legend-color" style="background-color: #f39c12;"></div>
                            <span class="legend-label">In Progress</span>
                        </div>
                        <div class="legend-right">
                            <span class="legend-count">36</span>
                            <span class="legend-percent">30%</span>
                        </div>
                    </div>
                    
                    <div class="legend-item">
                        <div class="legend-left">
                            <div class="legend-color" style="background-color: #5a6c7d;"></div>
                            <span class="legend-label">Incoming</span>
                        </div>
                        <div class="legend-right">
                            <span class="legend-count">30</span>
                            <span class="legend-percent">25%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2 class="card-title">Tasks in Progress</h2>
                <ul class="tasks-list">
                    <li>Revise All Transactions</li>
                    <li>Record Meeting Minutes</li>
                </ul>
            </div>

            <div class="card">
                <h2 class="card-title">Missed Tasks</h2>
                <div class="missed-number">0</div>
            </div>
        </div>
    </div>
     <script>
        // Force reload when page is loaded from cache (back button)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        // Check session when page becomes visible
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                fetch('check_session.php')
                    .then(response => response.json())
                    .then(data => {
                        if (!data.logged_in) {
                            window.location.href = '../public/login.php?error=Session expired';
                        }
                    });
            }
        });
    </script>
</body>
</html>
<?php 
} else {
    $em = "Login First";
    header("Location: ../public/login.php?error=$em");
    exit();
}
?>