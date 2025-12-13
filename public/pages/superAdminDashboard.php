<?php

require_once '../../app/Middlewares/auth_check.php';

if (isset($_SESSION['role']) && isset($_SESSION['id'])){
    include '../../config/db_connection.php';
    include '../../app/controllers/users.php';
    

    $allTasksCount = get_task_status_counts($conn);
    $totalDepartments = get_total_departments($conn);
    $totalEmployees = get_Allemployee_status_counts($conn);
    $taskperDepartment = get_total_tasks_by_department($conn);
    // $taskData = get_notifications($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Dashboard</title>
    <link rel="stylesheet" href="../styles/adminDashboard.css?v=3.2">
    <link rel="stylesheet" href="../styles/nav.css?v=2.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>


</head>
<body>
     <?php include '../inc/nav.php'; ?>

            <div class="main-content">
                <div class="container">
                    
                   <h1 class ="page-title">Dashboard</h1>
                    <p>Task & Employee Overview</p>
                    
                    <div class="dashboard">
                    <!-- Top Info Cards -->
                    <div class="top-cards">
                        <div class="card info-card">
                            <h3>Total Departments</h3>
                            <div class="value" id="department"></div>
                        </div>
                        <div class="card info-card">
                            <h3>Overall Total Tasks</h3>
                            <div class="value" id="totalTasks"></div>
                        </div>
                        <div class="card info-card">
                            <h3>Total Staffs</h3>
                            <div class="value" id="totalEmployees">12</div>
                        </div>
                    </div>

                    <!-- Main Layout -->
                    <div class="main-layout">
                        <!-- Left Section: Bar Chart -->
                        <div class="left-section">
                            <div class="chart-card">
                                <h2>Tasks by Department</h2>
                                <div class="chart-container">
                                    <canvas id="barChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section: Pie Charts -->
                        <div class="right-section">
                            <div class="chart-card-pie">
                                <h2>Overall Task Status</h2>
                                <div class="pie-chart-wrapper">
                                    <canvas id="taskPie"></canvas>
                                    <div class="center-text">
                                        <div class="label">Total Tasks</div>
                                        <div class="number" id="taskTotal"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="chart-card-pie">
                                <h2>Staff Status</h2>
                                <div class="pie-chart-wrapper">
                                    <canvas id="employeePie"></canvas>
                                    <div class="center-text">
                                        <div class="label">Total Staffs</div>
                                        <div class="number" id="empTotal"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
     <script>
   
    const allTasksCount = <?php echo json_encode($allTasksCount); ?>;
    const totalDepartments = <?php echo json_encode($totalDepartments); ?>;
    const totalEmployees = <?php echo json_encode($totalEmployees); ?>; 
    const task_per_department = <?php echo json_encode($taskperDepartment); ?>; 


    const completedTasks = allTasksCount.Completed || 0;
    const pendingTasks = allTasksCount.Pending || 0;
    const inProgressTasks = allTasksCount['In Progress'] || 0; // Note the space in the key!
    const missingTasks = allTasksCount.Missing || 0;

    const activeEmployees = totalEmployees.Active || 0;
    const inactiveEmployees = totalEmployees.Inactive || 0;

    // 2. Calculate Totals (Fixing the NaN issue by using + for number coercion)
    const totalTasks = +completedTasks + 
                       +pendingTasks + 
                       +inProgressTasks + 
                       +missingTasks;

    const totalAllEmployees = +activeEmployees + 
                           +inactiveEmployees;

    document.getElementById('totalTasks').textContent = totalTasks; 
    document.getElementById('department').textContent = totalDepartments;
    document.getElementById('totalEmployees').textContent = totalAllEmployees;
    document.getElementById('taskTotal').textContent = totalTasks;
    document.getElementById('empTotal').textContent = totalAllEmployees;

    const taskCtx = document.getElementById('taskPie').getContext('2d');
    new Chart(taskCtx, {
        type: 'doughnut',
        data: {
            labels: ['Missing', 'Completed', 'In Progress', 'Pending'],
            datasets: [{
                data: [
                    missingTasks,
                    completedTasks,
                    inProgressTasks,
                    pendingTasks
                ],
                backgroundColor: [
                    '#e04040ff', // Missing (Red)
                    '#4caf50ff', // Completed (Green)
                    '#5bc0deff', // In Progress (Blue)
                    '#ffb050ff'  // Pending (Orange)
                ],
                borderColor: '#fff',
                borderWidth: 3,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 13, weight: '500' , family: "Poppins"},
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                        color: '#000000ff'
                    }
                }
            }
        }
    });

    // Employee Pie Chart (Updated to use employeeStatusCounts)
    const empCtx = document.getElementById('employeePie').getContext('2d');
    new Chart(empCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive/Deleted'],
            datasets: [{
                data: [
                    activeEmployees,
                    inactiveEmployees // Uses the data from $employeeStatusCounts
                ],
                backgroundColor: [
                    '#667eea',
                    '#cbd5e0'
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
                        font: { size: 13, weight: '500' , family: "Poppins"},
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                        color: '#000000ff'
                    }
                }
            }
        }
    });

    // Bar Chart (Still uses static/dummy data. You need to implement the PHP function for this.)
    const barData = task_per_department.map(item => ({
        name: item.department_name,
        tasks: item.total_tasks
    }));
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: barData.map(e => e.name),
            datasets: [{
                label: 'Tasks Assigned',
                data: barData.map(e => e.tasks),
                backgroundColor: '#008fa5ff',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        font: { size: 13, weight: '500' },
                        padding: 20,
                        color: '#4a5568'
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        color: '#718096',
                        font: { size: 12 }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    ticks: {
                        color: '#4a5568',
                        font: { size: 12, weight: '500' }
                    },
                    grid: {
                        display: false
                    }
                }
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