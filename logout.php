<?php
session_start();              // Start the session
session_unset();              // Remove all session variables
session_destroy();            // Destroy the session completely

// ✅ Redirect to auth.php with session expired alert
header("Location: auth.php?expired=1");
exit();
?>
