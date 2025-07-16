<?php
session_start();

// If session not active, redirect to login
if (!isset($_SESSION['role'])) {
    $_SESSION['error'] = "⚠️ Session expired. Please login again.";
    header("Location: ../../auth.php");
    exit();
}

// Prevent browser cache so back button doesn't reopen
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
?>
