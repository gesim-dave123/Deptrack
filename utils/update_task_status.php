<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
   
    if (isset($_POST['task_id']) && isset($_POST['status'])) {
       include '../config/db_connection.php';
        
        $taskId = $_POST['task_id'];
        $newStatus = $_POST['status'];

        if(!$taskId || !$newStatus){
             $em = "Field Required";
            header("Location: ../my_task.php?error=$em");
            exit();
        }
        
        // Validate status value
        $validStatuses = ['In Progress', 'Completed', 'Pending'];
        if (!in_array($newStatus, $validStatuses)) {
            $_SESSION['error'] = "Invalid status value";
            header("Location: ../my_task.php");
            exit();
        }
        
        // Update query
        $sql = "UPDATE tasks SET status = ? WHERE task_id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([$newStatus, $taskId])) {
            echo "Task status updated successfully!";
        } else {
           echo "Failed to update task status";
        }
        
        // Redirect back to tasks page
        header("Location: ../my_task.php");
        exit();
        
    } else {
        $_SESSION['error'] = "Missing task data";
        header("Location: ../my_task.php");
        exit();
    }
    
} else {
    // User not logged in
    header("Location: ../login.php");
    exit();
}
?>