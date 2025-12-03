<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){
    include '../../config/db_connection.php';
    include '../../app/controllers/users.php';
    $employees = get_all_employees($conn, $_SESSION['department_id']);
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
    <link rel="stylesheet" href="../styles/addEmployeeModal.css">
</head>
<body>
    <?php include '../inc/nav.php'; ?>
    <?php include '../inc/toast.php'; ?>
     <div class="main-content">
        <h1 class="page-title">Manage Employees</h1>
        <div class="header">
            <button class="add-employee-btn" onclick="openModal()">+ Add Employee</button>
        </div>
        <?php include '../inc/addEmployeeModal.php'; ?>
        <div class="container">
            <input type="text" class="search-box" placeholder="Search" onkeyup="searchTable()">
            <?php if(empty($employees)){         
            ?>
            
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
                    <h1>No Employees in this department</h1>
                </tbody>
            </table>
             <?php }else{   
             ?>
             <div class = "table-container">
                    <table id="employeeTable">
                        <thead>
                            <tr>
                                <th>Fullname</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                            <?php foreach($employees as $employee){ ?>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?=$employee['full_name'] ?></td>
                                <td><?=$employee['username'] ?></td>
                                <td><?=$employee['email'] ?></td>
                                <td>Employee</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-edit" onclick="editEmployee(<?php echo $employee['id']; ?>)">
                                            <span class="icon-edit"></span> Edit
                                        </button>
                                        <button class="btn-delete" onclick="deleteEmployee(<?php echo $employee['id']; ?>)">
                                            <span class="icon-delete"></span>Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <?php } ?>
                    </table>
              </div>
             <?php } ?>
        </div>
        <!-- <-- Edit - mOdal --> 
        <div id="editModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modalTitle">Edit Employee</h2>
                    <button class="close" onclick="closeEditModal()">&times;</button>
                </div>
                <form id="employeeForm">
                    <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" name="fullname" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <input type="text" id="role" name="role" value="Employee" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-save">Save Changes</button>
                        <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
            <!-- <-- Delete - mOdal -->                        
          <div id="deleteModal" class="modal">
                <div class="modal-content confirm-modal-content">
                    <h2>Delete Employee</h2>
                    <p>Are you sure you want to delete this employee? This action cannot be undone.</p>
                    <div class="modal-footer">
                        <button class="btn-save btn-delete-confirm" onclick="confirmDelete()">Delete</button>
                        <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                    </div>
                </div>
            </div>
       </div>

    <script>

         function openEditModal(button) {
            isAddMode = false;
            currentRow = button.closest('tr');
            document.getElementById('modalTitle').textContent = 'Edit Employee';
            
            const cells = currentRow.querySelectorAll('td');
            document.getElementById('fullname').value = cells[0].textContent;
            document.getElementById('username').value = cells[1].textContent;
            document.getElementById('email').value = cells[2].textContent;
            document.getElementById('role').value = cells[3].textContent;
            
            document.getElementById('editModal').classList.add('show');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('show');
            currentRow = null;
        }

        function openDeleteModal(button) {
            currentRow = button.closest('tr');
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            currentRow = null;
        }

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
        function openModal() {
            document.getElementById('employeeModal').classList.add('active');
            document.getElementById('modalOverlay').classList.add('active');
        }

        function closeModal() {
            document.getElementById('employeeModal').classList.remove('active');
            document.getElementById('modalOverlay').classList.remove('active');
        }

        function handleSubmit(event) {
            event.preventDefault();
            
            const formData = {
                firstName: document.getElementById('firstName').value,
                lastName: document.getElementById('lastName').value,
                username: document.getElementById('username').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            };

            console.log('Form Data:', formData);
            alert('Employee added successfully!');
            
            // Reset form and close modal
            document.getElementById('employeeForm').reset();
            closeModal();
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