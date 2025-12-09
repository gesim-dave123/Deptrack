<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){
    include '../../config/db_connection.php';
    include '../../app/controllers/users.php';

    $department_id = $_SESSION['department_id'];
    $user_id = $_SESSION['id'];   
    $pending_tasks = get_all_my_tasks($conn, $department_id, $user_id);
    $taskData = get_notifications($conn, $user_id);
   
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
    <link rel="stylesheet" href="../styles/my_tasks.css?v=5.0">
    <link rel="stylesheet" href="../styles/nav.css?v=1.0">
</head>
<body>

    <?php include '../inc/nav.php'; ?>
    <?php include '../inc/toast.php'; ?>
    <div class="main-content">
        <h1 class="page-title">My Tasks</h1>

        <div class="task-grid" id="taskGrid">
            <!-- Tasks will be dynamically inserted here -->
        </div>
    </div>

    <div class="modal-overlay" id="viewModalOverlay" onclick="closeViewModal()"></div>
    <div class="modal" id="viewModal">
        <div class="modal-header">
            <h2>Task Details</h2>
            <button class="close-btn" onclick="closeViewModal()">&times;</button>
        </div>
        <div class="modal-body" id="viewModalBody">
            <!-- Details will be inserted here -->
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal-overlay" id="updateModalOverlay" onclick="closeUpdateModal()"></div>
    <div class="modal" id="updateModal">
        <div class="modal-header">
            <h2>Update Task Status</h2>
            <button class="close-btn" onclick="closeUpdateModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="updateStatusForm" action="../handlers/updateTask_handler.php" method="POST">
                <input type="hidden" name="task_id" id="taskIdInput" value="">
                <div class="status-buttons">
                    <button type="submit" class="status-btn in-progress" name="status" value="In Progress" id="inProgressBtn">
                        📊 In Progress
                    </button>
                    <button type="submit" class="status-btn completed" name="status" value="Completed" id="completedBtn">
                        ✅ Completed
                    </button>
                
                </div>
            </form>
        </div>
    </div>
   <script>
    // Sample task data (in real app, this comes from database)
    const tasks = <?php echo json_encode($pending_tasks); ?>;

    let currentTaskId = null;

    function getPriorityClass(priority) {
        if (priority === "High") return "priority-high";
        if (priority === "Medium") return "priority-medium";
        if (priority === "Low") return "priority-low";
    }
    
    // NEW HELPER FUNCTION: Correctly generates the status badge HTML based on status
    function getStatusBadgeHtml(status) {
        let className;
        let statusDisplay = status; // Default display text

        if (status === "In Progress") {
            className = "status-badge-in-progress";
        } else if (status === "Pending") {
            className = "status-badge-pending";
        } else if (status === "Missing") {
            className = "status-badge-missing";
            statusDisplay = "Missing";
        } else {
            // Fallback for 'Completed' tasks that somehow slipped through the filter, or other unhandled states
            className = "status-badge-default";
        }

        return `<span class="${className}">${statusDisplay}</span>`;
    }


    function renderTasks() {
        const taskGrid = document.getElementById('taskGrid');
        
        // Filter to include only active/relevant statuses
        const filteredTasks = tasks.filter(task => 
            task.status === "Pending" || 
            task.status === "In Progress" || 
            task.status === "Missing" // Use the correct status string
        );
        
        if (filteredTasks.length === 0) {
            taskGrid.innerHTML = '<p class="no-tasks-message">Hooray! No pending, in-progress, or missed tasks currently assigned to you.</p>';
            return;
        }

        taskGrid.innerHTML = filteredTasks.map(task => `
            <div class="task-card">
                <div class="pin-icon">📌</div>
                
                ${getStatusBadgeHtml(task.status)} <h3 class="task-title">${task.title}</h3>
                <div class="task-info">
                    <p>Priority: <span class="${getPriorityClass(task.priority)}">${task.priority}</span></p>
                    <p>Deadline: ${task.due_date}</p>
                </div>
                <div class="task-buttons">
                    <button class="btn-update" onclick="openUpdateModal(${task.task_id})">Update</button>
                    <button class="btn-view" onclick="openViewModal(${task.task_id})">View Details</button>
                </div>
            </div>
        `).join('');
    }

    function openViewModal(taskId) {
        const task = tasks.find(t => t.task_id === taskId);
        const modalBody = document.getElementById('viewModalBody');
        
        modalBody.innerHTML = `
            <div class="detail-item">
                <div class="detail-label">Task Title</div>
                <div class="detail-value">${task.title}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Description</div>
                <div class="detail-value">${task.description}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Priority</div>
                <div class="detail-value ${getPriorityClass(task.priority)}">${task.priority}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Deadline</div>
                <div class="detail-value">${task.due_date}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Status</div>
                <div class="detail-value">${task.status}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Assigned By</div>
                <div class="detail-value">${task.assigned_by}</div>
            </div>
        `;

        document.getElementById('viewModal').classList.add('active');
        document.getElementById('viewModalOverlay').classList.add('active');
    }

    function closeViewModal() {
        document.getElementById('viewModal').classList.remove('active');
        document.getElementById('viewModalOverlay').classList.remove('active');
    }

    function openUpdateModal(taskId) {
        const task = tasks.find(t => t.task_id === taskId);
        currentTaskId = taskId;
        document.getElementById('updateModal').classList.add('active');
        document.getElementById('updateModalOverlay').classList.add('active');
        document.getElementById('taskIdInput').value = taskId;
        const inProgressBtn = document.getElementById('inProgressBtn');
        

         if (task.status === "In Progress") {
            inProgressBtn.disabled = true;
            inProgressBtn.style.opacity = '0.5';
            inProgressBtn.style.cursor = 'not-allowed';
            inProgressBtn.title = 'Task is already in progress';
        } else {
            inProgressBtn.disabled = false;
            inProgressBtn.style.opacity = '1';
            inProgressBtn.style.cursor = 'pointer';
            inProgressBtn.title = '';
        }
        
        document.getElementById('updateModal').classList.add('active');
        document.getElementById('updateModalOverlay').classList.add('active');
        document.getElementById('taskIdInput').value = taskId;
    }

    function closeUpdateModal() {
        document.getElementById('updateModal').classList.remove('active');
        document.getElementById('updateModalOverlay').classList.remove('active');
        currentTaskId = null;
    }

    // Close modals with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeViewModal();
            closeUpdateModal();
        }
    });

    // Initial render
    renderTasks();
</script>
</body>
</html>
<?php 
} else {
    $em = "Login First";
    header("Location: login.php?error=$em");
    exit();
}
?>