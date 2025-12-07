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
    <link rel="stylesheet" href="../styles/notification.css?v=2.0">
    <link rel="stylesheet" href="../styles/nav.css?v=2.0">
    
</head>
<body>
    <?php include '../inc/nav.php'; ?>
   
    <div class="main-content">
        <h1 class="page-title">Notifications</h1>

        <div class="notification-container">
            <div class="tabs">
                <div class="tab active" data-tab="tasks">Tasks Updates</div>
              
            </div>

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
        
        let currentTab = 'tasks';

        function renderNotifications() {
            const list = document.getElementById('notificationList');
            const data = currentTab === 'tasks' ? tasksData : commentsData;
            list.innerHTML = data.map((item, index) => `
                <div class="notification-item ${item.is_read == 0 ? 'read' : ''}" 
                    onclick="openModal(${index}, this)">
                    <img class="notification-icon" src="../images/noti.png" alt="Notifications">
                    <div class="notification-text">
                        <strong>New ${currentTab === 'tasks' ? 'task' : 'comment'} ${currentTab === 'tasks' ? 'assigned' : 'received'}: "${item.task_title}"</strong>. Click here to view it. (${item.created_At.split(" ")[0]})
                    </div>
                </div>
            `).join('');
        }

       function openModal(index,element) {
        element.classList.remove('read');
        const modal = document.getElementById('modal');
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');
        const data = currentTab === 'tasks' ? tasksData[index] : commentsData[index];

        modalTitle.textContent = data.task_title;
       

        
        markNotificationAsRead(data.notification_id);
        
        if (currentTab === 'tasks') {
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
        } else {
            modalBody.innerHTML = `
                <div class="modal-field">
                    <div class="modal-label">Comment</div>
                    <div class="modal-value">${data.comment}</div>
                </div>
            `;
        }

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
                console.log('Response:', result); // Log the full response
                if (result.success) {
                    console.log('Notification marked as read');
                    renderNotifications();
                } else {
                    console.log('Failed:', result.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function closeModal() {
            const modal = document.getElementById('modal');
      
            modal.classList.remove('show');
        }
          function updateBadges() {  
            const unreadNotifications = tasksData.filter(task => task.is_read == 0).length;
            const notificationBadge = document.getElementById('notificationBadge');
            notificationBadge.textContent = unreadNotifications;
            notificationBadge.classList.toggle('hide', unreadNotifications === 0);
        }

        // Call on page load
        updateBadges();
    

        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                currentTab = this.dataset.tab;
                renderNotifications();
            });
        });

        window.onclick = function(event) {
            const modal = document.getElementById('modal');
            if (event.target === modal) {
                closeModal();
            }
        }

        function updateBadge() {
            const total = tasksData.length + commentsData.length;
            document.getElementById('notificationBadge').textContent = total;
        }

        renderNotifications();
        // updateBadge();
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