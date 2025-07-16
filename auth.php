<?php
session_start();
include("db.php");

$login_error = '';
$register_error = '';
$register_success = '';

// ------------------ LOGIN ------------------
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role;
            header("Location: users/$role/index.php");
            exit();
        } else {
            $login_error = "❌ Invalid password.";
        }
    } else {
        $login_error = "❌ Email not registered.";
    }
}

// ------------------ REGISTER ------------------
if (isset($_POST['register'])) {
    $name     = $_POST['reg_name'];
    $email    = $_POST['reg_email'];
    $password = password_hash($_POST['reg_password'], PASSWORD_BCRYPT);
    $role     = $_POST['reg_role'];

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $register_error = "❌ Email already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);
        if ($stmt->execute()) {
            $register_success = "✅ Registered successfully. Please login.";
        } else {
            $register_error = "❌ Registration failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>College Auth</title>
  <script>
    function showRegister() {
      document.getElementById('loginForm').classList.add('hidden');
      document.getElementById('registerForm').classList.remove('hidden');
    }

    function showLogin() {
      document.getElementById('registerForm').classList.add('hidden');
      document.getElementById('loginForm').classList.remove('hidden');
    }
  </script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-md">

    <!-- Login Form -->
    <div id="loginForm">
      <h2 class="text-2xl font-bold text-green-700 mb-4">Login</h2>
      <?php if ($login_error) echo "<p class='text-red-500 mb-2'>$login_error</p>"; ?>
      <?php if (isset($_SESSION['error'])) {
        echo "<p class='text-red-500 mb-2'>⚠️ " . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
      } ?>
      <form method="POST" class="space-y-3">
        <input type="email" name="email" placeholder="Email" required class="w-full border p-2 rounded">
        <input type="password" name="password" placeholder="Password" required class="w-full border p-2 rounded">
        <button type="submit" name="login" class="bg-green-700 text-white w-full py-2 rounded hover:bg-green-800">
          Login
        </button>
      </form>
      <p class="text-sm mt-3 text-gray-600">
        Not registered? <button onclick="showRegister()" class="text-blue-600 underline">Register here</button>
      </p>
    </div>

    <!-- Register Form -->
    <div id="registerForm" class="hidden">
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
        <button type="submit" name="register" class="bg-blue-700 text-white w-full py-2 rounded hover:bg-blue-800">
          Register
        </button>
      </form>
      <p class="text-sm mt-3 text-gray-600">
        Already registered? <button onclick="showLogin()" class="text-green-600 underline">Login here</button>
      </p>
    </div>

  </div>
</body>
</html>
