<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){
    include 'config/db_connection.php';
    include 'utils/users.php';
    $Accounts = get_all_accounts($conn);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Accounts</title>
    <link rel="stylesheet" href="styles/manageAccounts.css?v=3.0">
    <link rel="stylesheet" href="styles/nav.css?v=1.0">
    <link rel="stylesheet" href="styles/addEmployeeModal.css">
</head>
<body>
    <?php include 'inc/nav.php'; ?>
     <div class="main-content">
        <h1 class="page-title">Manage Accounts</h1>
        <div class="header">
            <button class="add-employee-btn" onclick="openModal()">+ Add Account</button>
        </div>
        <?php include 'inc/addEmployeeModal.php'; ?>
        <div class="table-container">
            <input type="text" class="search-box" placeholder="Search" onkeyup="searchTable()">
            <?php if(empty($Accounts)){         
            ?>
            <table id="employeeTable">
                <thead>
                    <tr>
                        <th>Fullname</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Hire Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <h1>No Employees in this department</h1>
                </tbody>
            </table>
             <?php }else{   
             ?>
            <table id="employeeTable">
                <thead>
                    <tr>
                        <th>Fullname</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Hire Date</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach($Accounts as $Account){ ?>
                </thead>
                <tbody>
                    <tr>
                        <td><?=$Account['full_name'] ?></td>
                        <td><?=$Account['username'] ?></td>
                        <td><?=$Account['role_name'] ?></td>
                        <td><?=$Account['department_name'] ?></td>
                        <td><?=$Account['hire_date'] ?></td>
                        <td>Employee</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-edit" onclick="editEmployee(<?php echo $Account['id']; ?>)">
                                    <span class="icon-edit"></span> Edit
                                </button>
                                <button class="btn-delete" onclick="deleteEmployee(<?php echo $Account['id']; ?>)">
                                    <span class="icon-delete"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <?php } ?>
            </table>
             <?php } ?>
        </div>
    </div>

    <script>
        function addEmployee() {
            alert('Add Employee button clicked!');
            // Add your modal or redirect logic here
        }

        function editEmployee(id) {
            alert('Edit employee ' + id);
            // Add your edit logic here
        }

        function deleteEmployee(id) {
            if (confirm('Are you sure you want to delete this employee?')) {
                alert('Employee ' + id + ' deleted');
                // Add your delete logic here
            }
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
    header("Location: ../login.php?error=$em");
    exit();
}
?>