<?php
if (isset($_POST['username']) && isset($_POST['password'])){
    include "../config/db_connection.php";
    include 'includes/toast.php'; 

    function validate_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validate_input($_POST['username']);
    $password = validate_input($_POST['password']);

    if(empty($username) || empty($password)){
         $em = "Field Required";
         header("Location: ../login.php?error=$em");
         exit();

    }else{   
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

        if( $stmt -> rowCount() == 1){
            $user = $stmt -> fetch();
            $db_password = $user['hashed_password'];
            $db_username = $user['username'];
            $db_role = $user['role_id'];
            $db_fullname = $user['full_name'];
            $db_department = $user['department_name'];
            $db_department_id = $user['department_id'];
            $db_id = $user['id'];
            
            if($username === $db_username){
                if(password_verify($password, $db_password)){
                    if($db_role == 1){
                        session_start();
                        $_SESSION['username'] = $db_username;
                        $_SESSION['role'] = 'Super Admin';
                        $_SESSION['fullname'] = $db_fullname;
                        $_SESSION['id'] = $db_id;
                        $_SESSION['department'] = null;
                        $_SESSION['toast'] = [
                            'type' => 'success',  // success, error, warning, info
                            'title' => 'Success!',
                            'message' => 'Logged in Successfully!'
                        ];
                        header("Location: ../dashboard.php");
                        exit();
                    }else if($db_role == 2){
                        session_start();
                        $_SESSION['username'] = $db_username;
                        $_SESSION['role'] = 'Admin';
                        $_SESSION['department'] = $db_department;
                        $_SESSION['department_id'] = $db_department_id;
                        $_SESSION['fullname'] = $db_fullname;
                        $_SESSION['id'] = $db_id;
                         $_SESSION['toast'] = [
                            'type' => 'success',  // success, error, warning, info
                            'title' => 'Success!',
                            'message' => 'Logged in Successfully!'
                        ];
                        header("Location: ../dashboard.php");
                        exit();
                    }else if($db_role == 3){
                        session_start();
                        $_SESSION['username'] = $db_username;
                        $_SESSION['role'] = 'Employee';
                        $_SESSION['department'] = $db_department;
                        $_SESSION['department_id'] = $db_department_id;
                        $_SESSION['fullname'] = $db_fullname;
                        $_SESSION['id'] = $db_id;
                        $_SESSION['toast'] = [
                            'type' => 'success',  // success, error, warning, info
                            'title' => 'Success!',
                            'message' => 'Logged in Successfully!'
                        ];
                        header("Location: ../dashboard.php");
                        exit();
                  }
                }else{
                    $em = "Incorrect Password";
                    header("Location: ../login.php?error=$em");
                    exit();
                }
            }else{
                $em = "Incorrect Username";
                header("Location: ../login.php?error=$em");
                exit();

            }
        }else{
            $em = "Incorrect Username or Password";
            header("Location: ../login.php?error=$em");
            exit();
          }
  
    }

}else{

    
}

?>