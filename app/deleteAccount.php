<?php
// Start session to access $_SESSION variables
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is authenticated
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
 
    if (isset($_POST['delete_id'])) {
        
        include '../../config/db_connection.php';
        
        $id = $_POST['delete_id'];
        
        // FIXED: Removed * from DELETE statement
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);

        // FIXED: Changed parameters to match the query (only $id)
        if ($stmt->execute([$id])) {
            $em = "Employee deleted successfully!";
            header("Location: ../../public/pages/manage_employees.php?success=$em");
            exit();   
        } else {
            $em = "Failed to delete employee";
             header("Location: ../../public/pages/manage_employees.php?success=$em");;
            exit();   
        }    
        
    } else {
        $em = "Missing employee ID";
         header("Location: ../../public/pages/manage_employees.php?success=$em");
        exit();
    }
} else {
    $em = "Login first";
    header("Location: ../../public/pages/login.php?error=$em"); 
    exit();
}
?>