<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){

    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email'])) {
        include "../config/db_connection.php";

        function validate_input($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $username = validate_input($_POST['username']);
        $password = validate_input($_POST['password']);
        $fullname = validate_input($_POST['firstName'] . ' ' . $_POST['lastName']);
        $email = validate_input($_POST['email']);
        $department_id = $_SESSION['department_id'];
        $role_id = 3; // Default role for Employee
        $created_by = $_SESSION['id'];


        if(empty($username) || empty($password) || empty($fullname) || empty($email)){
            $em = "Field Required";
            header("Location: ../login.php?error=$em");
            exit();

        }else{   
            $sql_username = "SELECT id FROM users WHERE username = ?";
            $stmt_username = $conn->prepare($sql_username);
            $stmt_username->execute([$username]);
            $result_username = $stmt_username->fetchAll();

            // Check email
            $sql_email = "SELECT id FROM users WHERE email = ?";
            $stmt_email = $conn->prepare($sql_email);
            $stmt_email->execute([$email]);
            $result_email = $stmt_email->fetchAll();

            if( count($result_username ) > 0){
                $em = "Username Taken";
                header("Location: ../manage_employees.php?error=$em");
                exit();
            }else if( count ($result_email) > 0){
                $em = "Email Taken";
                header("Location: ../manage_employees.php?error=$em");
                exit();
            }else{
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $role_id = 3; // Default role for Employee
                $sql = "INSERT INTO users (username, hashed_password, full_name, email, role_id, department_id,created_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$username, $hashed_password, $fullname, $email, $role_id,$department_id, $created_by]);
                header("Location: ../manage_employees.php?success=Account Created Successfully");
                exit();
            }
        }
    }else{
         $em = "Login First";
         header("Location: ../manage_employees.php?error=$em");
         exit();

        
    }
}else{
    $em = "Login First";
    header("Location: ../login.php?error=$em");
    exit();
}

?>
