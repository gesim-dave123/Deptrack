<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){
    include '../../config/db_connection.php';
    include '../../app/controllers/users.php';
    
    $role = $_SESSION['role'];
    $user_id = $_SESSION['id'];

    $activities = get_all_notifications($conn);
    // print_r($activities);


    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="../styles/activityLogs.css?v=4.0">
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
        const activities = <?php echo json_encode($activities); ?>;
        //   id: 10,
        //         type: 'Report Generated',
        //         user: 'Finance Team',
        //         description: 'Monthly activity report generated and exported',
        //         time: '3 days ago',
        //         status: 'success'
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
                        <div class="activity-time">${new Date(activity.created_At).toLocaleString("en-US", {
                            year: "numeric",
                            month: "long",
                            day: "numeric",
                            hour: "numeric",
                            minute: "2-digit",
                            hour12: true
                        })}</div>
                    </div>
                    <div class="activity-user">👤 ${activity.created_by}</div>
                    <div class="activity-description">${activity.message}</div>
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