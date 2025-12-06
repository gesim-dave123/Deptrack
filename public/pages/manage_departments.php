<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){
    include '../../config/db_connection.php';
    include '../../app/controllers/users.php';

    $departments =get_all_department($conn);
    
    // Assuming get_notifications is defined and available
    $taskData = get_notifications($conn, $_SESSION['id']); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments</title>
    <link rel="stylesheet" href="../styles/manageDepartments.css?v=4.0">
    <link rel="stylesheet" href="../styles/nav.css?v=1.0">
</head>
<body>
    <?php include '../inc/nav.php'; ?>
    <?php include '../inc/toast.php'; ?>
    

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Manage Departments</h1>
            <button class="add-department-btn" onclick="openAddModal()">
                <span>+</span> Add Department
            </button>
        </div>

        <div class="departments-grid" id="departmentsGrid">
            </div>
    </main>

    <div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>

    <div class="modal" id="viewDepartmentModal">
        <div class="modal-header">
            <div class="modal-title">
                <h2 id="viewDeptName">Department Details</h2>
                <div class="modal-subtitle">View department information</div>
            </div>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>

        <div class="modal-body">
            <div class="detail-item">
                <span class="detail-label">Department Name</span>
                <div class="detail-value" id="viewDeptNameValue"></div>
            </div>

            <div class="detail-item">
                <span class="detail-label">Description</span>
                <div class="detail-value" id="viewDeptDescription"></div>
            </div>

            <div class="detail-item">
                <span class="detail-label">Number of Employees</span>
                <div class="detail-value" id="viewDeptEmployees"></div>
            </div>

            <div class="detail-item">
                <span class="detail-label">Assigned Admin</span>
                <div class="detail-value" id="viewDeptAdmin"></div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal()">Close</button>
            <button class="btn-submit" onclick="editDepartment()">Edit Department</button>
        </div>
    </div>

    <div class="modal" id="addDepartmentModal">
        <div class="modal-header">
            <div class="modal-title">
                <h2>Add Department & Admin</h2>
                <div class="modal-subtitle">Create a new department and assign an admin</div>
            </div>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>

        <div class="modal-body">
            <form id="addDepartmentForm" action>
                <div class="section-header">📋 Department Information</div>

                <div class="form-group">
                    <label for="deptName">Department Name *</label>
                    <input type="text" id="deptName" name="deptName" placeholder="e.g., Engineering, Marketing" required>
                </div>

                <div class="form-group">
                    <label for="deptDescription">Description *</label>
                    <textarea id="deptDescription" name="deptDescription" placeholder="Describe the department's responsibilities and purpose" required></textarea>
                </div>

                <div class="form-group icon-selector">
                    <label>Select Department Icon *</label>
                    <div class="help-text">Choose an emoji that represents this department</div>
                    <input type="hidden" id="selectedIcon" name="selectedIcon" required>
                    <div class="icon-grid" id="iconGrid">
                        </div>
                </div>

                <div class="section-divider"></div>

                <div class="section-header">👤 Create Admin Account</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="adminFirstName">First Name *</label>
                        <input type="text" id="adminFirstName" name="adminFirstName" placeholder="First name" required>
                    </div>
                    <div class="form-group">
                        <label for="adminLastName">Last Name *</label>
                        <input type="text" id="adminLastName" name="adminLastName" placeholder="Last name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="adminUsername">Username *</label>
                    <input type="text" id="adminUsername" name="adminUsername" placeholder="Username for login" required>
                </div>

                <div class="form-group">
                    <label for="adminEmail">Email *</label>
                    <input type="email" id="adminEmail" name="adminEmail" placeholder="admin@company.com" required>
                </div>

                <div class="form-group">
                    <label for="adminPassword">Password *</label>
                    <input type="password" id="adminPassword" name="adminPassword" placeholder="Create a secure password" required>
                    <div class="help-text">Password must be at least 8 characters</div>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="btn-submit" onclick="addDepartment()">Create Department & Admin</button>
        </div>
    </div>

    <script>
        const departmentIcons = [
            '💻', '🏢', '📊', '💰', '📈', '🎯', '🔧', '⚙️', '🎨', '📱',
            '🌐', '📞', '🎓', '🏥', '🔬', '📦', '🚚', '🏭', '🛠️', '📢'
        ];
    
        const departments = <?php echo json_encode($departments); ?>;

        let selectedDepartment = null;
        let selectedIcon = null;

        // Render department cards - UPDATED STRUCTURE
        function renderDepartments() {
            const grid = document.getElementById('departmentsGrid');
            
            if (departments.length === 0) {
                grid.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">🏢</div>
                        <div class="empty-state-text">No departments yet. Click "Add Department" to create one.</div>
                    </div>
                `;
                return;
            }

            grid.innerHTML = departments.map(dept => `
                <div class="department-card" onclick="viewDepartment(${dept.department_id})">
                    <div class="card-header">
                        <div class="department-icon-placeholder">${dept.department_icon}</div>
                        <div class="department-name">${dept.department_name}</div>
                    </div>
                    <div class="department-description">${dept.department_description.substring(0, 70) + (dept.department_description.length > 70 ? '...' : '')}</div>
                    <div class="department-stats">
                        <div class="stat-item">
                            <span class="stat-label">Employees</span>
                            <span class="stat-value">${dept.user_count}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Admin</span>
                            <span class="stat-value">${dept.admin_name.split(' ')[0]}</span>
                        </div>
                        <div class="card-action-btn">View Details &rarr;</div>
                    </div>
                </div>
            `).join('');
        }

        // --- Other JavaScript functions (renderIconSelector, selectIcon, viewDepartment, openAddModal, addDepartment, editDepartment, closeModal) remain the same ---

        // Render icon selector
        function renderIconSelector() {
            const iconGrid = document.getElementById('iconGrid');
            iconGrid.innerHTML = departmentIcons.map(icon => `
                <div class="icon-option" onclick="selectIcon('${icon}')">${icon}</div>
            `).join('');
        }

        // Select icon
        function selectIcon(icon) {
            selectedIcon = icon;
            document.getElementById('selectedIcon').value = icon;
            
            // Update visual selection
            document.querySelectorAll('.icon-option').forEach(el => {
                el.classList.remove('selected');
                if (el.textContent === icon) {
                    el.classList.add('selected');
                }
            });
        }

        // View department details
        function viewDepartment(id) {
            selectedDepartment = departments.find(d => d.department_id === id);
            if (!selectedDepartment) return;

            document.getElementById('viewDeptName').textContent = selectedDepartment.department_name;
            document.getElementById('viewDeptNameValue').textContent = selectedDepartment.department_name;
            document.getElementById('viewDeptDescription').textContent = selectedDepartment.department_description;
            document.getElementById('viewDeptEmployees').textContent = selectedDepartment.user_count;
            document.getElementById('viewDeptAdmin').textContent = selectedDepartment.admin_name;

            document.getElementById('modalOverlay').classList.add('active');
            document.getElementById('viewDepartmentModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Open add department modal
        function openAddModal() {
            document.getElementById('addDepartmentForm').reset();
            selectedIcon = null;
            document.querySelectorAll('.icon-option').forEach(el => el.classList.remove('selected'));
            renderIconSelector();
            document.getElementById('modalOverlay').classList.add('active');
            document.getElementById('addDepartmentModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Add new department
       // ... existing JavaScript variables and functions ...

// New function to handle department creation and submission
// ... (lines 700-725 in manage_departments.php)

// New function to handle department creation and submission
function addDepartment() {
    // 1. Get Department Information
    const deptName = document.getElementById('deptName').value.trim();
    const deptDescription = document.getElementById('deptDescription').value.trim();
    const icon = document.getElementById('selectedIcon').value;
    
    // 2. Get Admin Information
    const firstName = document.getElementById('adminFirstName').value.trim();
    const lastName = document.getElementById('adminLastName').value.trim();
    const username = document.getElementById('adminUsername').value.trim();
    const email = document.getElementById('adminEmail').value.trim();
    const password = document.getElementById('adminPassword').value;
    const fullName= firstName + " "+ lastName; // CRITICAL: This variable must be defined here.

    // 3. Validation (Keep your existing validation logic here)
    if (!deptName || !deptDescription || !icon) {
        alert('Please fill in all department information and select an icon');
        return;
    }
    
    if (!firstName || !lastName || !username || !email || !password) {
        alert('Please fill in all admin account information');
        return;
    }

    if (password.length < 8) {
        alert('Password must be at least 8 characters');
        return;
    }

    // 4. Prepare the Data Payload (matching the JSON format)
    const departmentData = {
        action: 'createDepartment', 
        department: {
            name: deptName,
            description: deptDescription,
            icon: icon
        },
        admin: {
            fullName: fullName,
            username: username,
            email: email,
            password: password,
            role: 2 
        }
    };

    // 5. Send Data to PHP Backend using Fetch
    fetch('../../app/addDepartment.php', { // NOTE: Corrected path from your previous attempt
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(departmentData)
    })
    .then(response => {
        // Handle non-JSON responses (for debugging)
        if (!response.ok) {
            console.error('HTTP Error:', response.statusText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        console.log('Server Response:', result);
        if (result.success) {
            
            const newDeptId = result.department_id; 
            
            // 🐛 FIX APPLIED HERE: Use the database-style keys for the local object
            const newDept = {
                department_id: newDeptId, 
                department_name: deptName,
                department_description: deptDescription,
                user_count: 1, // Initial employee count is 0
                admin_name: fullName,
                department_icon: icon
            };
            
            departments.push(newDept); 
            renderDepartments();       
            closeModal();             
            alert(`Department "${deptName}" created successfully!`);

        } else {
            
            console.error('Failed to create department:', result.error);
        }
    })
    .catch(error => {
        console.error('Network Error during department creation:', error);
        alert('An unexpected error occurred. Check the console for details.');
    });
}
        // Edit department (placeholder)
        function editDepartment() {
            alert('Edit functionality would open an edit form here');
        }

        // Close modal
        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('active');
            document.getElementById('viewDepartmentModal').classList.remove('active');
            document.getElementById('addDepartmentModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            selectedDepartment = null;
            selectedIcon = null;
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Initial render
        renderDepartments();
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