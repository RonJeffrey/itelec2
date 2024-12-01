<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();

if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $admin->runQuery("SELECT * FROM notifications ORDER BY created_at DESC");
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $admin->runQuery("SELECT * FROM transactions WHERE login_email = :email ORDER BY created_at DESC LIMIT 1");
$stmt->execute(array(":email" => $user_data['email']));
$user_plan = $stmt->fetch(PDO::FETCH_ASSOC);
$current_plan = $user_plan ? htmlspecialchars($user_plan['plan']) : 'You are not subscribe to any gym membership plan.';
$current_billing_cycle = $user_plan ? htmlspecialchars($user_plan['billing_cycle']) : '';
$current_plan_display = ($current_plan !== 'You are not subscribe to any gym membership plan.') ? "$current_plan ($current_billing_cycle)" : 'You are not subscribe to any gym membership plan.';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="icon" type="image/png" href="src/img/PrimeStrength.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            background-color: #f4f4f4;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.5em;
        }

        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 1em;
            margin: 15px 0;
            padding: 10px;
            border-radius: 5px;
            display: block;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 1.5em;
        }

        .logout-button {
            background-color: #dc3545;
            border: none;
            border-radius: 5px;
            color: #fff;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 0.9em;
            text-align: center;
            text-decoration: none;
        }

        .logout-button:hover {
            background-color: #c82333;
        }

        .content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .content h2 {
            font-size: 1.2em;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 400px;
            text-align: center;
        }

        .modal button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .modal button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>User Dashboard</h2>
        <a href="user_dashboard.php">Home</a>
        <a href="user_profile.php">Profile</a>
        <a href="plans.php">Membership Plans</a>
        <a href="user_notif.php">Notifications</a>
        <a href="authentication/admin-class.php?admin_signout" class="logout-button">Sign Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Welcome, <?php echo htmlspecialchars(string: $user_data['email']); ?></h1>
            <h1>Current Plan: [<?php echo $current_plan; ?>]<?php echo $current_billing_cycle; ?></h1>
        </div>
        <div class="content">
            <h2>Dashboard Overview</h2>
            <div class="card">
                <p>Welcome to your dashboard. Here you can manage your profile, view notifications, and explore more
                    features.</p>
            </div>

            <div class="card">
                <h3>Latest Notifications:</h3>
                <?php if (count($notifications) > 0): ?>
                    <ul>
                        <?php
                        foreach ($notifications as $notification) {
                            echo '<li>';
                            echo '<p>' . htmlspecialchars($notification['message']) . '</p>';
                            echo '<small>Posted on: ' . $notification['created_at'] . '</small>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                <?php else: ?>
                    <p>No announcements at the moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>