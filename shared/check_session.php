<?php
session_start();

// Session timeout in seconds (10 minutes)
$timeout_duration = 600;

if (!isset($_SESSION['user_id'])) {
    header("Location: /college-management/index.php");
    exit();
}

// Check session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: /college-management/index.php?timeout=1");
    exit();
}

$_SESSION['last_activity'] = time(); // Update last activity
?>
