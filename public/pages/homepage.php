<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DepTrack - Streamline Team Productivity</title>
    <link rel="stylesheet" href="../styles/homepage.css?v=2.1">
</head>
<body>
    <header>
        <div class="logo">
            <div class="logo-icon">
                <img src ="public/images/logo.png">
            </div>
            <span>DepTrack</span>
        </div>
        <nav>
            <a href="">Home</a>
            <a href="#about">About Us</a>
            <a href="#feature">Feature</a>
            <a href="#team">Contact us</a>
        </nav>
    </header>  
    <section class="hero">  
        <div class="hero-content">
            <h1>
                <span class="green-text">Streamline</span>
                <span class="dark-text">and</span>
                <span class="green-text">Optimize</span>
                <span class="dark-text">team productivity</span>
            </h1>
            <p>Manage tasks effortlessly, track progress in real-time, and collaborate with your team using our powerful yet simple task management solution.</p>
           <button class="cta-button" onclick="window.location.href='public/pages/login.php'">Get Started</button>
        </div>

        <div class="image">
            <img src="public/images/HomepageImage.png" id="homeImg">
        </div>
    </section>

    <!-- About Us Section -->
    <section class="about-section" id="about">
        <div class="about-container">
            <h2>About Us</h2>
            <div class="about-content">
                <div class="about-icon">
                    <img src="public/images/building.png" alt="Task Icon" width="50" height="50">
                </div>
                <p class="about-text">
                     DepTrack is an information system designed to support effective task coordination within an organization. It enables departments to efficiently assign tasks, track task status, and monitor employee performance through a structured and user-friendly platform.
                </p>

                <div class="about-highlights">
                    <div class="highlight-item">
                        <div class="highlight1-icon">
                             <img src="public/images/bullseye-arrow.svg" alt="Task Icon" width="35" height="35" fill="#0b8766">
                        </div>
                        <h4>Efficient Coordination</h4>
                        <p>Streamline task assignment and collaboration across departments</p>
                    </div>

                    <div class="highlight-item">
                       <div class="highlight1-icon">
                             <img src="public/images/chart-line-up.svg" alt="Task Icon" width="35" height="35" fill="#0b8766">
                        </div>
                        <h4>Real-Time Tracking</h4>
                        <p>Monitor task progress and status updates in real-time</p>
                    </div>

                    <div class="highlight-item">
                        <div class="highlight1-icon">
                             <img src="public/images/user-suitcase.svg" alt="Task Icon" width="35" height="35" fill="#0b8766">
                        </div>
                        <h4>Performance Insights</h4>
                        <p>Track employee performance and productivity metrics</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="features-section" id="feature">
        <div class="features-header">
            <h2>Key Features</h2>
            <p>Streamline your department's workflow with these powerful task management capabilities</p>
        </div>

        <div class="features-grid">
            <!-- Feature 1: Department-Based Organization -->
            <div class="feature-card">
                <div class="feature-card-icon">
                    <img src="public/images/department-structure.svg" alt="Task Icon" width="35" height="35" fill="#0b8766">
                </div>
                <h3>Department-Based Organization</h3>
                <p>Keep your tasks organized by department for better focus and security. </p>
            </div>

            <!-- Feature 2: Role-Based Access Control -->
            <div class="feature-card">
                <div class="feature-card-icon">
                    <img src="public/images/padlock-check.svg" alt="Task Icon" width="35" height="35" fill="#0b8766">
                </div>
                <h3>Role-Based Access Control</h3>
                <p>Secure access levels tailored to each user's role and responsibilities.</p>
            </div>

            <!-- Feature 3: Task Creation & Assignment -->
            <div class="feature-card">
                <div class="feature-card-icon">
                    <img src="public/images/task-checklist.svg" alt="Task Icon" width="35" height="35" fill="#0b8766">
                </div>
                <h3>Task Creation & Assignment</h3>
                <p>Create detailed tasks and assign them to the right team members effortlessly.</p>
            </div>

            <!-- Feature 4: Task Status Tracking -->
            <div class="feature-card">
                <div class="feature-card-icon">
                    <img src="public/images/list-check.svg" alt="Task Icon" width="35" height="35" fill="#0b8766">
                </div>
                <h3>Task Status Tracking</h3>
                <p>Monitor task progress with comprehensive status tracking.</p>
            </div>

            <!-- Feature 5: Notifications & Alerts -->
            <div class="feature-card">
                 <div class="feature-card-icon">
                    <img src="public/images/bell-ring.svg" alt="Task Icon" width="35" height="35" fill="#0b8766">
                </div>
                <h3>Notifications & Alerts</h3>
                <p>Stay informed with email and in-system notifications for new task assignments, deadline reminders, and task status changes.</p>
            </div>

            <!-- Feature 6: Dashboard & Progress Overview -->
            <div class="feature-card">
               <div class="feature-card-icon">
                    <img src="public/images/stats.svg" alt="Task Icon" width="35" height="35" fill="#0b8766">
                </div>
                <h3>Dashboard & Progress Tracking</h3>
                <p>Monitor productivity with visual insights and real-time progress updates.</p>
            </div>
        </div>
    </section>

         <section class="about-section" id="team">
        <div class="about-header">
            <h2>Meet Our Team</h2>
            <p>The passionate individuals behind DepTrack who are dedicated to revolutionizing task management</p>
        </div>
        <div class="team-grid">
            <!-- Team Member 1 -->
            <div class="team-card">
                <div class="team-photo"><img src="public/images/user.svg" alt="Task Icon" width="100" height="100" ></div>
                <h3 class="team-name">Christian Dave Gesim</h3>
                <p class="team-role">Full Stack Developer</p>
                <p class="team-description">Full-stack developer passionate about creating intuitive and powerful solutions.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><img src="public/images/facebook-svgrepo-com.svg" alt="Task Icon" width="27" height="27" ></a>
                    <a href="#" class="social-link"><img src="public/images/github-svgrepo-com.svg" alt="Task Icon" width="27" height="27" ></a>
                    <a href="#" class="social-link"><img src="public/images/gmail-svgrepo-com.svg" alt="Task Icon" width="27" height="27" ></a>
                </div>
            </div>

            <!-- Team Member 2 -->
            <div class="team-card">
                 <div class="team-photo"><img src="public/images/user.svg" alt="Task Icon" width="100" height="100" ></div>
                <h3 class="team-name">Christ Hanzen Rallos</h3>
                <p class="team-role">Frontend Developer</p>
                <p class="team-description">Frontend developer passionate about creating intuitive and responsive user interfaces.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><img src="public/images/facebook-svgrepo-com.svg" alt="Task Icon" width="27" height="27" ></a>
                    <a href="#" class="social-link"><img src="public/images/github-svgrepo-com.svg" alt="Task Icon" width="27" height="27" ></a>
                    <a href="#" class="social-link"><img src="public/images/gmail-svgrepo-com.svg" alt="Task Icon" width="27" height="27" ></a>
                </div>
            </div>

            <!-- Team Member 3 -->
            <div class="team-card">
                 <div class="team-photo"><img src="public/images/user.svg" alt="Task Icon" width="100" height="100" ></div>
                <h3 class="team-name">Andrew Gabriel Belandres</h3>
                <p class="team-role">UX/UI Designer</p>
                <p class="team-description">Creative designer focused on delivering exceptional user experiences and intuitive interfaces.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><img src="public/images/facebook-svgrepo-com.svg" alt="Task Icon" width="27" height="27" ></a>
                    <a href="#" class="social-link"><img src="public/images/github-svgrepo-com.svg" alt="Task Icon" width="27" height="27" ></a>
                    <a href="#" class="social-link"><img src="public/images/gmail-svgrepo-com.svg" alt="Task Icon" width="27" height="27" ></a>
                </div>
            </div>

            <!-- Team Member 4 -->
            <div class="team-card">
                 <div class="team-photo"><img src="public/images/user.svg" alt="Task Icon" width="100" height="100" ></div>
                <h3 class="team-name">Jessa Jean Dinopol</h3>
                <p class="team-role">Quality Assurance</p>
                <p class="team-description">QA specialist ensuring software quality through rigorous testing and process improvement</p>
                <div class="social-links">
                    <a href="#" class="social-link"><img src="public/images/facebook-svgrepo-com.svg" alt="Task Icon" width="27" height="27" ></a>
                    <a href="#" class="social-link"><img src="public/images/github-svgrepo-com.svg" alt="Task Icon" width="27" height="27" ></a>
                    <a href="#" class="social-link"><img src="public/images/gmail-svgrepo-com.svg" alt="Task Icon" width="27" height="27" ></a>
                </div>
            </div>
        </div>
    </section>



</body>
</html>