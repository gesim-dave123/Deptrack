<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DepTrack - Login</title>
    <link rel = "stylesheet" href="styles/loginPage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <form action="app/login.php" method="POST">
      <div class="login-container">
          <h1>Welcome to DepTrack!</h1>
          <p class="subtitle">Enter to login</p>
          <!-- <?php
          //  $pass = "user1234";
          //  $pass = password_hash($pass, PASSWORD_DEFAULT);
          //  echo $pass;
          ?> -->
          <div class="input-group">
              <label for="username">Username</label>
              <input type="text" id="username" placeholder="Username" name ="username">
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
          <!-- <button class="login-button" onclick="window.location.href='dashboard.php'">Login</button> -->

          <a href="homepage.php" class="back-link">Back to Homepage</a>
      </div>
    </form>
</body>

<script>
function togglePassword() {
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
</script>
</html>