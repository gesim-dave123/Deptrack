<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){
    include '../../config/db_connection.php';
    include '../../app/controllers/users.php';
    
    // Get data based on role
    if($_SESSION['role'] == 'Super Admin') {
        $Accounts = get_all_accounts($conn);
        // Get all departments for filter
        $departments = get_all_departments($conn);
    } else if($_SESSION['role'] == 'Admin') {
        $employees = get_all_employees($conn, $_SESSION['department_id']);
    }
    
    $taskData = get_notifications($conn, $_SESSION['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
    <link rel="stylesheet" href="../styles/manage_employees.css?v=4.0">
    <link rel="stylesheet" href="../styles/nav.css">
    <link rel="stylesheet" href="../styles/addEmployeeModal.css?v=2.1">
</head>
<body>
    <?php include '../inc/nav.php'; ?>
    <?php include '../inc/toast.php'; ?>
    
    <!-- Include modals ONCE outside role-specific sections -->
    <?php include '../inc/addEmployeeModal.php'; ?>
    <?php include '../inc/editAccountModal.php'; ?>
    <?php include '../inc/deleteModal.php'; ?>
    
    <!-- ADMIN SECTION - Only for Admin role -->
    <?php if($_SESSION['role'] == 'Admin') { ?>
    <div class="main-content">
        <h1 class="page-title">Manage Employees</h1>
        <div class="header">
            <button class="add-employee-btn" onclick="openModal()">+ Add Employee</button>
        </div>
        <div class="container">
            <input type="text" class="search-box" placeholder="Search" onkeyup="searchTable()">
            <?php if(empty($employees)){ ?>
            
            <table id="employeeTable">
                <thead>
                    <tr>
                        <th>Fullname</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="5"><h1>No Employees in this department</h1></td></tr>
                </tbody>
            </table>
            
            <?php } else { ?>
            <div class="table-container">
                <table id="employeeTable">
                    <thead>
                        <tr>
                            <th>Fullname</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($employees as $employee){ ?>
                        <tr>
                            <td><?=$employee['full_name'] ?></td>
                            <td><?=$employee['username'] ?></td>
                            <td><?=$employee['email'] ?></td>
                            <td>Employee</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="openEditModal(
                                        '<?php echo htmlspecialchars($employee['id'], ENT_QUOTES, 'UTF-8'); ?>',
                                        '<?php echo htmlspecialchars($employee['full_name'], ENT_QUOTES, 'UTF-8'); ?>',
                                        '<?php echo htmlspecialchars($employee['username'], ENT_QUOTES, 'UTF-8'); ?>',
                                        '<?php echo htmlspecialchars($employee['email'], ENT_QUOTES, 'UTF-8'); ?>')">
                                        <span class="icon-edit"></span> Edit
                                    </button>
                                    
                                    <button class="btn-delete" onclick="openDeleteModal(
                                        '<?php echo htmlspecialchars($employee['id'], ENT_QUOTES, 'UTF-8'); ?>',
                                        '<?php echo htmlspecialchars($employee['full_name'], ENT_QUOTES, 'UTF-8'); ?>',
                                        '<?php echo htmlspecialchars($employee['username'], ENT_QUOTES, 'UTF-8'); ?>',
                                        '<?php echo htmlspecialchars($employee['email'], ENT_QUOTES, 'UTF-8'); ?>')">
                                        <span class="icon-delete"></span>Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>

    <!-- SUPER ADMIN SECTION - Only for Super Admin role -->
    <?php if($_SESSION['role'] == 'Super Admin') { ?>
    <div class="main-content">
        <h1 class="page-title">Manage Accounts</h1>
        <div class="header">
            <button class="add-employee-btn" onclick="openModal()">+ Add Account</button>
        </div>
        <div class="container">
            <!-- Filters Section -->
            <div class="filters-container">
              <div class="main-filter-container">
                <div class="filter-group">
                    <label for="roleFilter">Filter by Role</label>
                    <select id="roleFilter" class="filter-select" onchange="filterTable()">
                        <option value="">All Roles</option>
                        <option value="super admin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="employee">Employee</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="departmentFilter">Filter by Department</label>
                    <select id="departmentFilter" class="filter-select" onchange="filterTable()">
                        <option value="">All Departments</option>
                        <?php 
                        if(isset($departments) && !empty($departments)) {
                            foreach($departments as $dept) { 
                        ?>
                            <option value="<?= htmlspecialchars($dept['department_name'], ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($dept['department_name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php 
                            }
                        }
                        ?>
                    </select>
                </div>
                <button class="reset-filters-btn" onclick="resetFilters()">Reset Filters</button>
              </div>

              <div class="search-container">
                <input type="text" id="searchBox" class="search-box" placeholder="Search by name, username, or email" onkeyup="filterTable()">
              </div>
            </div>
            
            <?php if(empty($Accounts)){ ?>
            
            <table id="accountsTable">
                <thead>
                    <tr>
                        <th>Fullname</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="6"><h1>No Accounts</h1></td></tr>
                </tbody>
            </table>
            
            <?php } else { ?>
            <div class="table-container">
                <table id="accountsTable">
                    <thead>
                        <tr>
                            <th>Fullname</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($Accounts as $Account){ ?>
                        <tr>
                            <td><?=$Account['full_name'] ?></td>
                            <td><?=$Account['username'] ?></td>
                            <td><?=$Account['email'] ?></td>
                            <td><?=$Account['role_name'] ?></td>
                            <td><?=$Account['department_name'] ?? 'N/A' ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="openEditModal(
                                        '<?php echo htmlspecialchars($Account['id'], ENT_QUOTES, 'UTF-8'); ?>',
                                        '<?php echo htmlspecialchars($Account['full_name'], ENT_QUOTES, 'UTF-8'); ?>',
                                        '<?php echo htmlspecialchars($Account['username'], ENT_QUOTES, 'UTF-8'); ?>',
                                        '<?php echo htmlspecialchars($Account['email'], ENT_QUOTES, 'UTF-8'); ?>')">
                                        <span class="icon-edit"></span> Edit
                                    </button>
                                    
                                    <button class="btn-delete" onclick="openDeleteModal(
                                        '<?php echo htmlspecialchars($Account['id'], ENT_QUOTES, 'UTF-8'); ?>',
                                        '<?php echo htmlspecialchars($Account['full_name'], ENT_QUOTES, 'UTF-8'); ?>',
                                        '<?php echo htmlspecialchars($Account['username'], ENT_QUOTES, 'UTF-8'); ?>',
                                        '<?php echo htmlspecialchars($Account['email'], ENT_QUOTES, 'UTF-8'); ?>')">
                                        <span class="icon-delete"></span>Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    
    <script>
        // For Admin - Simple search
        function searchTable() {
            const input = document.querySelector('.search-box');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('employeeTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.toLowerCase().includes(filter)) {
                        found = true;
                        break;
                    }
                }

                rows[i].style.display = found ? '' : 'none';
            }
        }

        // For Super Admin - Combined filter with search
        function filterTable() {
            const searchInput = document.getElementById('searchBox');
            const searchFilter = searchInput ? searchInput.value.toLowerCase() : '';
            const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
            const departmentFilter = document.getElementById('departmentFilter').value.toLowerCase();
            const table = document.getElementById('accountsTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                
                if (cells.length === 0) continue;

                const fullname = cells[0].textContent.toLowerCase();
                const username = cells[1].textContent.toLowerCase();
                const email = cells[2].textContent.toLowerCase();
                const role = cells[3].textContent.toLowerCase();
                const department = cells[4].textContent.toLowerCase();

                // Check search filter
                const matchesSearch = !searchFilter || 
                    fullname.includes(searchFilter) || 
                    username.includes(searchFilter) || 
                    email.includes(searchFilter);

                // Check role filter
                const matchesRole = !roleFilter || role.includes(roleFilter);

                // Check department filter
                const matchesDepartment = !departmentFilter || department.includes(departmentFilter);

                // Show row only if all filters match
                rows[i].style.display = (matchesSearch && matchesRole && matchesDepartment) ? '' : 'none';
            }
        }

        // Reset all filters
        function resetFilters() {
            document.getElementById('roleFilter').value = '';
            document.getElementById('departmentFilter').value = '';
            document.getElementById('searchBox').value = '';
            filterTable();
        }

        function openModal() {
            document.getElementById('employeeModal').classList.add('active');
            document.getElementById('modalOverlay').classList.add('active');
        }

        function openEditModal(id, fullname, username, email) {
            fullname = fullname.trim();        
            let firstName = fullname.substring(0, fullname.lastIndexOf(" "));
            let lastName = fullname.substring(fullname.lastIndexOf(" ") + 1);
            
            if (fullname.indexOf(" ") === -1) {
                firstName = fullname;
                lastName = "";
            }
            
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_firstName').value = firstName;
            document.getElementById('edit_lastName').value = lastName;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_email').value = email;
            document.getElementById('modalOverlay').classList.add('active');
            document.getElementById('editEmployeeModal').classList.add('active');
        }

        function openDeleteModal(id, fullname, username, email) {
            document.getElementById('delete_id').value = id;
            document.getElementById('employeeName').textContent = fullname;
            document.getElementById('employeeUsername').textContent = username;
            document.getElementById('employeeEmail').textContent = email;
            document.getElementById('modalOverlay').classList.add('active');
            document.getElementById('deleteEmployeeModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('employeeModal').classList.remove('active');
            document.getElementById('modalOverlay').classList.remove('active');
            document.getElementById('editEmployeeModal').classList.remove('active');
            document.getElementById('deleteEmployeeModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal when pressing ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
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