<?php
session_start();
require_once 'authentication/admin-class.php';

$admin = new ADMIN();

if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

// Fetch all users
$stmt = $admin->runQuery("SELECT * FROM user");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$users) {
    $users = [];
}

// Fetch email configuration
$stmt = $admin->runQuery("SELECT * FROM email_config");
$stmt->execute();
$emailConfig = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$emailConfig) {
    $emailConfig = [];
}

// Create User
if (isset($_POST['create_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $admin->runQuery("INSERT INTO user (username, email, password, Type) VALUES (:username, :email, :password, :role)");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $password,
        ':role' => $role
    ]);

    header("Location: admin_dashboard.php");
    exit();
}

// Update User
if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $admin->runQuery("UPDATE user SET username = :username, email = :email, Type = :role WHERE id = :id");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':role' => $role,
        ':id' => $id
    ]);

    $_SESSION['update_success'] = true;
    header("Location: admin_dashboard.php");
    exit();
}

// Delete User
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $admin->runQuery("DELETE FROM user WHERE id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: admin_dashboard.php");
    exit();
}

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../../");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - User Management</title>
    <link rel="icon" type="image/png" href="src/img/PrimeStrength.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    background-color: #f4f4f4;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.sidebar {
    width: 215px;
    height: 100vh;
    background-color: #2c2c2c;
    color: #fff;
    position: fixed;
    top: 0;
    left: 0;
    padding: 20px;
    box-shadow: 3px 0px 8px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    animation: fadeIn 0.6s ease;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 24px;
    font-weight: bold;
    color: #e74c3c;
}

.sidebar a {
    color: #fff;
    text-decoration: none;
    display: block;
    margin: 15px 0;
    padding: 12px 15px;
    border-radius: 5px;
    font-size: 18px;
    transition: all 0.3s ease;
    position: relative;
}

.sidebar a:hover {
    background-color: #e74c3c;
    transform: translateX(5px);
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
}

.sidebar a.active {
    background-color: #e74c3c;
}

.sidebar .logout-btn {
    padding: 10px 15px;
    background-color: #c0392b;
    color: white;
    border: none;
    border-radius: 5px;
    text-align: center;
    width: 100%;
    font-size: 16px;
    margin-top: 20px;
    transition: all 0.3s ease;
}

.sidebar .logout-btn:hover {
    background-color: #a93226;
    transform: scale(1.05);
}

.content {
    margin-left: 250px;
    padding: 20px;
    width: calc(100% - 250px);
    background-color: #ffffff;
    min-height: 100vh;
    animation: fadeIn 0.6s ease;
}

h1, h2 {
    color: #333;
    animation: fadeIn 0.8s ease;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    animation: fadeIn 0.8s ease;
}

table, th, td {
    border: 1px solid #ddd;
}

th {
    background-color: #e74c3c;
    color: #fff;
    font-weight: bold;
}

td, th {
    padding: 10px;
    text-align: left;
}

tr:hover {
    background-color: #f8d7da;
}

.btn {
    display: inline-block;
    padding: 8px 12px;
    margin: 5px;
    background-color: #e74c3c;
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn:hover {
    background-color: #c0392b;
    transform: scale(1.05);
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.btn-danger {
    background-color: #b03a2e;
}

.btn-danger:hover {
    background-color: #922b21;
    transform: scale(1.05);
}

input, select {
    margin-bottom: 10px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 100%;
    transition: all 0.3s ease;
}

input:focus, select:focus {
    border-color: #e74c3c;
    box-shadow: 0 0 5px rgba(231, 76, 60, 0.5);
    outline: none;
}

.modal {
    display: none;
    position: fixed;
    z-index: 10;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    animation: fadeIn 0.6s ease;
}

.modal.active {
    display: block;
}

.modal h3 {
    margin: 0 0 20px;
    color: #333;
}

    </style>
</head>

<body>
<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : ''; ?>">
        <div class="icon"><i class="fa fa-tachometer-alt"></i></div> Dashboard
    </a>
    <a href="manage_users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>">
        <div class="icon"><i class="fa fa-users"></i></div> Manage Users
    </a>
    <a href="logs.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'logs.php' ? 'active' : ''; ?>">
        <div class="icon"><i class="fa fa-list-alt"></i></div> Logs
    </a>
    <form method="POST" action="">
        <button type="submit" name="logout" class="logout-btn">Logout</button>
    </form>
</div>

<div class="content">
    <h1>User Management</h1>

    <form method="POST" action="">
        <h3>Create New User</h3>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <button type="submit" name="create_user" class="btn">Create User</button>
    </form>

    <h2>List of Users</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['Type']); ?></td>
                    <td>
                        <button onclick="document.getElementById('editModal<?php echo $user['id']; ?>').classList.add('active');" class="btn">Edit</button>
                        <a href="?delete_id=<?php echo $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>

                <div class="modal" id="editModal<?php echo $user['id']; ?>">
                    <h3>Edit User</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        <select name="role" required>
                            <option value="admin" <?php echo ($user['Type'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="user" <?php echo ($user['Type'] == 'user') ? 'selected' : ''; ?>>User</option>
                        </select>
                        <button type="submit" name="update_user" class="btn">Save Changes</button>
                        <button type="button" onclick="document.getElementById('editModal<?php echo $user['id']; ?>').classList.remove('active')">Close</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No users found</td>
            </tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
