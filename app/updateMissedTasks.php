<?php
// task_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function checkAndHandleMissedDeadlines($conn) {
    // We assume the connection ($conn) is passed from the calling script (login handler)
    
    $newStatus = 'Missing';
    $createdByName = "System";

    // 1. SELECT overdue tasks that aren't already completed or missed
    $sql_select = "
        SELECT 
            task_id
        FROM 
            tasks 
        WHERE 
            status NOT IN ('Completed', 'Missing') 
            AND due_date < CURRENT_TIMESTAMP
    ";

    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->execute();
    $overdueTasks = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

    if (empty($overdueTasks)) {
        return; // No tasks to update
    }

    $taskIdsToUpdate = [];
    $notificationInserts = [];

    foreach ($overdueTasks as $task) {
        $taskId = $task['task_id'];
        $taskIdsToUpdate[] = $taskId;

    }

    // 2. Batch UPDATE the tasks status
    $placeholders = implode(',', array_fill(0, count($taskIdsToUpdate), '?'));
    $sql_update = "
        UPDATE 
            tasks 
        SET 
            status = ? 
        WHERE 
            task_id IN ($placeholders)
    ";
    $stmt_update = $conn->prepare($sql_update);
    $executeParams = array_merge([$newStatus], $taskIdsToUpdate);
    $stmt_update->execute($executeParams);
    return;
}
?>