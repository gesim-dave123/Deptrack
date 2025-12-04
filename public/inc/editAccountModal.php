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
                <div class="modal-subtitle">Edit Employee details</div>
            </div>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <form id="editEmployeeForm" action="../handlers/editAccount_handler.php" method="POST">
                <!-- First Name and Last Name Row -->
                <div class="form-row">
                    <div class="form-group"> 
                        <label for="edit_firstName">Full Name</label>
                        <input type="text" id="edit_firstName" name="edit_firstName" placeholder="Firstname" >
                    </div>
                    <div class="form-group">
                        <label for="edit_lastName">Last Name</label>
                        <input type="text" id="edit_lastName" name="edit_lastName" placeholder=" Lastname" >
                    </div>
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label for="edit_username">Username</label>
                    <input type="text" id="edit_username" name="edit_username" placeholder="Username" >
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email" name="edit_email" placeholder="Email">
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="edit_password">Password</label>
                    <input type="password" id="edit_password" name="edit_password" placeholder="Password" >
                </div>
                 <!-- id -->
                <div class="form-group">
                    <input type="hidden" id="edit_id" name="edit_id" value="">
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="submit" form="editEmployeeForm" class="add-btn">Save Changes</button>
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
    