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
        .card {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }
        .membership-container {
            display: flex;
            gap: 20px;
            max-width: 1200px;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
            padding: 20px;
        }
        .membership-card {
            background-color: #fff;
            width: 300px;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            flex: 1;
            margin-bottom: 20px;
        }
        .membership-title {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .price {
            font-size: 2em;
            color: #333;
            margin: 10px 0;
        }
        .price small {
            font-size: 0.5em;
            vertical-align: super;
        }
        .features {
            list-style: none;
            padding: 0;
            margin: 20px 0;
            color: #666;
            font-size: 1em;
            text-align: left;
        }
        .enroll-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>User Dashboard</h2>
        <a href="user_dashboard.php">Home</a>
        <a href="#">Profile</a>
        <a href="#">Settings</a>
        <a href="plans.php">Membership Plans</a>
        <a href="#">Notifications</a>
        <a href="authentication/admin-class.php?admin_signout" class="logout-button">Sign Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Welcome, <?php echo htmlspecialchars($user_data['email']); ?></h1>
        </div>
        <div class="content">
            <h2>Dashboard Overview</h2>
            <div class="card">
                <p>Welcome to your dashboard. Here you can manage your profile, view notifications, and explore more
                    features.</p>
            </div>
            <div class="card">
                <p>MEMBERSHIP PLANS!</p>
            </div>
            <div class="membership-container">
                <div class="membership-card">
                    <div class="membership-title">Bronze Membership</div>
                    <div class="price">₱1500 <small>/mo</small></div>
                    <ul class="features">
                        <li>✔️ Unlimited use of gym equipment</li>
                        <li>✔️ Free health and fitness assessment</li>
                        <li>✔️ Free drinking water</li>
                        <li>✔️ Free WIFI</li>
                        <li>✔️ Free use of locker</li>
                    </ul>
                    <a href="bronzemem.php" class="enroll-btn">ENROLL NOW</a>
                </div>

                <div class="membership-card">
                    <div class="membership-title">Silver Membership</div>
                    <div class="price">₱2000 <small>/mo</small></div>
                    <ul class="features">
                        <li>✔️ Unlimited use of gym equipment</li>
                        <li>✔️ Free health and fitness assessment</li>
                        <li>✔️ Free drinking water</li>
                        <li>✔️ Free WIFI</li>
                        <li>✔️ Free use of locker</li>
                        <li>✔️ Free 2 sessions with trainer</li>
                        <li>✔️ Free use of hot/cold shower</li>
                        <li>✔️ Free 5 guest pass</li>
                    </ul>
                    <a href="silvermem.php" class="enroll-btn">ENROLL NOW</a>
                </div>

                <div class="membership-card">
                    <div class="membership-title">Gold Membership</div>
                    <div class="price">₱3000 <small>/mo</small></div>
                    <ul class="features">
                        <li>✔️ Unlimited use of gym equipment</li>
                        <li>✔️ Free health and fitness assessment</li>
                        <li>✔️ Free drinking water</li>
                        <li>✔️ Free WIFI</li>
                        <li>✔️ Free use of locker</li>
                        <li>✔️ Free 5 sessions with trainer</li>
                        <li>✔️ Free use of hot/cold shower</li>
                        <li>✔️ Free 10 guest pass</li>
                        <li>✔️ Free parking space</li>
                        <li>✔️ Free group classes (Zumba, Pilates, Yoga)</li>
                    </ul>
                    <a href="goldmem.php" class="enroll-btn">ENROLL NOW</a>
                </div>
            </div>

        </div>
    </div>
</body>

</html>