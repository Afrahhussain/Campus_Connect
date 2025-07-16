<?php include("../../shared/check_session.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Faculty Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded shadow text-center">
    <h1 class="text-3xl font-bold text-purple-800 mb-4">
      Welcome, <?= $_SESSION['name'] ?> (Faculty)
    </h1>
    <p class="text-gray-700 mb-4">Use the options below to manage attendance or marks.</p>
    <div class="space-y-4">
      <a href="upload_marks.php" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700">✍️ Upload Marks</a>
      <a href="../../logout.php" class="text-red-600 underline block">🔒 Logout</a>
    </div>
  </div>
</body>
</html>
