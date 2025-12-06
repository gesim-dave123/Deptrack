<?php
// Ensure this file is used only via POST request
require_once '../app/Middlewares/auth_check.php';

// Set the response type to JSON
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
include '../config/db_connection.php'; // This makes the $conn PDO object available


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method. Only POST is allowed.']);
    exit();
}

try {
    // Decode the JSON input stream
    $input = json_decode(file_get_contents('php://input'), true);

    // Check for JSON errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON payload: ' . json_last_error_msg()]);
        exit();
    }
    
    $action = $input['action'] ?? '';

    if ($action === 'createDepartment') {
        
        // --- 1. Extract Data ---
        $deptData = $input['department'] ?? [];
        $adminData = $input['admin'] ?? [];
        
        $departmentName = $deptData['name'] ?? null;
        $departmentDescription = $deptData['description'] ?? null;
        $departmentIcon = $deptData['icon'] ?? null;
        $createdBy = $_SESSION['user_id'] ?? 1; 

        // --- 2. Basic Validation ---
        if (!$departmentName || !$departmentDescription || !$departmentIcon) {
            echo json_encode(['success' => false, 'error' => 'Missing department name, description, or icon.']);
            exit();
        }
        
        if (empty($adminData)) {
            echo json_encode(['success' => false, 'error' => 'Missing administrator data.']);
            exit();
        }
            $sql_username = "SELECT id FROM users WHERE username = ?";
            $stmt_username = $conn->prepare($sql_username);
            $stmt_username->execute([$adminData['username']]);
            $result_username = $stmt_username->fetchAll();

            // Check email
            $sql_email = "SELECT id FROM users WHERE email = ?";
            $stmt_email = $conn->prepare($sql_email);
            $stmt_email->execute([$adminData['email']]);
            $result_email = $stmt_email->fetchAll();

             if( count($result_username ) > 0){
                
                  echo json_encode(['success' => false, 'error' => 'Username Taken']);
                  exit();
            }else if( count ($result_email) > 0){
               
                  echo json_encode(['success' => false, 'error' => 'Email Taken']);
                  exit();
            }

        // --- 3. Start Database Transaction (ACID) ---
        $conn->beginTransaction(); // <--- FIX 1: ADDED MISSING LINE

        // A. Create Admin/User Record (without department_id initially)
        $admin_id = insertAdmin($conn, $adminData);
        if (!$admin_id) {
            throw new Exception('Failed to create department admin user.');
        }

        // B. Create Department Record (without linking back to the admin)
        $department_id = insertDepartment($conn, $deptData, $createdBy);
        if (!$department_id) {
            throw new Exception('Failed to insert department record.');
        }

        // C. CRITICAL STEP: Link the Admin user back to the new Department ID
        $link_success = updateAdminDepartmentId($conn, $admin_id, $department_id);
        if (!$link_success) {
            throw new Exception('Failed to link admin user to the new department.');
        }

        // --- 4. Finalize ---
        $conn->commit();

        echo json_encode([
            'success' => true,
            'department_id' => $department_id // Return the new ID to the frontend
        ]);
        exit();
        
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid action specified.']);
        exit();
    }
    
} catch (Exception $e) {
    // Catch any database or application errors
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack(); // Ensure any open transaction is rolled back
    }
    echo json_encode(['success' => false, 'error' => 'Server Error: ' . $e->getMessage()]);
}

// ==========================================================
// CORRECTED PLACEHOLDER FUNCTIONS
// ==========================================================

/**
 * Inserts a new user record into the database and returns the new user ID.
 */
function insertAdmin($conn, $adminData) {
    // FIX 2: Corrected column names to match the expected data structure (name/email/password)
    $sql = "INSERT INTO users (full_name, username, email, hashed_password, role_id) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        $adminData['fullName'], 
        $adminData['username'],
        $adminData['email'],
        password_hash($adminData['password'], PASSWORD_DEFAULT), // HASH THE PASSWORD!
        $adminData['role']
    ]);

    return $result ? $conn->lastInsertId() : false;
}

/**
 * Inserts a new department record.
 */
function insertDepartment($conn, $deptData, $createdBy) {
    $sql = "INSERT INTO departments (department_name, department_description, department_icon, created_by) 
            VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        $deptData['name'],
        $deptData['description'],
        $deptData['icon'],
        $createdBy // FIX 3: Use the $createdBy variable, not a hardcoded value (3)
    ]);
    
    return $result ? $conn->lastInsertId() : false;
}

/**
 * REQUIRED LINKING FUNCTION: Updates the admin user's department_id column.
 */
function updateAdminDepartmentId($conn, $userId, $departmentId) {
    $sql = "UPDATE users SET department_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$departmentId, $userId]);
}
?>