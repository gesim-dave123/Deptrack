<?php

require_once '../../app/Middlewares/auth_check.php';

if (isset($_SESSION['role']) && isset($_SESSION['id'])){
    include '../../config/db_connection.php';
    include '../../app/controllers/users.php';
    

    $dashboard_data = get_chart_data($conn, $_SESSION['id']);
    // print_r( $dashboard_data);
        
    $cleanData = [
        "Completed" => 0,
        "Pending" => 0,
        "InProgress" => 0,
        "Missing" => 0
    ];

    foreach ($dashboard_data as $row) {
        if ($row['status'] === "Completed") $cleanData["Completed"] = $row["total"];
        if ($row['status'] === "Pending") $cleanData["Pending"] = $row["total"];
        if ($row['status'] === "In Progress") $cleanData["InProgress"] = $row["total"];
        if ($row['status'] === "Missing") $cleanData["Missing"] = $row["total"];
    }
    $taskData = get_notifications($conn, $_SESSION['id']);
    // print_r($cleanData);    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Dashboard</title>
    <link rel="stylesheet" href="../styles/dashboard.css?v=6.0">
    <link rel="stylesheet" href="../styles/nav.css?v=2.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

</head>
<body>
     <?php include '../inc/nav.php'; ?>
    <div class="container">
        <div class="header">
            <h1>Task Dashboard</h1>
            <p>Monitor your project progress in real-time</p>
        </div>

        <div class="dashboard">
            <!-- Chart Card -->
            <div class="chart-card">
                <div class="chart-title">Task Distribution</div>
                <div class="chart-wrapper">
                    <div class="total-count">
                        <div class="number" id="totalTasks">12</div>
                        <div class="label">Total Tasks</div>
                    </div>
                    <div class="chart-container">
                        <canvas id="taskChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card completed">
                    <div class="stat-content">
                        <div class="stat-number" id="completedCount">5</div>
                        <div class="stat-label"><span class="stat-icon"></span>Completed</div>
                    </div>
                </div>
                <div class="stat-card inprogress">
                    <div class="stat-content">
                        <div class="stat-number" id="inprogressCount">3</div>
                        <div class="stat-label"><span class="stat-icon"></span>In Progress</div>
                    </div>
                </div>
                <div class="stat-card pending">
                    <div class="stat-content">
                        <div class="stat-number" id="pendingCount">2</div>
                        <div class="stat-label"><span class="stat-icon"></span>Pending</div>
                    </div>
                </div>
                <div class="stat-card missing">
                    <div class="stat-content">
                        <div class="stat-number" id="missingCount">2</div>
                        <div class="stat-label"><span class="stat-icon"></span>Missing</div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script>
           const data = <?php echo json_encode([
            'missing' => $cleanData["Missing"],
            'completed' => $cleanData["Completed"],
            'inprogress' => $cleanData["InProgress"],
            'pending' => $cleanData["Pending"]
        ]); ?>;

        document.getElementById('missingCount').textContent = data.missing;
        document.getElementById('completedCount').textContent = data.completed;
        document.getElementById('inprogressCount').textContent = data.inprogress;
        document.getElementById('pendingCount').textContent = data.pending;

        const total = data.missing + data.completed + data.inprogress + data.pending;
        document.getElementById('totalTasks').textContent = total;

        const ctx = document.getElementById('taskChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Missing', 'Completed', 'In Progress', 'Pending'],
                datasets: [{
                    data: [data.missing, data.completed, data.inprogress, data.pending],
                    backgroundColor: [
                        '#e04040ff',
                        '#4caf50ff',
                        '#5bc0deff',
                        '#ffb050ff'
                    ],
                    borderColor: 'transparent',
                    borderWidth: 0,
                    hoverOffset: 12,
                    hoverBorderColor: '#f1f5f9'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 25,
                            font: {
                                size: 13,
                                weight: 600,
                                family: "Poppins"
                            },
                            color: '#000000ff',
                            // font-family: Poppins,   
                            usePointStyle: true,
                            pointStyle: 'circle',
                            pointRadius: 6
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        padding: 16,
                        titleFont: { size: 14, weight: 600 },
                        bodyFont: { size: 13 },
                        borderColor: 'rgba(148, 163, 184, 0.2)',
                        borderWidth: 1,
                        titleColor: '#000000ff',
                        bodyColor: '#cbd5e1',
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    duration: 1200,
                    easing: 'easeInOutQuart'
                }
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