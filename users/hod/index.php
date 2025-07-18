<?php
require_once "../../shared/check_session.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HOD Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<div class="bg-white shadow p-4 rounded">
    <h1 class="text-3xl font-bold text-purple-700">Welcome, <?php echo $_SESSION['name']; ?> (HOD)</h1>
    <p class="mt-2 text-gray-600">View faculty performance, reports, and manage department.</p>

    <a href="/college-management/logout.php" 
       class="inline-block mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
       Logout
    </a>
</div>

</body>
</html>
