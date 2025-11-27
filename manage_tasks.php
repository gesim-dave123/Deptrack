<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){
    include 'config/db_connection.php';
    include 'utils/users.php';
    $employees = get_all_employees($conn, $_SESSION['department_id']);
    $tasks = get_all_tasks($conn, $_SESSION['department_id']);
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tasks</title>
    <link rel="stylesheet" href="styles/manage_tasks.css?v=3.0">
    <link rel="stylesheet" href="styles/nav.css?v=2.0">
    <link rel="stylesheet" href="styles/createTaskModal.css?v=2.0">
</head>
<body>

    <?php include 'inc/nav.php'; ?> 
    <div class="main-content">
        <h1 class="page-title">Manage Tasks</h1>
         <div class="header">
            <button class="create-btn" onclick="openModal()">
                <span>+</span> Create Task
            </button>
        </div>
        <?php include 'inc/createTaskModal.php'; ?>

        <div class="task-container">
            <div class="controls">
                <div class="filter-section">
                    <span class="filter-icon">🔍</span>
                    <select class="filter-select" id="statusFilter">
                        <option value="all">All Status</option>
                        <option value="Pending" selected>Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <input type="text" class="search-input" id="searchInput" placeholder="Search">
            </div>

            <table class="task-table" id="taskTable">
                <thead>
                    <tr>
                        <th>Task Id.</th>
                        <th>Task Title</th>
                        <th>Status</th>
                        <th>Assigned to</th>
                        <th>Deadline</th>
                    </tr>
                </thead>
                <tbody id="taskTableBody">
                    <!-- Tasks will be inserted here -->
                </tbody>
            </table>

            <div class="pagination">
                <div class="pagination-info" id="paginationInfo">
                    Showing 1-7 out of 108
                </div>
                <div class="pagination-controls" id="paginationControls">
                    <!-- Pagination buttons will be inserted here -->
                </div>
            </div>
        </div>
    </div>

    <script>
       

        function openModal() {
            document.getElementById('taskModal').classList.add('active');
            document.getElementById('modalOverlay').classList.add('active');
            // Set minimum date to today
            document.getElementById('deadline').min = new Date().toISOString().split('T')[0];
        }

        function closeModal() {
            document.getElementById('taskModal').classList.remove('active');
            document.getElementById('modalOverlay').classList.remove('active');
            clearErrors();
        }

        function clearErrors() {
            const errorMessages = document.querySelectorAll('.error-message');
            const inputs = document.querySelectorAll('input, textarea, select');
            
            errorMessages.forEach(msg => msg.classList.remove('show'));
            inputs.forEach(input => input.classList.remove('error'));
        }

        function showError(fieldId, errorId) {
            document.getElementById(fieldId).classList.add('error');
            document.getElementById(errorId).classList.add('show');
        }

        function validateForm() {
            clearErrors();
            let isValid = true;

            // Validate Task Title
            const taskTitle = document.getElementById('taskTitle').value.trim();
            if (taskTitle === '') {
                showError('taskTitle', 'titleError');
                isValid = false;
            }

            // Validate Description
            const description = document.getElementById('description').value.trim();
            if (description === '') {
                showError('description', 'descriptionError');
                isValid = false;
            }

            // Validate Deadline
            const deadline = document.getElementById('deadline').value;
            if (deadline === '') {
                showError('deadline', 'deadlineError');
                isValid = false;
            }

            // Validate Priority
            const priority = document.getElementById('priority').value;
            if (priority === '') {
                showError('priority', 'priorityError');
                isValid = false;
            }

            // Validate Assign To
            const assignTo = document.getElementById('assignTo').value;
            if (assignTo === '') {
                showError('assignTo', 'assignError');
                isValid = false;
            }

            return isValid;
        }

       

        // Close modal when pressing ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Remove error styling on input
        document.querySelectorAll('input, textarea, select').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
                const errorId = this.id + 'Error';
                const errorMsg = document.getElementById(errorId);
                if (errorMsg) {
                    errorMsg.classList.remove('show');
                }
            });
        });
       
        // Sample task data (in a real application, this would come from PHP/database)
        const allTasks = <?php echo json_encode($tasks); ?>;

        let currentPage = 1;
        const tasksPerPage = 7;
        let filteredTasks = [...allTasks];

        function getStatusClass(status) {
            if (status === "Pending") return "status-pending";
            if (status === "In Progress") return "status-progress";
            if (status === "Completed") return "status-completed";
            return "";
        }

        function filterTasks() {
            const statusFilter = document.getElementById('statusFilter').value;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            filteredTasks = allTasks.filter(task => {
                const matchesStatus = statusFilter === 'all' || task.status === statusFilter;
                const matchesSearch = searchTerm === '' || 
                    task.title.toLowerCase().includes(searchTerm) || 
                    task.id.toString().includes(searchTerm);
                
                return matchesStatus && matchesSearch;
            });

            currentPage = 1;
            renderTasks();
        }

        function renderTasks() {
            const tbody = document.getElementById('taskTableBody');
            const startIndex = (currentPage - 1) * tasksPerPage;
            const endIndex = startIndex + tasksPerPage;
            const tasksToShow = filteredTasks.slice(startIndex, endIndex);

            if (tasksToShow.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="no-results">No tasks found</td></tr>';
            } else {
                tbody.innerHTML = tasksToShow.map(task => `
                    <tr>
                        <td>${task.task_id}</td>
                        <td>${task.title}</td>
                        <td><span class="status-badge ${getStatusClass(task.status)}">${task.status}</span></td>
                        <td>${task.assigned_employee}</td>
                        <td>${task.due_date}</td>
                    </tr>
                `).join('');
            }

            renderPagination();
        }

        function renderPagination() {
            const totalPages = Math.ceil(filteredTasks.length / tasksPerPage);
            const paginationInfo = document.getElementById('paginationInfo');
            const paginationControls = document.getElementById('paginationControls');

            const startItem = filteredTasks.length === 0 ? 0 : (currentPage - 1) * tasksPerPage + 1;
            const endItem = Math.min(currentPage * tasksPerPage, filteredTasks.length);

            paginationInfo.textContent = `Showing ${startItem}-${endItem} out of ${filteredTasks.length}`;

            let buttonsHTML = `
                <button class="page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>Prev</button>
            `;

            // Show page numbers
            const maxButtons = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
            let endPage = Math.min(totalPages, startPage + maxButtons - 1);

            if (endPage - startPage < maxButtons - 1) {
                startPage = Math.max(1, endPage - maxButtons + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                buttonsHTML += `
                    <button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>
                `;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    buttonsHTML += `<button class="page-btn" disabled>...</button>`;
                }
                buttonsHTML += `<button class="page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
            }

            buttonsHTML += `
                <button class="page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}>Next</button>
                <button class="page-btn" onclick="goToPage(${totalPages})" ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}>Last</button>
            `;

            paginationControls.innerHTML = buttonsHTML;
        }

        function goToPage(page) {
            const totalPages = Math.ceil(filteredTasks.length / tasksPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderTasks();
        }

        // Event listeners
        document.getElementById('statusFilter').addEventListener('change', filterTasks);
        document.getElementById('searchInput').addEventListener('input', filterTasks);

        // Initial render
        renderTasks();
    </script>
    </div>
</body>
</html>
<?php 
} else {
    $em = "Login First";
    header("Location: ../login.php?error=$em");
    exit();
} 
?>