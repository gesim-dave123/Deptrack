<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    // Generate a secure, random token
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
}

$csrf_token = $_SESSION['csrf_token']; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DepTrack - Login</title>
    <link rel = "stylesheet" href="../styles/loginPage.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
   <form action="../handlers/login_handler.php" method="POST" id="loginForm">
        <div class="login-container" id="loginContainer">

            <?php   
            include '../inc/toast.php';     
            ?>
            
            <h1>Welcome to DepTrack!</h1>
            <p class="subtitle">Enter to login</p>
            
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" placeholder="Username" name="username">
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" placeholder="Password" name="password">
                    <span class="toggle-password" onclick="togglePassword()">
                        <i class="fa fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>
            
            <button class="login-button" type="submit">Login</button>
            <a href="../../index.php" class="back-link">Back to Homepage</a>
        </div>
    </form>

    <script>
        // Toggle password visibility
        function togglePassword() {
            // ... (your existing togglePassword function remains unchanged)
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }

        // Shake animation on error
        window.addEventListener('DOMContentLoaded', function() {
            // ... (your existing DOMContentLoaded logic remains unchanged)
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            
            if (error) {
                const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
                
                // Add shake animation and error styling to inputs
                inputs.forEach(input => {
                    input.classList.add('error', 'shake');
                });
                
                // Remove shake class after animation completes
                setTimeout(() => {
                    inputs.forEach(input => {
                        input.classList.remove('shake');
                    });
                }, 600);
                
                // Remove error styling when user starts typing
                inputs.forEach(input => {
                    input.addEventListener('input', function() {
                        this.classList.remove('error');
                    });
                });
            }
        });

        // Client-side validation with shake and toast
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            
            let hasError = false;

            // Check if fields are empty
            if (usernameInput.value.trim() === '' || passwordInput.value.trim() === '') {
                e.preventDefault(); // STOP form submission if fields are empty
                hasError = true;
                
                // Show the Toast Notification
                // NOTE: The showToast function must be defined in 'inc/toast.php'
                showToast('error', 'Error', 'Username and Password are required.', 4000); 
                
                // Apply Shake Animation and Error Styling
                if (usernameInput.value.trim() === '') {
                    usernameInput.classList.add('error', 'shake');
                    setTimeout(() => usernameInput.classList.remove('shake'), 600);
                } else {
                    usernameInput.classList.remove('error'); 
                }
                
                if (passwordInput.value.trim() === '') {
                    passwordInput.classList.add('error', 'shake');
                    setTimeout(() => passwordInput.classList.remove('shake'), 600);
                } else {
                    passwordInput.classList.remove('error');
                }
            }
            
            // If hasError is true, submission is prevented by e.preventDefault()
            // If hasError is false, the form submits to the PHP script (app/login.php).
        });
    </script> 
</html>