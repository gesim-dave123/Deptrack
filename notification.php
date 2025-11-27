<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){
    include 'config/db_connection.php';
    include 'utils/users.php';
    
    $role = $_SESSION['role'];
    $user_id = $_SESSION['id'];

    if ($role == 'Super Admin'){
        $taskData = get_notifications($conn, $user_id);
        // Super Admin might not have a department
        $department_id = null;
    } else {
        // For other roles, make sure department_id exists
        $department_id = $_SESSION['department_id'] ?? null;
        $taskData = get_notifications($conn, $user_id);
    }

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="styles/notification.css?v=1.0">
    <link rel="stylesheet" href="styles/nav.css?v=1.0">
</head>
<body>
    <?php include 'inc/nav.php'; ?>
   
    <div class="main-content">
        <h1>Notifications</h1>

        <div class="notification-container">
            <div class="tabs">
                <div class="tab active" data-tab="tasks">Tasks Updates</div>
                <div class="tab" data-tab="comments">Comments</div>
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
        const tasksData = <?php echo json_encode($taskData); ?>;
        const commentsData = [
            {
                type: 'comment',
                title: 'Logo Design Feedback',
                comment: 'Great work on the initial design! Could you try using our brand colors more prominently? Also, the font might need to be more modern.',
                time: '11:40 AM'
            },
            {
                type: 'comment',
                title: 'Homepage Mockup',
                comment: 'The new layout looks fantastic! I love the clean design. Can we add more whitespace around the hero section?',
                time: '10:55 AM'
            },
            {
                type: 'comment',
                title: 'Q3 Report Numbers',
                comment: 'Please double-check the revenue figures for September. They seem slightly off compared to our internal records.',
                time: '09:30 AM'
            },
            {
                type: 'comment',
                title: 'Meeting Agenda',
                comment: 'Can we also discuss the budget allocation for next quarter during the meeting? It\'s quite urgent.',
                time: '08:50 AM'
            },
            {
                type: 'comment',
                title: 'Campaign Analytics',
                comment: 'The engagement rate is impressive! Let\'s discuss how we can replicate this success in future campaigns.',
                time: '02:45 PM'
            },
            {
                type: 'comment',
                title: 'Handbook Updates',
                comment: 'Don\'t forget to include the new remote work policy and updated vacation guidelines in the handbook.',
                time: '03:15 PM'
            }
        ];

        let currentTab = 'tasks';

        function renderNotifications() {
            const list = document.getElementById('notificationList');
            const data = currentTab === 'tasks' ? tasksData : commentsData;
            
            list.innerHTML = data.map((item, index) => `
                <div class="notification-item" onclick="openModal(${index})">
                    <img class="notification-icon" src="images/noti.png" alt="Notifications">
                    <div class="notification-text">
                        <strong>New ${currentTab === 'tasks' ? 'task' : 'comment'} ${currentTab === 'tasks' ? 'assigned' : 'received'}: "${item.task_title}"</strong>. Click here to view it. (${item.task_due_date})
                    </div>
                </div>
            `).join('');
        }

        function openModal(index) {
            const modal = document.getElementById('modal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');
            const data = currentTab === 'tasks' ? tasksData[index] : commentsData[index];

            modalTitle.textContent = data.task_title;

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

        function closeModal() {
            const modal = document.getElementById('modal');
            modal.classList.remove('show');
        }

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
        updateBadge();
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