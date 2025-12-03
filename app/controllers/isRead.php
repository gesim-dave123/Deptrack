<?php
require_once '../../app/Middlewares/auth_check.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        include '../../config/db_connection.php';
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['success' => false, 'error' => 'Invalid JSON: ' . json_last_error_msg()]);
            exit(); // Remove the console.log line
        }
        
        $action = $input['action'] ?? '';

        if ($action === 'markAsRead') {
            $notificationId = $input['notification_id'] ?? null;
            
            // Validate notification ID
            if (!$notificationId) {
                echo json_encode(['success' => false, 'error' => 'Missing notification_id']);
                exit();
            }
            
            // Call your function
            $result = set_Noti_isread($conn, $notificationId);
            
            echo json_encode(['success' => $result]);
            exit();
        }
        
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        // Remove console.log line here too
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

function set_Noti_isread($conn, $notification_id) {
    $sql = "UPDATE notification SET is_read = 1 WHERE notification_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$notification_id]);
    
    if ($stmt->rowCount() == 0) {
        return false;
    }
    return true; // FIX: Add return true
}
?>