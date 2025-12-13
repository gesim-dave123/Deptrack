<?php
function get_all_employees($conn, $department_id) {
    $sql = "SELECT id, username, full_name, email FROM users WHERE role_id = 3 AND department_id = ? AND is_active = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$department_id]);

    if ($stmt->rowCount() == 0) {
        return [];
    }
    return $stmt->fetchAll();
}
function get_all_tasks($conn, $department_id) {
    $sql = "SELECT 
            t.task_id, 
            t.title, 
            t.description, 
            t.due_date, 
            t.status, 
            t.priority, 
            t.assigned_to,
            u.full_name AS assigned_employee
        FROM tasks t
        LEFT JOIN users u ON t.assigned_to = u.id
        WHERE t.department_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$department_id]);
    if ($stmt->rowCount() == 0) {
        return [];
    }
    return $stmt->fetchAll();
}

function get_all_my_tasks($conn, $department_id, $user_id) {
    $sql = "SELECT task_id, title,status, description,due_date,priority, assigned_by 
            FROM tasks 
            WHERE department_id = ? AND assigned_to = ? AND status != 'Completed'";        
    $stmt = $conn->prepare($sql);
    $stmt->execute([$department_id, $user_id]);
    if ($stmt->rowCount() == 0) {
        return [];
    }
    return $stmt->fetchAll();
}

function get_completed_tasks($conn, $department_id,$user_id) {
    $sql = "SELECT task_id, title,status, due_date, priority
            FROM tasks 
            WHERE department_id = ? AND status = 'Completed' AND assigned_to = ?";        
    $stmt = $conn->prepare($sql);
    $stmt->execute([$department_id,$user_id]);
    if ($stmt->rowCount() == 0) {
        return [];
    }
    return $stmt->fetchAll();
}

function get_notifications($conn, $user_id) {
        $sql = "SELECT 
        n.notification_id, 
        n.message, 
        n.date, 
        n.is_read,
        created_At, 
        t.title AS task_title, 
        t.due_date AS task_due_date, 
        t.priority AS task_priority
        FROM notification n
        LEFT JOIN tasks t ON n.task_id = t.task_id
        WHERE n.recepient_id = ?
        ORDER BY created_At DESC;";
        $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    if ($stmt->rowCount() == 0) {
        return [];
    }
    return $stmt->fetchAll() ;
}


function get_all_accounts($conn) {
    $sql = "SELECT 
        u.full_name,
        u.id,
        u.username,
        u.hire_date,
        u.email,
        r.role_name,
        d.department_name
    FROM users u
    LEFT JOIN roles r ON u.role_id = r.role_id
    LEFT JOIN departments d ON u.department_id = d.department_id
    WHERE u.is_active = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        return [];
    }
    return $stmt->fetchAll();
}

function get_chart_data($conn, $user_id) {
    $sql = "SELECT 
                status,
                COUNT(*) AS total
            FROM tasks
            WHERE assigned_to = ?
            GROUP BY status";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    if ($stmt->rowCount() == 0) {
        return [];
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function set_Noti_isread($conn, $notification_id) {
    
        $sql = "UPDATE notification SET isread = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$notification_id]);
        
        if ($stmt->rowCount() == 0) {
            return false;
         }
        return;
}
function get_all_departments($conn) {
    
        $sql = "SELECT department_name from departments ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            return [];
         }
        return $stmt->fetchAll();
}

function get_all_department($conn) {
    $sql = "SELECT 
                d.department_id,
                d.department_name,
                d.department_description,
                d.department_icon,
                admin.full_name AS admin_name,
                COUNT(u.id) AS user_count
            FROM departments d
            LEFT JOIN users AS admin 
                ON admin.department_id = d.department_id 
                AND admin.role_id = 2  -- admin name
            LEFT JOIN users AS u ON d.department_id = u.department_id  -- users under department
            GROUP BY d.department_id;";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        return [];
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function update_department($conn, $dept_id, $name, $description) {
    // Sanitize input
    $safe_name = htmlspecialchars(trim($name));
    $safe_description = htmlspecialchars(trim($description));
    $safe_id = filter_var($dept_id, FILTER_SANITIZE_NUMBER_INT);

    $sql = "UPDATE departments SET 
                department_name = ?, 
                department_description = ? 
            WHERE department_id = ?";

    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        return "Failed to prepare statement: " . $conn->error;
    }
    

    $execute_result = $stmt->execute([$safe_name,$safe_description,$safe_id]);

    if ($execute_result) {
            return true; // Success
       
    } else {
      return false;
    }
}

function get_all_notifications($conn){
    $sql = "SELECT * FROM notification ORDER BY created_At DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        return [];
    }
    return $stmt->fetchAll();
}

