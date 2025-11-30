
  <div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>

    <!-- Modal -->
    <div class="modal" id="taskModal">
        <!-- Modal Header -->
        <div class="modal-header">
            <div class="modal-title">
                <h2>Add Task</h2>
                <div class="modal-subtitle">Enter task details</div>
            </div>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <form id="taskForm" action="../handlers/createTask_handler.php" method="POST">
                <!-- Task Title -->
                <div class="form-group">
                    <label for="taskTitle">Task Title</label>
                    <input type="text" id="taskTitle" name="taskTitle" placeholder="Task Title">
                    <div class="error-message" id="titleError">Task title is required</div>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Description"></textarea>
                    <div class="error-message" id="descriptionError">Description is required</div>
                </div>

                <!-- Deadline and Priority Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="deadline">Deadline</label>
                        <input type="date" id="deadline" name="deadline" placeholder="Deadline">
                        <div class="error-message" id="deadlineError">Deadline is required</div>
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority" required>
                            
                            <option value="">--Select Priority--</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                        <div class="error-message" id="priorityError">Priority is required</div>
                    </div>
                </div>
 
                <!-- Assign to -->
                <div class="form-group">
                    <label for="assignTo">Assign to</label>
                    <select id="assignTo" name="assignTo" required>                  
                        <option value="">--Select Employee--</option>
                        <?php foreach($employees as $employee){ ?>
                            <option value="<?= $employee['id'] ?>"><?= htmlspecialchars($employee['full_name']) ?></option>
                        <?php } ?>
                    </select>
                    <div class="error-message" id="assignError">Please select an employee</div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="submit" form="taskForm" class="save-btn">Save Changes</button>
        </div>
    </div>

