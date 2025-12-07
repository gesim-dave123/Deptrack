<?php
// Path: ../../app/updateDepartment.php
session_start();
header('Content-Type: application/json');

// 1. Check Authorization
if (!isset($_SESSION['role'])) { // Assuming role 1 is Admin
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Authorization required']);
    exit();
}

// Include database connection and controller functions
include '../config/db_connection.php'; 

include 'controllers/users.php'; 

// 2. Read JSON Input
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// 3. Basic Input Validation
if (empty($data['department_id']) || empty($data['department_name']) || empty($data['department_description'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required department fields.']);
    exit();
}

$dept_id = $data['department_id'];
$dept_name = $data['department_name'];
$dept_description = $data['department_description'];

// 4. Sanitize and Update Database
// NOTE: Assuming your database controller has a function like update_department()
try {
    // You should define this function in controllers/departments.php
    $result = update_department($conn, $dept_id, $dept_name, $dept_description);

    if ($result === true) {
        echo json_encode(['success' => true, 'message' => 'Department updated successfully.']);
    } else {
        // Log the error for debugging purposes
        error_log("DB Update Failed for Dept ID $dept_id: " . ($result !== false ? $result : 'Unknown Error'));
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Database update failed.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}

?>