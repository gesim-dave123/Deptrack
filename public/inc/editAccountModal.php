 <?php
 $role = $_SESSION['role'];
if($role!= 'Super Admin'){?>
 <div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>

    <!-- Modal -->
    <div class="modal" id="editEmployeeModal">
        <!-- Modal Header -->
        <div class="modal-header">
            <div class="modal-title">
                <h2>Edit Employee</h2>
                <div class="modal-subtitle">Enter Employee details</div>
            </div>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <form id="employeeForm" action="../handlers/editEmployee_handler.php" method="POST">
                <!-- First Name and Last Name Row -->
                <div class="form-row">
                    <div class="form-group"> 
                        <label for="firstName">Full Name</label>
                        <input type="text" id="firstName" name="firstName" placeholder=$employee['fullname'] required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" placeholder="Lastname" required>
                    </div>
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder=$employee['username']  required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder=$employee['email'] required>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="submit" form="employeeForm" class="add-btn">Save Changes</button>
        </div>
    </div>
<?php }else{?>
     <div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>

    <!-- Modal -->
    <div class="modal" id="editEmployeeModal">
        <!-- Modal Header -->
        <div class="modal-header">
            <div class="modal-title">
                <h2>Add Account</h2>
                <div class="modal-subtitle">Enter Account details</div>
            </div>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <form id="employeeForm" action="app/add_employee.php" method="POST">
                <!-- First Name and Last Name Row -->
                <div class="form-row">
                    <div class="form-group"> 
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" placeholder="Firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" placeholder="Lastname" required>
                    </div>
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>

                <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" name="department" required>
                            <option value="">--Select Role--</option>
                             <?php foreach($department as $dep){ ?>
                            <option value="<?= $dep['department_id'] ?>"><?= htmlspecialchars($dep['department_name']) ?></option>
                        <?php } ?>
                        </select>
                        <div class="error-message" id="priorityError">Department is required</div>
                </div>
                <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>                         
                            <option value="">--Select Priority--</option>
                            <option value="2">Admin</option>
                            <option value="3">Employee</option>
                        </select>
                        <div class="error-message" id="priorityError">Role is required</div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="submit" form="employeeForm" class="add-btn">Add Account</button>
        </div>
    </div>

<?php }
?>
    