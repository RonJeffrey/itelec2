<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();

if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);
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
            background-color: white;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            color: #000000; 
            padding: 20px;
            border-radius: 10px;
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
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
        }
        .content h2 {
            font-size: 1.4em;
            margin-bottom: 15px;
            color: #000000;
        }
        .card {
            background-color: #2c3e50;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 20px;
        }
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
            <h1>Welcome, <?php echo htmlspecialchars($user_data['email']); ?></h1>
        </div>
        <div class="content">
            <h2>Dashboard Overview</h2>
            <div class="card">
                <p>Welcome to your dashboard. Here you can manage your profile, view notifications, and explore more features.</p>
            </div>
            <div class="card">
                <p>Stay tuned for updates!</p>
            </div>
        </div>
    </div>
</body>
</html>
