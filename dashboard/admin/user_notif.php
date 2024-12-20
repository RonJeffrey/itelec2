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
$current_plan = $user_plan ? htmlspecialchars($user_plan['plan']) : 'You are not subscribed to any gym membership plan.';
$current_billing_cycle = $user_plan ? htmlspecialchars($user_plan['billing_cycle']) : '';
$current_plan_display = ($current_plan !== 'You are not subscribed to any gym membership plan.') ? "$current_plan ($current_billing_cycle)" : 'You are not subscribed to any gym membership plan.';

$current_date = date('Y-m-d');

$subscription_expired = false;

if ($user_plan) {
    $expiration_date = date('Y-m-d', strtotime($user_plan['expiration_date']));

    if ($current_date > $expiration_date) {
        $stmt = $admin->runQuery("UPDATE user SET subscription_status = 'expired' WHERE email = :email");
        $stmt->execute(array(":email" => $user_data['email']));
        
        $subscription_expired = true;
    }else {
        $stmt = $admin->runQuery("UPDATE user SET subscription_status = 'Member' WHERE email = :email");
        $stmt->execute(array(":email" => $user_data['email']));
        $subscription_expired = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="icon" type="image/png" href="src/img/PrimeStrength.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Protest+Revolution&family=Red+Hat+Display:ital,wght@0,300..900;1,300..900&display=swap');
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
            background-color: #181818;
            color: #fff;
        }
        .sidebar {
            width: 250px;
            background-color: rgba(0, 0, 0, 0.8);
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
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
            background-color: rgba(255, 255, 255, 0.2);
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
            background-color: #dc3545;
            padding: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 1.2em;
            color: #fff;
            font-family: "Red Hat Display", sans-serif;
        }
        .content {
            background: #2c2c2c;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.7);
        }
        .content h2 {
            font-size: 1.4em;
            margin-bottom: 15px;
            color: #ff4545;
        }
        .card {
            background: #3a3a3a;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }
        .card h3 {
            color: #ff4545;
            margin-bottom: 10px;
        }
        .logo-membership {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }
        .logo-membership img {
            max-width: 150px;
            max-height: 150px;
            width: auto;
            height: auto;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo-membership">
            <img src="../../src/img/PrimeStrength_BlackWhite.png" alt="Company Logo" onerror="alert('Image not found or path is incorrect')">
        </div>
        <h2>User Dashboard</h2>
        <a href="user_dashboard.php"><i class='bx bxs-home'></i> Home</a>
        <a href="user_profile.php"><i class='bx bxs-user'></i> Profile</a>
        <a href="plans.php"><i class='bx bx-id-card'></i> Membership Plans</a>
        <a href="user_notif.php"><i class='bx bxs-bell'></i> Notifications</a>
        <a href="authentication/admin-class.php?admin_signout" class="logout-button"><i class='bx bx-log-out'></i> Sign Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Welcome, <?php echo htmlspecialchars($user_data['email']); ?></h1>
            <h1>
            <?php 
            if ($subscription_expired) {
                echo 'CURRENT PLAN: Plan Expired';
            } else {
                echo "CURRENT PLAN: [$current_plan] $current_billing_cycle";
            }
        ?>
        </h1>
        </div>
        <div class="content">
            <h2>Latest Notifications:</h2>
            <div class="card">
                <?php if (count($notifications) > 0): ?>
                    <ul>
                        <?php foreach ($notifications as $notification): ?>
                            <li>
                                <p><?php echo htmlspecialchars($notification['message']); ?></p>
                                <small>Posted on: <?php echo $notification['created_at']; ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No announcements at the moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
