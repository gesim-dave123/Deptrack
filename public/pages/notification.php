<?php
session_start();
// The user data arrays (tasksData and commentsData) need to be defined
// for the JavaScript to work. Since the PHP part only retrieves $taskData, 
// we'll rename it and inject it into the script.

if (isset($_SESSION['role']) && isset($_SESSION['id'])){
    include '../../config/db_connection.php';
    // Assuming this file contains the definition of get_notifications($conn, $user_id)
    include '../../app/controllers/users.php';
    
    $role = $_SESSION['role'];
    $user_id = $_SESSION['id'];

    if ($role == 'Super Admin'){
        // Rename to clearly represent task data for JS injection
        $taskData = get_notifications($conn, $user_id);
        $department_id = null;
    } else {
        $department_id = $_SESSION['department_id'] ?? null;
        $taskData = get_notifications($conn, $user_id);
    }
    
    // Convert the PHP array to a JSON string for JavaScript

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="../styles/notification.css?v=7.0">
    <link rel="stylesheet" href="../styles/nav.css?v=2.0">
</head>
<body>
    <?php include '../inc/nav.php'; ?>
   
    <div class="main-content">
        <h1 class="page-title">Notifications</h1>

        <div class="notification-container">
            

            <div class="notification-list" id="notificationList"></div>
        </div>
    </div>

    <div class="modal" id="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle"></h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <script>
            const notificationsData = <?php echo json_encode($taskData); ?>;
  
       
        
        function renderNotifications() {
            const list = document.getElementById('notificationList');
            const data = notificationsData; // Now always uses notificationsData
            
            if (data.length === 0) {
                list.innerHTML = '<div class="notification-item-empty">No new task updates.</div>';
                return;
            }

            list.innerHTML = data.map((item, index) => `
                <div class="notification-item ${item.is_read == 0 ? 'unread' : 'read'}" 
                    onclick="openModal(${index}, this)">
                    <img class="notification-icon" src="../images/task-checklist.svg"  alt="Task Icon" width="35" height="35" fill="#0b8766">
                    <div class="notification-text">
                        <strong>Task Update: "${item.task_title}"</strong>. ${item.message} (${item.created_At.split(" ")[0]})
                    </div>
                </div>
            `).join('');
        }

        function openModal(index, element) {
            // Remove 'unread' class to update the styling locally
            element.classList.remove('unread');
            
            const modal = document.getElementById('modal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');
            const data = notificationsData[index]; // Always use notificationsData

            modalTitle.textContent = data.task_title;
            
            // Mark as read asynchronously
            markNotificationAsRead(data.notification_id);
            
            // Modal content for Task Updates
            modalBody.innerHTML = `
                <div class="modal-field">
                    <div class="modal-label">Description</div>
                    <div class="modal-value">${data.message}</div>
                </div>
                <div class="modal-field">
                    <div class="modal-label">Deadline</div>
                    <div class="modal-value">${new Date(data.task_due_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</div>
                </div>
                <div class="modal-field">
                    <div class="modal-label">Priority</div>
                    <div class="modal-value">
                        <span class="priority ${data.task_priority}">${data.task_priority}</span>
                    </div>
                </div>
            `;

            modal.classList.add('show');
        }

        function markNotificationAsRead(notificationId) {
            fetch('../../app/controllers/isRead.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'markAsRead',
                    notification_id: notificationId
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log('Notification marked as read');
                    // Find the item and update its is_read status in the local array
                    const index = notificationsData.findIndex(n => n.notification_id == notificationId);
                    if (index !== -1) {
                         notificationsData[index].is_read = 1; // Update local data
                    }
                    updateBadges(); // Only update badges, no need to re-render the list
                } else {
                    console.error('Failed to mark as read:', result.error);
                }
            })
            .catch(error => console.error('Fetch Error:', error));
        }

        function closeModal() {
            const modal = document.getElementById('modal');
            modal.classList.remove('show');
        }
        
        function updateBadges() {  
            const unreadNotifications = notificationsData.filter(item => item.is_read == 0).length;
            const notificationBadge = document.getElementById('notificationBadge');
            
            // Check if the badge element exists (it should be in nav.php)
            if(notificationBadge) {
                 notificationBadge.textContent = unreadNotifications;
                 // Add logic for 'hide' class, using 0 for falsy state
                 notificationBadge.classList.toggle('hide', unreadNotifications === 0);
            } else {
                console.warn("Notification badge element not found in the DOM.");
            }
        }
        
        // --- Initialization ---
        renderNotifications();
        updateBadges(); // Call on page load to set the initial badge count

        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Since there is only one tab now, this handler is simplified/optional
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                // currentTab is no longer needed
                renderNotifications();
            });
        });

        window.onclick = function(event) {
            const modal = document.getElementById('modal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
<?php 
} else {
    $em = "Login First";
    // Assuming login.php is one level up relative to where this file is run
    header("Location: ../login.php?error=$em");
    exit();
}
?>