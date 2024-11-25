<?php
session_start();
require_once 'authentication/admin-class.php';

$admin = new ADMIN();

if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

$stmt = $admin->runQuery("SELECT * FROM user");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$users) {
    $users = [];
}

$stmt = $admin->runQuery("SELECT * FROM email_config");
$stmt->execute();
$emailConfig = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$emailConfig) {
    $emailConfig = [];
}

if (isset($_POST['create_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $admin->runQuery("INSERT INTO user (username, email, password, role) VALUES (:username, :email, :password, :role)");
    $stmt->execute([':username' => $username, ':email' => $email, ':password' => $password, ':role' => $role]);
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
    $stmt->execute([':username' => $username, ':email' => $email, ':role' => $role, ':id' => $id]);

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
        }

        .sidebar {
            width: 215px;
            height: 100vh;
            background-color: #2C3E50;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: bold;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            margin: 15px 0;
            padding: 12px 15px;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #16A085;
        }

        .sidebar a.active {
            background-color: #1ABC9C;
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 20px;
        }

        .sidebar .logout-btn {
            padding: 10px 15px;
            background-color: #E74C3C;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            width: 100%;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .sidebar .logout-btn:hover {
            background-color: #C0392B;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
            width: 100%;
            background-color: #f4f4f4;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        .btn {
            display: inline-block;
            padding: 5px 10px;
            margin: 5px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input,
        select {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group {
            display: flex;
            justify-content: space-between;
        }

        .form-group div {
            flex: 1;
            margin-right: 10px;
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
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            border-radius: 5px;
        }

        .modal.active {
            display: block;
        }

        .modal h3 {
            margin: 0 0 20px;
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

        <h2>SMTP Email Configuration</h2>
        <table>
            <tr>
                <th>Email</th>
                <th>Password</th>
                <th>Actions</th>
            </tr>
            <?php if (!empty($emailConfig)): ?>
                <?php foreach ($emailConfig as $config): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($config['email']); ?></td>
                        <td><?php echo htmlspecialchars($config['password']); ?></td>
                        <td>
                            <button onclick="document.getElementById('editModal<?php echo $config['id']; ?>').classList.add('active');" class="btn">Edit</button>
                        </td>
                    </tr>

                    <div id="editModal<?php echo $config['id']; ?>" class="modal">
                        <h3>Edit Email Configuration</h3>
                        <form method="POST" action="">
                            <input type="hidden" name="id" value="<?php echo $config['id']; ?>">
                            <input type="email" name="email" value="<?php echo htmlspecialchars($config['email']); ?>" required>
                            <input type="text" name="password" value="<?php echo htmlspecialchars($config['password']); ?>" required>
                            <button type="submit" name="update_email_config" class="btn">Update Email Config</button>
                            <button type="button" onclick="document.getElementById('editModal<?php echo $config['id']; ?>').classList.remove('active');" class="btn btn-danger">Cancel</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No email configuration found</td>
                </tr>
            <?php endif; ?>
        </table>

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
                        <td><?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
                        <td><?php echo isset($user['Type']) && !empty($user['Type']) ? htmlspecialchars($user['Type']) : 'N/A'; ?>
                        </td>
                        <td>
                            <button
                                onclick="document.getElementById('editModal<?php echo $user['id']; ?>').classList.add('active');"
                                class="btn">Edit</button>
                            <a href="?delete_id=<?php echo $user['id']; ?>" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>

                    <div class="modal" id="editModal<?php echo $user['id']; ?>">
                        <h3>Edit User</h3>
                        <form method="POST" action="">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"
                                required>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            <select name="role" required>
                                <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                            </select>
                            <button type="submit" name="update_user" class="btn">Save Changes</button>
                            <button type="button"
                                onclick="document.getElementById('editModal<?php echo $user['id']; ?>').classList.remove('active')">Close</button>
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

    <script>
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = 'admin_dashboard.php?delete_id=' + id;
            }
        }
    </script>
</body>

</html>