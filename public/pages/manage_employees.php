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
                                       <button class="btn-edit" onclick="openEditModal(this)">
                                            <span class="icon-edit"></span> Edit
                                        </button>
                                        <?php include '../inc/editAccountModal.php'; ?>
                                        <button class="btn-delete" onclick="openModal(this)">
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

    <script>
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
        function openEditModal() {
            document.getElementById('modalOverlay').classList.add('active');
            document.getElementById('editEmployeeModal').classList.add('active');
        }
        function openDeleteModal() {
            document.getElementById('deleteEmployeeModal').classList.add('active');
            document.getElementById('modalOverlay').classList.add('active');
        }

        function closeModal() {
            document.getElementById('employeeModal').classList.remove('active');
            document.getElementById('modalOverlay').classList.remove('active');
            document.getElementById('editEmployeeModal').classList.remove('active');
            
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