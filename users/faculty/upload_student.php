<?php
session_start();
include('../../db.php');
include('../../session_check.php');

// Allow only faculty role
if ($_SESSION['role'] !== 'faculty') {
    header("Location: ../../auth.php");
    exit();
}

$success = $error = '';

if (isset($_POST['submit'])) {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $roll     = $_POST['roll_no'];
    $year     = $_POST['year'];
    $branch   = $_POST['branch'];
    $section  = $_POST['section'];
    $password = password_hash("1234", PASSWORD_BCRYPT);
    $photo    = '';

    // Upload photo if selected
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../../uploads/photos/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . time() . "_" . $file_name;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo = $target_file;
        }
    }

    // Step 1: Insert into users table
    $stmt1 = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
    $stmt1->bind_param("sss", $name, $email, $password);

    if ($stmt1->execute()) {
        $user_id = $stmt1->insert_id;

        // Step 2: Insert into students_extra table
        $stmt2 = $conn->prepare("INSERT INTO students_extra (user_id, roll_no, year, branch, section, photo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("isssss", $user_id, $roll, $year, $branch, $section, $photo);

        if ($stmt2->execute()) {
            $success = "✅ Student added successfully!";
        } else {
            $error = "❌ Error in students_extra: " . $stmt2->error;
        }
    } else {
        $error = "❌ Email already exists or insert failed: " . $stmt1->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Upload Student</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">
  <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4 text-blue-700">Upload New Student</h2>

    <?php if ($success) echo "<p class='text-green-600 mb-3'>$success</p>"; ?>
    <?php if ($error) echo "<p class='text-red-600 mb-3'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-4">
      <input type="text" name="name" placeholder="Full Name" required class="w-full border p-2 rounded">
      <input type="email" name="email" placeholder="Email" required class="w-full border p-2 rounded">
      <input type="text" name="roll_no" placeholder="Roll No" required class="w-full border p-2 rounded">

      <div class="flex space-x-2">
        <input type="text" name="year" placeholder="Year (e.g., 2nd)" required class="w-1/3 border p-2 rounded">
        <input type="text" name="branch" placeholder="Branch (e.g., CSE)" required class="w-1/3 border p-2 rounded">
        <input type="text" name="section" placeholder="Section (e.g., A)" required class="w-1/3 border p-2 rounded">
      </div>

      <label class="text-sm">Profile Photo (optional):</label>
      <input type="file" name="photo" class="w-full border p-2 rounded">

      <button type="submit" name="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
        Upload Student
      </button>
    </form>
  </div>
</body>
</html>
