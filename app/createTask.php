<?php
if (isset($_SESSION['role']) && isset($_SESSION['id'])){

    if (isset($_POST['taskTitle']) && isset($_POST['description']) && isset($_POST['deadline']) && isset($_POST['priority']) && isset($_POST['assignTo'])) {
        include "../../config/db_connection.php";

        function validate_input($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $title = validate_input($_POST['taskTitle']);
        $description = validate_input($_POST['description']);
        $deadline = validate_input($_POST['deadline']);
        $priority = validate_input($_POST['priority']);
        $assignTo = validate_input($_POST['assignTo']); 
        $created_by = $_SESSION['id'];

        if(empty($title) || empty( $description) || empty($deadline) || empty($priority) || empty($assignTo)){
            $em = "Field Required";
            header("Location: ../../public/pages/manage_tasks.php?error=$em");
            exit();
            
        }else{   
                $type= "Task Created";
                $status="Success";
                $created_by_name = $_SESSION['fullname'];
                $assigned_by = $_SESSION['id']; 
                $department_id = $_SESSION['department_id'];
                $sql = "INSERT INTO tasks (title, description, due_date, priority, assigned_to, assigned_by, department_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$title, $description, $deadline, $priority, $assignTo, $assigned_by, $department_id]);

                $task_id = $conn->lastInsertId();

                $sql_notification = "INSERT INTO notification (message, recepient_id, type,date,task_id,created_by,status) VALUES (?, ?, ?, ?, ?,?,?)";
                $stmt = $conn->prepare($sql_notification);
                $stmt->execute([$description,$assignTo,$type,$deadline,$task_id,$created_by_name,$status]);
                $em = "Task created successfully";
                header("Location: ../../public/pages/manage_tasks.php?success=$em");
                exit();
            }
        }
    }else{
         $em = "Login First";
         header("Location: ../../public/pages/login.php?error=$em");;
         exit();
    }
?>
