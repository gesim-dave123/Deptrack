<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    unset($_SESSION['csrf_token']); // Destroy token on mismatch
        $em = "Invalid Token";
        header("Location: ../../public/pages/login.php?error=$em");
    exit();
}

// Token is valid, destroy it before processing the rest of the login
unset($_SESSION['csrf_token']);

if (isset($_POST['username']) && isset($_POST['password'])) {
    include "../../config/db_connection.php";

    function validate_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validate_input($_POST['username']);
    $password = validate_input($_POST['password']);

    if (empty($username) || empty($password)) {
        $em = "Fields are Required";
        header("Location: ../../public/pages/login.php?error=$em");
        exit();
    }

    $sql = "SELECT 
        u.id,
        u.username,
        u.hashed_password,
        u.full_name,
        u.role_id,
        u.department_id,
        d.department_name
    FROM users u
    LEFT JOIN departments d ON u.department_id = d.department_id
    WHERE u.username = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);

    if ($stmt->rowCount() !== 1) {
        $em = "Account not found";
        header("Location: ../../public/pages/login.php?error=$em");
        exit();
    }

    $user = $stmt->fetch();
    
    if ($username !== $user['username'] || !password_verify($password, $user['hashed_password'])) {
        $em = "Invalid Credentials";
        header("Location: ../../public/pages/login.php?error=$em");
        exit();
    }

    
    $_SESSION['username'] = $user['username'];
    $_SESSION['fullname'] = $user['full_name'];
    $_SESSION['id'] = $user['id'];
    $_SESSION['toast'] = [
        'type' => 'success',
        'title' => 'Success!',
        'message' => 'Logged in Successfully!'
    ];

    // Set role-specific session variables
    switch ($user['role_id']) {
        case 1:
            $_SESSION['role'] = 'Super Admin';
            $_SESSION['department'] = null;
            break;
        case 2:
            $_SESSION['role'] = 'Admin';
            $_SESSION['department'] = $user['department_name'];
            $_SESSION['department_id'] = $user['department_id'];
            break;
        case 3:
            $_SESSION['role'] = 'Employee';
            $_SESSION['department'] = $user['department_name'];
            $_SESSION['department_id'] = $user['department_id'];
            break;
    }

  
    header("Location: ../../public/pages/dashboard.php");
    exit();
}
?>