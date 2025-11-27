<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboards</title>
    <link rel="stylesheet" href="styles/leaderboards.css?v=2.0">
    <link rel="stylesheet" href="styles/nav.css?v=1.0">
</head>
<body>
    <?php include 'inc/nav.php'; ?>
    
    <div class="main-content">
        <h1 class="page-title">Leaderboards</h1>
        
        <div class="dashboard-grid">
        
        </div>
    </div>
</body>
</html>
<?php 
} else {
    $em = "Login First";
    header("Location: ../login.php?error=$em");
    exit();
}
?>