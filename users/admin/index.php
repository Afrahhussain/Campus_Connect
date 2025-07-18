<?php
session_start();
include("../../shared/check_session.php");
include("../../db.php");

// Handle Approve/Reject
if (isset($_POST['action']) && isset($_POST['user_id'])) {
    $status = ($_POST['action'] === 'approve') ? 'approved' : 'rejected';
    $user_id = intval($_POST['user_id']);
    $stmt = $conn->prepare("UPDATE users SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $user_id);
    $stmt->execute();
}

// Filters
$where = "WHERE 1=1";
if (!empty($_GET['role'])) {
    $where .= " AND role='" . $conn->real_escape_string($_GET['role']) . "'";
}
if (!empty($_GET['department'])) {
    $where .= " AND department='" . $conn->real_escape_string($_GET['department']) . "'";
}
if (!empty($_GET['year'])) {
    $where .= " AND year=" . intval($_GET['year']);
}
if (!empty($_GET['section'])) {
    $where .= " AND section='" . $conn->real_escape_string($_GET['section']) . "'";
}
if (!empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $where .= " AND (name LIKE '%$search%' OR email LIKE '%$search%')";
}

// Fetch users
$result = $conn->query("SELECT * FROM users $where ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6">
<h1 class="text-2xl font-bold mb-4">Admin Dashboard - Manage Users</h1>

<!-- Filters -->
<form method="GET" class="mb-4 flex flex-wrap gap-2">
    <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="border p-2 rounded">
    <select name="role" class="border p-2 rounded">
        <option value="">All Roles</option>
        <option value="admin">Admin</option>
        <option value="faculty">Faculty</option>
        <option value="hod">HOD</option>
        <option value="student">Student</option>
    </select>
    <input type="text" name="department" placeholder="Department" class="border p-2 rounded">
    <input type="number" name="year" placeholder="Year" class="border p-2 rounded">
    <input type="text" name="section" placeholder="Section" class="border p-2 rounded">
    <button class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
</form>

<!-- Users Table -->
<table class="border-collapse w-full border">
    <thead>
        <tr class="bg-gray-200">
            <th class="border p-2">ID</th>
            <th class="border p-2">Name</th>
            <th class="border p-2">Email</th>
            <th class="border p-2">Role</th>
            <th class="border p-2">Status</th>
            <th class="border p-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td class="border p-2"><?= $row['id'] ?></td>
                <td class="border p-2"><?= $row['name'] ?></td>
                <td class="border p-2"><?= $row['email'] ?></td>
                <td class="border p-2"><?= $row['role'] ?></td>
                <td class="border p-2"><?= $row['status'] ?></td>
                <td class="border p-2">
                    <?php if ($row['status'] === 'pending') : ?>
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="action" value="approve" class="bg-green-500 text-white px-2 py-1 rounded">Approve</button>
                        </form>
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="action" value="reject" class="bg-red-500 text-white px-2 py-1 rounded">Reject</button>
                        </form>
                    <?php else : ?>
                        <span class="text-gray-500">No Action</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
