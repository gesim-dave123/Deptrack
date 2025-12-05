<?php
$role = $_SESSION['role'];
if($role != 'Super Admin'){?>
    <div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>

    <!-- Edit Employee Modal (for Admin/Employee) -->
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
                        <label for="edit_firstName">First Name</label>
                        <input type="text" id="edit_firstName" name="edit_firstName" placeholder="Firstname">
                    </div>
                    <div class="form-group">
                        <label for="edit_lastName">Last Name</label>
                        <input type="text" id="edit_lastName" name="edit_lastName" placeholder="Lastname">
                    </div>
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label for="edit_username">Username</label>
                    <input type="text" id="edit_username" name="edit_username" placeholder="Username">
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email" name="edit_email" placeholder="Email">
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="edit_password">Password</label>
                    <input type="password" id="edit_password" name="edit_password" placeholder="Leave blank to keep current password">
                </div>
                
                <!-- Hidden ID -->
                <input type="hidden" id="edit_id" name="edit_id" value="">
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="submit" form="editEmployeeForm" class="add-btn">Save Changes</button>
        </div>
    </div>
    
<?php } else { ?>
    
    <!-- Edit Account Modal (for Super Admin) -->
    <div class="modal" id="editEmployeeModal">
        <!-- Modal Header -->
        <div class="modal-header">
            <div class="modal-title">
                <h2>Edit Account</h2>
                <div class="modal-subtitle">Edit Account details</div>
            </div>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <form id="editEmployeeForm" action="../handlers/editAccount_handler.php" method="POST">
                <!-- First Name and Last Name Row -->
                <div class="form-row">
                    <div class="form-group"> 
                        <label for="edit_firstName">First Name</label>
                        <input type="text" id="edit_firstName" name="edit_firstName" placeholder="Firstname">
                    </div>
                    <div class="form-group">
                        <label for="edit_lastName">Last Name</label>
                        <input type="text" id="edit_lastName" name="edit_lastName" placeholder="Lastname">
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
                    <input type="email" id="edit_email" name="edit_email" placeholder="Email" >
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="edit_password">Password</label>
                    <input type="password" id="edit_password" name="edit_password" placeholder="Leave blank to keep current password">
                </div>

                
                
                <!-- Hidden ID -->
                <input type="hidden" id="edit_id" name="edit_id" value="">
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="submit" form="editEmployeeForm" class="add-btn">Save Changes</button>
        </div>
    </div>

<?php } ?>