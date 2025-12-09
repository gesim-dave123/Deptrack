<?php
if (!isset($cleanData) || !isset($totalTasks)) {
    // Fallback or error handling if data isn't passed
    return;
}

function calculate_percent($count, $total) {
    return $total > 0 ? round(($count / $total) * 100) : 0;
}

$completedPercent   = calculate_percent($cleanData["Completed"], $totalTasks);
$inProgressPercent  = calculate_percent($cleanData["InProgress"], $totalTasks);
$pendingPercent     = calculate_percent($cleanData["Pending"], $totalTasks);
$missingPercent     = calculate_percent($cleanData["Missing"], $totalTasks);

?>

<div class="stats-container">
    <div class="stat-item completed">
        <div class="stat-color"></div>
        <div class="stat-info">
            <h3>Completed</h3>
            <p><?php echo $cleanData["Completed"]; ?></p>
        </div>
        <div class="stat-percentage"><?php echo $completedPercent; ?>%</div>
    </div>

    <div class="stat-item in-progress">
        <div class="stat-color"></div>
        <div class="stat-info">
            <h3>In Progress</h3>
            <p><?php echo $cleanData["InProgress"]; ?></p>
        </div>
        <div class="stat-percentage"><?php echo $inProgressPercent; ?>%</div>
    </div>

    <div class="stat-item pending">
        <div class="stat-color"></div>
        <div class="stat-info">
            <h3>Pending</h3>
            <p><?php echo $cleanData["Pending"]; ?></p>
        </div>
        <div class="stat-percentage"><?php echo $pendingPercent; ?>%</div>
    </div>

    <div class="stat-item missing">
        <div class="stat-color"></div>
        <div class="stat-info">
            <h3>Missing</h3>
            <p><?php echo $cleanData["Missing"]; ?></p>
        </div>
        <div class="stat-percentage"><?php echo $missingPercent; ?>%</div>
    </div>
</div>