function get_all_tasks_per_department($conn, $department_id) {
    // Use a positional placeholder (?) for the department_id
    $sql = "
        SELECT 
            status, 
            COUNT(task_id) AS total
        FROM tasks
        WHERE department_id = ?
        GROUP BY status
    ";

    $stmt = $conn->prepare($sql);
    
    // CORRECTED LINE 1: Pass the parameter array to the execute() method for positional binding.
    // The execute method returns TRUE on success or FALSE on failure.
    $stmt->execute([$department_id]);
    
    $tasks_by_status = [];

    // CORRECTED LINE 2: Use PDO's fetch method to retrieve results.
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tasks_by_status[$row['status']] = $row['total'];
    }

    // PDO statements are generally closed automatically when the script finishes or 
    // when the variable is reassigned, but you can explicitly unset or close a cursor if needed.
    // $stmt = null; 
    
    return $tasks_by_status;
}
function get_employee_status_counts($conn, $department_id) {
    // SQL query to count employees by their 'is_active' status (0 or 1)
    $sql = "
        SELECT 
            is_active, 
            COUNT(id) AS total
        FROM users
        WHERE department_id = :dept_id AND role_id = 3
        GROUP BY is_active
    ";

    $stmt = $conn->prepare($sql);
    
    $stmt->execute([':dept_id' => $department_id]);

    $status_counts = [
        'Active' => 0,
        'Inactive' => 0
    ];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['is_active'] == 1) {
            $status_counts['Active'] = $row['total'];
        } else {
            $status_counts['Inactive'] = $row['total'];
        }
    }
    
    return $status_counts;
}

function get_total_tasks_per_user_by_department($conn, $department_id) {
    
    $sql = "
        SELECT 
            u.id, 
            u.username,  
            COUNT(t.task_id) AS total_tasks
        FROM 
            users u
        LEFT JOIN 
            tasks t ON u.id = t.assigned_to
        WHERE 
            u.department_id = ?  && u.role_id != 2
        GROUP BY 
            u.id, u.username
        ORDER BY 
            total_tasks DESC;
    ";
    
    // Use a prepared statement to safely include the department ID
    $stmt = $conn->prepare($sql);
    
    // Execute the statement, passing the department ID as a parameter
    $stmt->execute([$department_id]);
    
    // Fetch all results into an associative array
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $results;
}

function get_tasks_by_status_count($conn) {
    $sql = "
        SELECT  
            status,
            COUNT(task_id) AS task_count 
        FROM 
            tasks
        GROUP BY
            status
    ";
    $stmt = $conn->prepare($sql);
    
    // Execute the statement
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return the array containing all status counts
    return $results;
}

function get_total_departments($conn){
    $sql = "SELECT COUNT(department_id) AS total_departments FROM departments";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        return 0;
    }
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_departments'];
}

function get_total_employees($conn){
    $sql = "SELECT COUNT(id) AS total_employees FROM users WHERE role_id != 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        return 0;
    }
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_employees'];
}


function get_total_tasks_by_department($conn){
    $sql = "SELECT
                d.department_name,
                COUNT(t.task_id) AS total_tasks
            FROM
                departments d
            LEFT JOIN
                tasks t ON t.department_id = d.department_id
            GROUP BY
                d.department_id, d.department_name
            ORDER BY
                d.department_name;
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $results;
}

function get_task_status_counts($conn){
    // 1. Corrected SQL: Selects status and the count
    $sql = "SELECT 
                status, 
                COUNT(task_id) AS count 
            FROM 
                tasks 
            GROUP BY 
                status";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        // 2. Fetch ALL results as an indexed array of rows
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 3. Format the results into a key/value array for easy JS consumption
        $statusCounts = [];
        foreach ($results as $row) {
            $statusCounts[$row['status']] = (int)$row['count'];
        }
        
        return $statusCounts;
        
    } catch (PDOException $e) {
        // Handle database error gracefully
        error_log("Database Error in get_task_status_counts: " . $e->getMessage());
        return [];
    }
}


function get_AllEmployee_status_counts($conn) {
    
    // Define the map to convert the database status code (1 or 0) to the desired string key
    $status_map = [
        1 => 'Active',
        0 => 'Inactive'
    ];
    
    $sql = "
        SELECT 
            is_active, 
            COUNT(id) AS total_employees 
        FROM users 
        WHERE 
            role_id != 1 
        GROUP BY is_active
    ";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $status_counts = [];
        foreach ($results as $row) {
            $is_active_code = (int)$row['is_active'];
            $total_count = (int)$row['total_employees'];
            
            // Check if the code exists in our map before assigning
            if (isset($status_map[$is_active_code])) {
                $string_key = $status_map[$is_active_code];
                $status_counts[$string_key] = $total_count;
            }
        }
        
        // Optional: Merge with default values (Active=0, Inactive=0) to ensure both keys are ALWAYS present
        $default_counts = [
            'Active' => 0,
            'Inactive' => 0
        ];
        
        // Return the final keyed array, ensuring Active/Inactive keys exist
        return array_merge($default_counts, $status_counts);
        
    } catch (PDOException $e) {
        error_log("Database Error in get_AllEmployee_status_counts: " . $e->getMessage());
        // Return the safe default structure on error
        return ['Active' => 0, 'Inactive' => 0]; 
    }
}
?>
