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
    } else {
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
            background-color: #181818;
            height: 100vh;
            overflow: hidden;
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
            background-color: #dc3545;
            padding: 20px;
        }


        .header h1 {
            margin: 0;
            font-size: 1.25em;

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
            max-width: 150px;
            max-height: 150px;
            width: auto;
            height: auto;
            border-radius: 50%;
        }

        .gym-schedule {
            background: white;
            padding: 10px;
            border-radius: 8px;
            display: inline-block;
            text-align: center;
            margin: 20px auto;
            position: relative;
        }

        .gym-schedule img {
            max-width: 100%;
            max-height: 600px;
            width: auto;
            height: auto;
            border-radius: 8px;
        }

        .gym-schedule .description {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 10px;
            font-size: 1em;
            border-radius: 5px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .gym-schedule:hover .description {
            opacity: 1;
        }

        .container {
            margin-top: 30px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .col-md-4 {
            width: 32%;
            margin-bottom: 20px;
        }

        .col-md-4 img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .col-md-4 img:hover {
            transform: scale(1.05);
        }


        .video-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .video-grid iframe {
            width: 100%;
            border-radius: 8px;
            height: 200px;
        }

        h3 {
            text-align: center;
            margin: 20px 0;
            background-color: black;
            color: white;
            padding: 10px;
            font-size: 1.5em;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo-membership">
            <img src="../../src/img/PrimeStrength_BlackWhite.png" alt="Company Logo">
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
                <?php if ($subscription_expired) {
                    echo 'CURRENT PLAN: Plan Expired';
                } else {
                    echo "CURRENT PLAN: [$current_plan] $current_billing_cycle";
                } ?>
            </h1>
        </div>

        <h3>Gym Amenities</h3>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="gym-schedule">
                        <img src="../../src/img/lobbyy.png" alt="Facility 1">
                        <div class="description">Waiting Area</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gym-schedule">
                        <img src="../../src/img/44.png" alt="Facility 2">
                        <div class="description">Pilates Area</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gym-schedule">
                        <img src="../../src/img/3a.png" alt="Facility 3">
                        <div class="description">Shower Area</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gym-schedule">
                        <img src="../../src/img/dance.png" alt="Facility 4">
                        <div class="description">Zumba Area</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gym-schedule">
                        <img src="../../src/img/33.png" alt="Facility 5">
                        <div class="description">Cycling Area</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gym-schedule">
                        <img src="../../src/img/55.png" alt="Facility 6">
                        <div class="description">Yoga Area</div>
                    </div>
                </div>
            </div>
        </div>



        <h3>Gym Class Schedule</h3>
        <div style="text-align: center;">
            <img src="../../src/img/sched.jpg" alt="Class Schedule" style="max-width: 100%; border-radius: 8px;"><br>
        </div>

        <br>
        <h3>Gym Workout Tutorials</h3>
        <div class="video-grid">
            <iframe src="https://www.youtube.com/embed/czFDNJYODhA?si=fbcwugAML9-v4b1i" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe src="https://www.youtube.com/embed/T41mYCmtWls?si=JhPCC9zBCeyqjKLY" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe src="https://www.youtube.com/embed/kZDvg92tTMc?si=UQj27QEKMTbtNoxt" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe src="https://www.youtube.com/embed/rEqRmKAQ5xM?si=drsnOmvHvDOwKra6" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe src="https://www.youtube.com/embed/PJLN1kAzbyw?si=IZ9-srMG5A0_1oDK" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
             <iframe src="https://www.youtube.com/embed/eTxO5ZMxcsc?si=qb1YBe7VSyDy6KmQ" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe src="https://www.youtube.com/embed/kPl66RocFDo?si=n-ywKigzUh7sYUNU" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe src="https://www.youtube.com/embed/dnAm_v85_HE?si=7F4xyyMnTQ47he3y" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe src="https://www.youtube.com/embed/T65Y73hpAZw?si=AJyZih1HuH_r5tYW" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe src="https://www.youtube.com/embed/mf1xQS_Zlkc?si=pJxwQdWxA18ij8mO" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe src="https://www.youtube.com/embed/Kjh0E9LrxRo?si=ULpUgxOBFeonPgBr" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe src="https://www.youtube.com/embed/u20cgW0MmmM?si=lKG7pi0_7zZrB4DN" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe src="https://www.youtube.com/embed/pJ4gl5W2sss?si=cWOXP-KrsZ92_vbx" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe src="https://www.youtube.com/embed/1-c-yvj1XyE?si=VdkoQScA4HLpGfOF" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe src="https://www.youtube.com/embed/6rsffeMsRxI?si=UrTfx2_jhgzJ200o" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

        </div>


</body>

</html>
