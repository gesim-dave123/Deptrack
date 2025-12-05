<?php
if (isset($_SESSION['role']) && isset($_SESSION['id'])){

    if (isset($_POST['edit_username']) && isset($_POST['edit_password']) &&
        isset($_POST['edit_firstName']) && isset($_POST['edit_lastName']) && 
        isset($_POST['edit_email']) && isset($_POST['edit_id'])) {
        include "../../config/db_connection.php";

        function validate_input($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $username = validate_input($_POST['edit_username']);
        $password = validate_input($_POST['edit_password']);
        $fullname = validate_input($_POST['edit_firstName'] . ' ' . $_POST['edit_lastName']);
        $email = validate_input($_POST['edit_email']);
        $id = validate_input($_POST['edit_id']);


        if(empty($username) || empty($fullname) || empty($email)){ 
            $em = "Fieldsdsds Required";
            header("Location: ../../public/pages/manage_employee.php?error=$em");
            exit();

        }else{   
            $sql = "SELECT * FROM users WHERE username = ? AND id != ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$username, $id]);
            if($stmt->rowCount() > 0){
                $em = "Username Taken";
                header("Location: ../../public/pages/manage_employees.php?error=$em");
                exit();
            }

            $sql = "SELECT * FROM users WHERE email = ? AND id != ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$email, $id]);
            if($stmt->rowCount() > 0){
                $em = "Email Taken";
                header("Location: ../../public/pages/manage_employees.php?error=$em");
                exit();
            }else{
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $sql = "UPDATE users 
                        SET username = ?, hashed_password = ?, full_name = ?, email = ?
                        WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$username, $hashed_password, $fullname, $email, $id]);
                header("Location:  ../../public/pages/manage_employees.php?success=Account Updated Successfully");
                exit();
            }
        }
    }else{
         $em = "Login  fdfdFirst";
         header("Location: ../../public/pages/login.php?error=$em");
         exit();

        
    }
}else{
    $em = "Login dssdsds First";
     header("Location: ../../public/pages/login.php?error=$em");
    exit();
}

?>
