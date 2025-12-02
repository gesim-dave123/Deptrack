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
    <link rel="stylesheet" href="../styles/dashboard.css?v=2.0">
    <link rel="stylesheet" href="../styles/nav.css?v=2.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <!-- GitHub Calendar CSS -->
    <link rel="stylesheet" href="https://unpkg.com/github-calendar/dist/github-calendar-responsive.css">

    <!-- GitHub Calendar JS -->
    <script src="https://unpkg.com/github-calendar/dist/github-calendar.min.js"></script>

</head>
<body>
    <?php include '../inc/nav.php'; ?>
    <?php include '../inc/toast.php'; ?>  
   

    <div class="main-content">
        <h1 class="page-title">Dashboard</h1>
       
        
        <div class="dashboard-grid">
            <div class="card card-large">
                <h2 class="card-title">Task Status Overview</h2>
                
                <div class="content">
                    <div class="chart-wrapper">
                        <canvas id="taskChart"></canvas>
                    </div>

                    <div class="stats">
                        <div class="stat-item completed">
                            <div class="stat-color"></div>
                            <div class="stat-info">
                                <h3>Completed</h3>
                                <p id="completedCount">0</p>
                            </div>
                            <div class="stat-percentage" id="completedPercent">0%</div>
                        </div>

                        <div class="stat-item in-progress">
                            <div class="stat-color"></div>
                            <div class="stat-info">
                                <h3>In Progress</h3>
                                <p id="inProgressCount">0</p>
                            </div>
                            <div class="stat-percentage" id="inProgressPercent">0%</div>
                        </div>

                        <div class="stat-item incoming">
                            <div class="stat-color"></div>
                            <div class="stat-info">
                                <h3>Incoming</h3>
                                <p id="incomingCount">0</p>
                            </div>
                            <div class="stat-percentage" id="incomingPercent">0%</div>
                        </div>

                        <div class="total-tasks">
                            <h3>Total Tasks</h3>
                            <div class="number" id="totalTasks">0</div>
                        </div>
                    </div>
                </div>

                <div class="legend">
                    <div class="heatmap-wrapper">
                        <div id="github-calendar"></div>
                    </div>
               
                </div>
            </div>
        </div>
    </div>
     <script>
        // Dummy data
        const taskData = {
            completed: 54,
            inProgress: 36,
            incoming: 30
        };

        const total = taskData.completed + taskData.inProgress + taskData.incoming;
        const completedPercent = Math.round((taskData.completed / total) * 100);
        const inProgressPercent = Math.round((taskData.inProgress / total) * 100);
        const incomingPercent = Math.round((taskData.incoming / total) * 100);

        // Update stat numbers
        document.getElementById('completedCount').textContent = taskData.completed;
        document.getElementById('inProgressCount').textContent = taskData.inProgress;
        document.getElementById('incomingCount').textContent = taskData.incoming;
        document.getElementById('totalTasks').textContent = total;

        // Update percentages
        document.getElementById('completedPercent').textContent = completedPercent + '%';
        document.getElementById('inProgressPercent').textContent = inProgressPercent + '%';
        document.getElementById('incomingPercent').textContent = incomingPercent + '%';

        // Create doughnut chart
        const ctx = document.getElementById('taskChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress', 'Incoming'],
                datasets: [{
                    data: [taskData.completed, taskData.inProgress, taskData.incoming],
                    backgroundColor: [
                        '#15af39',
                        '#0598b3',
                        '#f1bb19'
                    ],
                    borderColor: [
                        '#15af39',
                        '#0598b3',
                        '#f1bb19'
                    ],
                    borderWidth: 3,
                    borderRadius: 8,
                    spacing: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' tasks';
                            }
                        }
                    }
                }
            }
        });
         GitHubCalendar("#github-calendar", "your-github-username", {
            responsive: true, // make it responsive
            global_stats: false // optionally hide total contributions
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