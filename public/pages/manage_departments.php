<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){
     include '../../config/db_connection.php';
    include '../../app/controllers/users.php';
    
    $taskData = get_notifications($conn, $_SESSION['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments</title>
    <link rel="stylesheet" href="../styles/manageDepartments.css?v=2.0">
    <link rel="stylesheet" href="../styles/nav.css?v=1.0">
</head>
<body>
    <?php include '../inc/nav.php'; ?>
   

    <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Manage Departments</h1>
                <button class="add-department-btn" onclick="openAddModal()">
                    <span>+</span> Add Department
                </button>
            </div>

            <!-- Departments Grid -->
            <div class="departments-grid" id="departmentsGrid">
                <!-- Department cards will be inserted here -->
            </div>
        </main>
    </div>

    <!-- Modal Overlay -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>

    <!-- View Department Details Modal -->
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

    <!-- Add Department Modal -->
    <div class="modal" id="addDepartmentModal">
        <div class="modal-header">
            <div class="modal-title">
                <h2>Add Department & Admin</h2>
                <div class="modal-subtitle">Create a new department and assign an admin</div>
            </div>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>

        <div class="modal-body">
            <form id="addDepartmentForm">
                <!-- Department Information Section -->
                <div class="section-header">📋 Department Information</div>

                <div class="form-group">
                    <label for="deptName">Department Name *</label>
                    <input type="text" id="deptName" name="deptName" placeholder="e.g., Engineering, Marketing" required>
                </div>

                <div class="form-group">
                    <label for="deptDescription">Description *</label>
                    <textarea id="deptDescription" name="deptDescription" placeholder="Describe the department's responsibilities and purpose" required></textarea>
                </div>

                <!-- Icon Selector -->
                <div class="form-group icon-selector">
                    <label>Select Department Icon *</label>
                    <div class="help-text">Choose an icon that represents this department</div>
                    <input type="hidden" id="selectedIcon" name="selectedIcon" required>
                    <div class="icon-grid" id="iconGrid">
                        <!-- Icons will be inserted here -->
                    </div>
                </div>

                <div class="section-divider"></div>

                <!-- Admin Account Section -->
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

        // Sample department data
        const departments = [
            {
                id: 1,
                name: 'Engineering',
                description: 'Responsible for software development, system architecture, and technical infrastructure',
                employees: 24,
                admin: 'John Doe',
                icon: '💻'
            },
            {
                id: 2,
                name: 'Human Resources',
                description: 'Manages employee relations, recruitment, and organizational development',
                employees: 8,
                admin: 'Jane Smith',
                icon: '👥'
            },
            {
                id: 3,
                name: 'Marketing',
                description: 'Handles brand strategy, digital marketing, and customer engagement',
                employees: 15,
                admin: 'Mike Johnson',
                icon: '📢'
            }
        ];

        let selectedDepartment = null;
        let selectedIcon = null;

        // Render department cards
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
                <div class="department-card" onclick="viewDepartment(${dept.id})">
                    <div class="department-icon">${dept.icon}</div>
                    <div class="department-name">${dept.name}</div>
                    <div class="department-stats">
                        <div class="stat-item">
                            <span class="stat-label">Employees</span>
                            <span class="stat-value">${dept.employees}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Admin</span>
                            <span class="stat-value">${dept.admin.split(' ')[0]}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

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
            selectedDepartment = departments.find(d => d.id === id);
            if (!selectedDepartment) return;

            document.getElementById('viewDeptName').textContent = selectedDepartment.name;
            document.getElementById('viewDeptNameValue').textContent = selectedDepartment.name;
            document.getElementById('viewDeptDescription').textContent = selectedDepartment.description;
            document.getElementById('viewDeptEmployees').textContent = selectedDepartment.employees;
            document.getElementById('viewDeptAdmin').textContent = selectedDepartment.admin;

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
        function addDepartment() {
            // Get department info
            const deptName = document.getElementById('deptName').value.trim();
            const deptDescription = document.getElementById('deptDescription').value.trim();
            const icon = document.getElementById('selectedIcon').value;
            
            // Get admin info
            const firstName = document.getElementById('adminFirstName').value.trim();
            const lastName = document.getElementById('adminLastName').value.trim();
            const username = document.getElementById('adminUsername').value.trim();
            const email = document.getElementById('adminEmail').value.trim();
            const password = document.getElementById('adminPassword').value;

            // Validation
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

            // Create new department with admin
            const newDept = {
                id: departments.length + 1,
                name: deptName,
                description: deptDescription,
                employees: 0,
                admin: `${firstName} ${lastName}`,
                icon: icon
            };

            // In a real application, you would send this to your backend:
            const departmentData = {
                department: {
                    name: deptName,
                    description: deptDescription,
                    icon: icon
                },
                admin: {
                    firstName: firstName,
                    lastName: lastName,
                    username: username,
                    email: email,
                    password: password,
                    role: 'Admin'
                }
            };

            console.log('Creating department and admin:', departmentData);

            departments.push(newDept);
            renderDepartments();
            closeModal();
            alert(`Department "${deptName}" created successfully!\nAdmin account created for ${firstName} ${lastName}`);
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