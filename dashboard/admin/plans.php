<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();

if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $admin->runQuery("SELECT * FROM transactions WHERE login_email = :email ORDER BY created_at DESC LIMIT 1");
$stmt->execute(array(":email" => $user_data['email']));
$user_plan = $stmt->fetch(PDO::FETCH_ASSOC);
$current_plan = $user_plan ? htmlspecialchars($user_plan['plan']) : 'You are not subscribe to any gym membership plan.';
$current_billing_cycle = $user_plan ? htmlspecialchars($user_plan['billing_cycle']) : '';
$current_plan_display = ($current_plan !== 'You are not subscribe to any gym membership plan.') ? "$current_plan ($current_billing_cycle)" : 'You are not subscribe to any gym membership plan.';

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
            background: url("../../src/img/barbellbg.jpg") no-repeat center center/cover;
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
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            overflow-y: auto;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1px;
        }
        .header h1 {
            margin: 0;
            font-size: 1.5em;
            color: #fff;
            font-family: "Red Hat Display", sans-serif;
            font-optical-sizing: auto;
            font-weight: 500;
            font-style: normal;
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
            background: rgba(0, 0, 0, 0);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            color: #333;
        }
        .content h1 {
            font-size: 3em;
            margin-bottom: 15px;
            color: #fff;
            font-family: "Bebas Neue", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
        .content h2 {
            font-size: 1.2em;
            margin-bottom: 15px;
            color: #fff;
            font-family: "Red Hat Display", sans-serif;
            font-optical-sizing: auto;
            font-weight: 500;
            font-style: normal;
        }
        .card {
            color: #fff;
            padding: 5px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 3em;
            font-family: "Protest Revolution", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
        .membership-container {
            display: flex;
            gap: 20px;
            max-width: 1200px;
            flex-wrap: wrap;
            justify-content: center;
            align-items: stretch;
            margin: 0 auto;
            padding: 20px;
        }
        .membership-card {
            background-color: #fff;
            width: 300px;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 3px solid black;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 0.5s ease-in-out;
        }
        .membership-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .membership-title {
            text-align: center;
            font-size: 1.5em;
            color: #fff;
            margin-bottom: 10px;
            font-weight: bold;
            font-family: "Red Hat Display", sans-serif;
            font-optical-sizing: auto;
            font-weight: 500;
            font-style: normal;
        }
        .membership-card.bronze {
            background: linear-gradient(to right, #dc3545, black);
            color: white;
        }
        .membership-card.silver {
            background-color: black;
            color: white;
        }
        .membership-card.gold {
            background: linear-gradient(to right, black, #dc3545);
            color: white;
        }
        .price {
            font-size: 2em;
            color: #fff;
            margin: 10px 0;
            text-align: center;
            margin-bottom: 10px;
        }
        .price small {
            font-size: 0.5em;
            vertical-align: super;
        }
        .features {
            list-style: none;
            padding: 0;
            margin: 0;
            color: #fff;
            font-size: 1em;
            text-align: left;
            flex-grow: 1;
        }
        .features li {
            margin-bottom: 5px;
        }
        .enroll-btn {
            background-color: red;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
            border: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .enroll-btn:hover {
            background-color: maroon;
            transform: scale(1.05);
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
        <a href="user_profile.php"><i class='bx bxs-user' ></i> Profile</a>
        <a href="plans.php"><i class='bx bx-id-card' ></i> Membership Plans</a>
        <a href="user_notif.php"><i class='bx bxs-bell'></i> Notifications</a>
        <a href="authentication/admin-class.php?admin_signout" class="logout-button"><i class='bx bx-log-out' ></i> Sign Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>MEMBERSHIP PLANS</h1>
            <h1>
            <?php 
            if ($subscription_expired) {
                echo 'CURRENT PLAN: Plan Expired';
            } else {
                echo "[$current_plan] $current_billing_cycle";
            }
        ?></h1>
        </div>
        <div class="content">
            <h1>UNLOCK YOUR POTENTIAL!</h1>
            <h2>Enjoy access to top-notch equipment, expert trainers, 
            and a supportive community to help you achieve your fitness goals.</h2>
            <div class="card">
                <a href="indexpp.php"><img src="../../src/img/MembershipJoinUsBW.png" alt="Company Logo" onerror="alert('Image not found or path is incorrect')">
                </a>
            </div>
            <div class="membership-container">
                <div class="membership-card bronze">
                    <div class="membership-title">Bronze Membership</div>
                    <div class="price">$1.50 <small>/mo</small></div><br>
                    <ul class="features">
                        <li>✔️ Unlimited use of gym equipment</li>
                        <li>✔️ Free health and fitness assessment</li>
                        <li>✔️ Free drinking water</li>
                        <li>✔️ Free WIFI</li>
                        <li>✔️ Free use of locker</li>
                    </ul>
                    <a href="indexpp.php" class="enroll-btn">ENROLL NOW</a>
                </div>

                <div class="membership-card silver">
                    <div class="membership-title">Silver Membership</div>
                    <div class="price">$2.50 <small>/mo</small></div><br>
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
                    <a href="indexpp.php" class="enroll-btn">ENROLL NOW</a>
                </div>

                <div class="membership-card gold">
                    <div class="membership-title">Gold Membership</div>
                    <div class="price">$3.25 <small>/mo</small></div><br>
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
                    <a href="indexpp.php" class="enroll-btn">ENROLL NOW</a>
                </div>
            </div>

        </div>
    </div>
</body>
</html>