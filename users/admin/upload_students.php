<?php
require_once "../../shared/check_session.php";
require_once "../../db.php";

$message = "";

if (isset($_POST['upload'])) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $file = $_FILES['file']['tmp_name'];
        $handle = fopen($file, "r");

        if ($handle !== FALSE) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row == 0) { // Skip header row
                    $row++;
                    continue;
                }
                $name = trim($data[0]);
                $email = trim($data[1]);
                $branch = trim($data[2]);
                $year = trim($data[3]);
                $section = trim($data[4]);
                $password = password_hash('1234', PASSWORD_BCRYPT); // Default password

                // Check if email exists
                $check = $conn->prepare("SELECT * FROM students WHERE email=?");
                $check->bind_param("s", $email);
                $check->execute();
                if ($check->get_result()->num_rows == 0) {
                    $stmt = $conn->prepare("INSERT INTO students (name, email, branch, year, section, password) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $name, $email, $branch, $year, $section, $password);
                    $stmt->execute();
                }
                $row++;
            }
            fclose($handle);
            $message = "Students uploaded successfully!";
        } else {
            $message = "Error opening the file.";
        }
    } else {
        $message = "Please upload a valid CSV file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Students</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<div class="bg-white shadow p-6 rounded max-w-lg mx-auto">
    <h1 class="text-2xl font-bold text-green-700 mb-4">Upload Students (CSV)</h1>
    <?php if ($message): ?>
        <p class="text-blue-600 mb-4"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="file" name="file" accept=".csv" required class="w-full border p-2 rounded">
        <button type="submit" name="upload" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Upload
        </button>
    </form>

    <p class="mt-4 text-gray-600 text-sm">
        CSV Format: <b>Name, Email, Branch, Year, Section</b>
    </p>

    <a href="/college-management/users/admin/index.php" class="text-blue-600 mt-4 inline-block hover:underline">← Back to Dashboard</a>
</div>

</body>
</html>
