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
            background-color: #f4f4f4;
            height: 100vh;
            overflow: hidden;
            background: url("../../src/img/BlackRedEnergy.jpg") no-repeat center center/cover;
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
            background-color: #dc3545;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }
        .logo-membership {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }
        .logo-membership img {
            max-width: 150px; /* Adjust size as needed */
            max-height: 150px; /* Adjust size as needed */
            width: auto;
            height: auto;
            border-radius: 50%; /* Optional for rounded logo */
        }
        .gym-schedule {
            background: white;
            padding: 10px; /* Optional padding around the image */
            border-radius: 8px; /* Optional rounded corners */
            display: inline-block; /* Make container hug the image */
            text-align: center; /* Center the image horizontally */
            margin: 20px auto; /* Center the container on the page */
        }
        .gym-schedule img {
            max-width: 100%; /* Ensure the image scales with the container */
            max-height: 600px; /* Set a maximum height for the image */
            width: auto;
            height: auto;
            border-radius: 8px; /* Optional: Rounded corners for the image */
            filter: grayscale(100%);
        }
        .content-schedule {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center horizontally */
            justify-content: center; /* Center vertically */
            padding: 20px;
            overflow-y: auto;
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
            <h1>Welcome, <?php echo htmlspecialchars($user_data['email']); ?></h1>
            <h1>Current Plan: [<?php echo $current_plan; ?>]<?php echo $current_billing_cycle; ?></h1>
        </div>
        <div class="gym-schedule">
                <img src="../../src/img/GymScheduleRevised.jpg" alt="Gym Schedule" onerror="alert('Image not found or path is incorrect')">
        </div>
    </div>
</body>
</html>
