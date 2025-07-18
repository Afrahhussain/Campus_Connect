<?php
session_start();
require_once "db.php"; // DB connection

$login_error = $register_error = $register_success = "";

// Handle Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT * FROM users WHERE email=?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: users/admin/index.php");
            } elseif ($user['role'] == 'faculty') {
                header("Location: users/faculty/index.php");
            } elseif ($user['role'] == 'hod') {
                header("Location: users/hod/index.php");
            } elseif ($user['role'] == 'student') {
                header("Location: users/student/index.php");
            }
            exit();
        } else {
            $login_error = "Invalid Password!";
        }
    } else {
        $login_error = "No user found with this email!";
    }
}

// Handle Register
if (isset($_POST['register'])) {
    $name = $_POST['reg_name'];
    $email = $_POST['reg_email'];
    $password = password_hash($_POST['reg_password'], PASSWORD_BCRYPT);
    $role = $_POST['reg_role'];

    // Check if email exists
    $check = $conn->prepare("SELECT * FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $register_error = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);
        if ($stmt->execute()) {
            $register_success = "Registration successful! You can now login.";
        } else {
            $register_error = "Something went wrong!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>College Login/Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function toggleForms(showRegister) {
            document.getElementById('login-form').style.display = showRegister ? 'none' : 'block';
            document.getElementById('register-form').style.display = showRegister ? 'block' : 'none';
        }
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <!-- Login Form -->
    <div id="login-form" style="display: block;">
        <h2 class="text-2xl font-bold text-green-700 mb-4">Login</h2>
        <?php if ($login_error) echo "<p class='text-red-500 mb-2'>$login_error</p>"; ?>
        <form method="POST" class="space-y-3">
            <input type="email" name="email" placeholder="Email" required class="w-full border p-2 rounded">
            <input type="password" name="password" placeholder="Password" required class="w-full border p-2 rounded">
            <button type="submit" name="login" class="bg-green-700 text-white w-full py-2 rounded hover:bg-green-800">Login</button>
        </form>
        <p class="text-sm mt-3 text-gray-600">Not registered? 
            <a href="#" onclick="toggleForms(true)" class="text-blue-600 hover:underline">Register here</a>
        </p>
    </div>

    <!-- Register Form -->
    <div id="register-form" style="display: none;">
        <h2 class="text-2xl font-bold text-blue-700 mb-4">Register</h2>
        <?php if ($register_success) echo "<p class='text-green-600 mb-2'>$register_success</p>"; ?>
        <?php if ($register_error) echo "<p class='text-red-600 mb-2'>$register_error</p>"; ?>
        <form method="POST" class="space-y-3">
            <input type="text" name="reg_name" placeholder="Full Name" required class="w-full border p-2 rounded">
            <input type="email" name="reg_email" placeholder="Email" required class="w-full border p-2 rounded">
            <input type="password" name="reg_password" placeholder="Password" required class="w-full border p-2 rounded">
            <select name="reg_role" required class="w-full border p-2 rounded">
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="faculty">Faculty</option>
                <option value="hod">HOD</option>
                <option value="student">Student</option>
            </select>
            <button type="submit" name="register" class="bg-blue-700 text-white w-full py-2 rounded hover:bg-blue-800">Register</button>
        </form>
        <p class="text-sm mt-3 text-gray-600">
            Already have an account? 
            <a href="#" onclick="toggleForms(false)" class="text-green-600 hover:underline">Login here</a>
        </p>
    </div>
</div>

</body>
</html>
