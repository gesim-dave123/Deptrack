<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){
    include '../../config/db_connection.php';
    include '../../app/controllers/users.php';
    
    $role = $_SESSION['role'];
    $user_id = $_SESSION['id'];

    if ($role == 'Super Admin'){
        $taskData = get_notifications($conn, $user_id);
        $department_id = null;
    } else {
        // For other roles, make sure department_id exists
        $department_id = $_SESSION['department_id'] ?? null;
        $taskData = get_notifications($conn, $user_id);
    }
//    print_r($taskData);

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="../styles/activityLogs.css?v=3.0">
    <link rel="stylesheet" href="../styles/nav.css?v=2.0">
    
</head>
<body>
    <?php include '../inc/nav.php'; ?>

        <div class="main-content">
            
            <h1 class="page-title">Activity Logs</h1>

            <div class="activity-container" id="activityContainer">
                <!-- Activities will be inserted here -->
            </div>
        </div>
    </div>

    <script>
        // Sample activity data
        const activities = [
            {
                id: 1,
                type: 'User Created',
                user: 'John Smith',
                description: 'New user account created for johsmith@company.com',
                time: '2 hours ago',
                status: 'success'
            },
            {
                id: 2,
                type: 'Department Updated',
                user: 'Maria Garcia',
                description: 'Updated Sales department with new team members',
                time: '4 hours ago',
                status: 'success'
            },
            {
                id: 3,
                type: 'Account Modified',
                user: 'Andrew Gabriel Belandres',
                description: 'Changed permissions for IT department admin',
                time: '6 hours ago',
                status: 'warning'
            },
            {
                id: 4,
                type: 'Login Activity',
                user: 'Sarah Johnson',
                description: 'User logged in from new device - Chrome on Windows',
                time: '8 hours ago',
                status: 'info'
            },
            {
                id: 5,
                type: 'Department Deleted',
                user: 'Andrew Gabriel Belandres',
                description: 'Archived Legacy Projects department',
                time: '1 day ago',
                status: 'warning'
            },
            {
                id: 6,
                type: 'User Disabled',
                user: 'System Admin',
                description: 'Account for alex.thompson@company.com has been disabled',
                time: '1 day ago',
                status: 'warning'
            },
            {
                id: 7,
                type: 'Bulk Import',
                user: 'Andrew Gabriel Belandres',
                description: 'Imported 45 new user accounts from HR system',
                time: '2 days ago',
                status: 'success'
            },
            {
                id: 8,
                type: 'Settings Updated',
                user: 'Maria Garcia',
                description: 'System security settings have been updated',
                time: '2 days ago',
                status: 'success'
            },
            {
                id: 9,
                type: 'Password Reset',
                user: 'Robert Chen',
                description: 'Password reset initiated and email sent',
                time: '3 days ago',
                status: 'info'
            },
            {
                id: 10,
                type: 'Report Generated',
                user: 'Finance Team',
                description: 'Monthly activity report generated and exported',
                time: '3 days ago',
                status: 'success'
            }
        ];

        function renderActivities() {
            const container = document.getElementById('activityContainer');
            
            if (activities.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <p>No activities recorded yet</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = activities.map(activity => `
                <div class="activity-item">
                    <div class="activity-header">
                        <div class="activity-type">${activity.type}</div>
                        <div class="activity-time">${activity.time}</div>
                    </div>
                    <div class="activity-user">👤 ${activity.user}</div>
                    <div class="activity-description">${activity.description}</div>
                    <span class="status-badge status-${activity.status}">
                        ${activity.status.charAt(0).toUpperCase() + activity.status.slice(1)}
                    </span>
                </div>
            `).join('');
        }

        // Initial render
        renderActivities();

        // Add click handlers for navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Simulate real-time activity updates (optional)
        function addNewActivity() {
            const newActivity = {
                id: activities.length + 1,
                type: 'New Activity',
                user: 'System User',
                description: 'A new activity has been recorded',
                time: 'Just now',
                status: 'info'
            };
            
            activities.unshift(newActivity);
            renderActivities();
        }

        // Demo: Add a new activity every 30 seconds (uncomment to enable)
        // setInterval(addNewActivity, 30000);
    </script>
     
</body>
</html>
<?php 
} else {
    $em = "Login First";
    header("Location: ../login.php?error=$em");
    exit();
}
?>