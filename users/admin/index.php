<?php include("../../shared/check_session.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded shadow text-center">
    <h1 class="text-3xl font-bold text-blue-800 mb-4">
      Welcome, <?= $_SESSION['name'] ?> (Admin)
    </h1>
    <div class="space-y-4">
      <a href="upload_students.php" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
        📥 Upload Student CSV
      </a>
      <a href="../../logout.php" class="text-red-600 underline block">🔒 Logout</a>
    </div>
  </div>
</body>
</html>
