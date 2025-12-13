<?php?>
<div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteEmployeeModal">
        <!-- Modal Header -->
        <div class="modal-header">
            <div class="modal-title">
                <h2>Delete Employee</h2>
            </div>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <div class="warning-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            
            <div class="delete-message">
                <h3>Are you sure you want to delete this employee?</h3>
                <p>This action cannot be undone. All employee data will be permanently removed from the system.</p>
            </div>

            <div class="employee-info">
                <p><strong>Name:</strong> <span id="employeeName">John Doe</span></p>
                <p><strong>Username:</strong> <span id="employeeUsername">johndoe</span></p>
                <p><strong>Email:</strong> <span id="employeeEmail">john.doe@example.com</span></p>
                <form id="taskForm" action="../handlers/deleteAccount_handler.php" method="POST">
                <input type="hidden" id="delete_id" name="delete_id" value="">
                <input type="hidden" id="delete_role_name" name="delete_role_name" value="">
                </form>
            </div>
        </div>

        <!-- Modal Footer -->
       <div class="modal-footer">
            <button class="cancel-btn" onclick="closeModal()">Cancel</button>
            
            <button class="delete-btn" id="confirmDeleteButton" type="submit" form="taskForm">Confirm Delete</button>
        </div>
    </div>