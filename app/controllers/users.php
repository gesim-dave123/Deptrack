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

    // 1. Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // 2. Execute the statement, passing an associative array for parameter binding
    $stmt->execute([':dept_id' => $department_id]);
    
    // 3. Initialize counts
    $status_counts = [
        'Active' => 0,
        'Inactive' => 0
    ];

    // 4. Fetch the results
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
    
    // Prepare and execute the statement
    // Using prepare() is still good practice even without parameters
    $stmt = $conn->prepare($sql);
    
    // Execute the statement
    $stmt->execute();
    
    // Fetch *all* results into an associative array, since there are multiple rows
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return the array containing all status counts
    return $results;
}
?>