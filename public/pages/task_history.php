<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){
    include '../../config/db_connection.php';
    include '../../app/controllers/users.php';
    
    $department_id = $_SESSION['department_id'];
    $user_id = $_SESSION['id'];
    $completed_tasks = get_completed_tasks($conn, $department_id, $user_id);
    //  print_r($completed_tasks);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task History</title>
    <link rel="stylesheet" href="../styles/task_history.css?v=2.0">
    <link rel="stylesheet" href="../styles/nav.css?v=1.0">
</head>
<body>
    <?php include '../inc/nav.php'; ?>
    <div class="main-content">
        <h1 class="page-title">Task History</h1>     
       <div class="task-container">
            <div class="controls">        
                <input type="text" class="search-input" id="searchInput" placeholder="Search">
            </div>

            <table class="task-table" id="taskTable">
                <thead>
                    <tr>
                        <th>Task Id.</th>
                        <th>Task Title</th>
                        <th>Status</th>
                        <th>Priority</th>
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
    </div>
</body>
 <script>
            
    const allTasks = <?php echo json_encode($completed_tasks); ?>;
    
    let currentPage = 1;
    const tasksPerPage = 7;
    let filteredTasks = [...allTasks];

    function getStatusClass(status) {
        if (status === "Pending") return "status-pending";
        if (status === "In Progress") return "status-progress";
        if (status === "Completed") return "status-completed";
        return "";
    }

    function getPriorityClass(priority) {
        if (priority === "High") return "priority-high";
        if (priority === "Medium") return "priority-medium";
        if (priority === "Low") return "priority-low";
        return "";
    }

    function filterTasks() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();

        filteredTasks = allTasks.filter(task => {
            return searchTerm === '' || 
                task.title.toLowerCase().includes(searchTerm) || 
                task.task_id.toString().includes(searchTerm) ||
                task.priority.toLowerCase().includes(searchTerm);
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
            tbody.innerHTML = '<tr><td colspan="5" class="no-results">No completed tasks found</td></tr>';
        } else {
            tbody.innerHTML = tasksToShow.map(task => `
                <tr>
                    <td>${task.task_id}</td>
                    <td>${task.title}</td>
                    <td><span class="status-badge ${getStatusClass(task.status)}">${task.status}</span></td>
                    <td><span class="${getPriorityClass(task.priority)}">${task.priority}</span></td>
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

    // Event listener for search only
    document.getElementById('searchInput').addEventListener('input', filterTasks);

    // Initial render
    renderTasks();

    </script>
</html>
<?php 
} else {
    $em = "Login First";
    header("Location: login.php?error=$em");
    exit();
}
?>