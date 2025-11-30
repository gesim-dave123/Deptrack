<?php
// Start session to access $_SESSION variables (essential for 'role' and 'id')
// NOTE: Assuming session_start() is called somewhere before this script, 
// but it's good practice to ensure it's at the top if not.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is authenticated
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    
    // Check if required POST data (task ID and new status) is present
    if (isset($_POST['task_id']) && isset($_POST['status'])) {
        
        // Configuration and setup
        // Assuming the database connection file handles connection initialization
        include '../../config/db_connection.php';
        
        $taskId = $_POST['task_id'];
        $newStatus = $_POST['status'];

        // --- 1. Basic Input Validation ---
        
        // Check for empty values after POST submission
        if (empty($taskId) || empty($newStatus)) {
            $em = "Field Required";
            // NOTE: The original output redirects with an 'error' query parameter
            header("Location: ../my_task.php?error=$em"); 
            exit();
        }
        
        // Define accepted statuses for strong validation
        $validStatuses = ['In Progress', 'Completed', 'Pending'];
        
        // Validate status value against the allowed list
        if (!in_array($newStatus, $validStatuses)) {
            $em = "Invalid status value"; // Set session error message
             header("Location: ../../public/pages/my_task.php?error=$_em"); 
            exit();
        }
        
        // --- 2. Database Update ---

        // Prepare the SQL statement to prevent SQL injection (using parameterized query)
        $sql = "UPDATE tasks SET status = ? WHERE task_id = ?";
        $stmt = $conn->prepare($sql);

        // Execute the update
        if ($stmt->execute([$newStatus, $taskId])) {
            echo "Task status updated successfully!"; // Original output: This echo will be lost on redirect.
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