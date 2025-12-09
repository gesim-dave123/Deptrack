<?php
// Start session to access $_SESSION variables (essential for 'role' and 'id')
// NOTE: Assuming session_start() is called somewhere before this script, 
// but it's good practice to ensure it's at the top if not.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is authenticated
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
 
    if (isset($_POST['task_id']) && isset($_POST['status'])) {
        

        include '../../config/db_connection.php';
        
        $taskId = $_POST['task_id'];
        $newStatus = $_POST['status'];

       
        if (empty($taskId) || empty($newStatus)) {
            $em = "Field Required";
     
            header("Location: ../my_task.php?error=$em"); 
            exit();
        }
        
        $validStatuses = ['In Progress', 'Completed', 'Pending'];
  
        if (!in_array($newStatus, $validStatuses)) {
            $em = "Invalid status value";
             header("Location: ../../public/pages/my_task.php?error=$_em"); 
            exit();
        }
        
     
        $sql = "UPDATE tasks SET status = ? WHERE task_id = ?";
        $stmt = $conn->prepare($sql);


       

        if ($stmt->execute([$newStatus, $taskId])) {
            echo "Task status updated successfully!";

             $message = "Task status changed to " . $newStatus;
             $type= "Task Updated";
             $status="Success";
             $created_by_name = $_SESSION['fullname'];
             $recepient_id = $_SESSION['created_by'];
             $sql_notification = "INSERT INTO notification (message, recepient_id, task_id,type,created_by,status) VALUES (?, ?,?, ?, ?, ?)";
             $stmt_notification = $conn->prepare($sql_notification);
             $stmt_notification->execute([$message, $recepient_id,$taskId, $type, $created_by_name, $status]);

           
               
        } else {
           $em = " Failed to update task";
           header("Location: ../../public/pages/my_task.php?error=$em");
        exit();   
        }    
        $em = " Task " . $newStatus;
        header("Location: ../../public/pages/my_task.php?info=$em");
        exit();
        
    } else {
        $_SESSION['error'] = "Missing task data";
        header("Location: ../my_task.php"); 
        exit();
    }
} else {
    $em = " Login first";
    header("Location: ../../public/pages/login.php?error=$em"); 
    exit();
}
?>