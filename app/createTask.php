<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])){

    if (isset($_POST['taskTitle']) && isset($_POST['description']) && isset($_POST['deadline']) && isset($_POST['priority']) && isset($_POST['assignTo'])) {
        include "../db_connection.php";

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
            header("Location: ../manage_tasks.php?error=$em");
            exit();
            
        }else{   
            $sql_taskTitle = "SELECT task_id FROM tasks WHERE title = ?";
            $stmt_taskTitle = $conn->prepare($sql_taskTitle);
            $stmt_taskTitle->execute([$title]); 
            $result_taskTitle = $stmt_taskTitle->fetchAll();

            if(count($result_taskTitle ) > 0){
                $em = "Task already exists";
                header("Location: ../manage_tasks.php?error=$em");
                exit();
            }else{
                $assigned_by = $_SESSION['id'];
                $department_id = $_SESSION['department_id'];
                $sql = "INSERT INTO tasks (title, description, due_date, priority, assigned_to, assigned_by, department_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$title, $description, $deadline, $priority, $assignTo, $assigned_by, $department_id]);
                //for notification
                $sql_notification = "INSERT INTO notification (message, recepient_id, type) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql_notification);
                $stmt->execute([$description,$assignTo,$priority]);

                header("Location: ../manage_tasks.php?success=Task Created Successfully");
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
