<?php
function get_all_employees($conn, $department_id) {
    $sql = "SELECT id, username, full_name, email FROM users WHERE role_id = 3 AND department_id = ?";
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
            WHERE department_id = ? AND assigned_to = ? AND (status = 'Pending' OR status = 'In Progress')";        
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
    LEFT JOIN departments d ON u.department_id = d.department_id";
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


?>