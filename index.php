<?php
$base = rtrim(dirname($_SERVER['PHP_SELF']), '/');
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?= $base ?>/public/styles/homepage.css?v=4.0">
</head>
<body>
    <main>
        <?php include __DIR__ . '/public/pages/homepage.php'; ?>
    </main>
</body>
</html>
