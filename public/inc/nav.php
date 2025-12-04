


<button class="mobile-toggle" onclick="toggleMobileSidebar()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <div class="overlay" onclick="toggleMobileSidebar()"></div>

    <div class="sidebar" id="sidebar">
        <button class="toggle-btn" onclick="toggleSidebar()">
            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
       
        <div class="profile-section">
            <div class="dept-label">
                 <?php echo $_SESSION['department']; ?>
            </div>
            <div class="profile-avatar"></div>
            <div class="profile-name">
                   <?php echo $_SESSION['fullname']; ?> 
            </div>
            <div class="profile-role">
                <?php echo $_SESSION['role']; ?> 
            </div>
        </div>

        <!-- Employee Navigation Bar -->
         <?php
            $user = $_SESSION['role'];

            if($user == "Employee"){
         
         ?>
        
        <nav class="nav-menu">
            <div class="nav-item">
                <a href="dashboard.php">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="my_task.php">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span>My Tasks</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="task_history.php">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span>Task History</span>
                </a>
            </div>
            <div class="nav-item">
                <span class="nav-badge" id="notificationBadge">0</span>
                <a href="notification.php">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                
                
                <span>Notifications</span>
                
                 </a>
                
            </div>
                 <!-- Admin Navigation Bar -->
            <?php }else if ($user == "Admin"){?>

            <nav class="nav-menu">
            <div class="nav-item ">
                <a href="dashboard.php">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="manage_tasks.php" >
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span>Manage Tasks</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="manage_employees.php">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span>Manage Employees</span>
                </a>
                
            </div>
            <div class="nav-item">
                <a href="notification.php">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span>Notifications</span>
                </a>
                
            </div>
                 <!-- Super Admin Navigation Bar -->
            <?php }else { ?>

            <nav class="nav-menu">
            <div class="nav-item <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                <a href="dashboard.php">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="leaderboards.php" >
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span>Leaderboards</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="manage_departments.php" class=>
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 8v-4m0 4h4"></path>
                </svg>
                <span>Manage Departments</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="manage_accounts.php" class=>
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 00-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 010 7.75"></path>
                </svg>
                <span>Manage Accounts</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="notification.php">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span>Notifications</span>
                </a>
                
            </div>
            <?php } ?>  
                  
        </nav>
        
        <div class="nav-item" id="logoutBtn">
            <a href="../handlers/logout_handler.php">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <span>Logout</span>
            </a>
        </div>
    </div>

    <script>
        const tasksData = <?php echo json_encode($taskData); ?>;
        const activePage = window.location.pathname.split("/").pop();
        const navItems = document.querySelectorAll(".nav-item a");
        navItems.forEach(item => {
            const itemPage = item.getAttribute("href");
            if (itemPage === activePage) {
                item.parentElement.classList.add("active");
            }
        });
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }
        function updateBadges() {  
            const unreadNotifications = tasksData.filter(task => task.is_read == 0).length;
            const notificationBadge = document.getElementById('notificationBadge');
            notificationBadge.textContent = unreadNotifications;
            notificationBadge.classList.toggle('hide', unreadNotifications === 0);
        }

        // Call on page load
        updateBadges();

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.overlay');
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.querySelector('.overlay');
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            }
        });
        
    </script>

<!-- <?php  ?> -->