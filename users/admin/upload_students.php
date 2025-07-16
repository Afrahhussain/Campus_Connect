<?php include("../../shared/check_session.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Students</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow text-center">
    <h1 class="text-2xl font-bold text-green-800 mb-4">Upload Student Data (CSV)</h1>

    <form method="POST" enctype="multipart/form-data" class="space-y-4">
      <input type="file" name="csv_file" accept=".csv" required class="w-full border p-2 rounded">
      <button type="submit" name="upload" class="bg-green-700 text-white px-6 py-2 rounded hover:bg-green-800">
        Upload
      </button>
    </form>

    <p class="mt-6"><a href="index.php" class="text-blue-600 underline">← Back to Dashboard</a></p>
  </div>
</body>
</html>
