<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Membership Plans</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .header {
            width: 100%;
            max-width: 1200px;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 150px;
            height: auto;
            margin-right: 15px;
        }
        .title {
            font-size: 1.8em;
            font-weight: bold;
            color: #333;
        }
        .membership-container {
            display: flex;
            gap: 20px;
            max-width: 1200px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .membership-card {
            background-color: #fff;
            width: 300px;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            flex: 1;
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

<div class="header">
    <img src="src/img/PrimeStrength.png" alt="Prime Strength Logo" class="logo">
    <div class="title">Prime Strength Membership Plans</div>
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
        <a href="index.php" class="enroll-btn">ENROLL NOW</a>
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
        <a href="index.php" class="enroll-btn">ENROLL NOW</a>
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
        <a href="index.php" class="enroll-btn">ENROLL NOW</a>
    </div>
</div>

</body>
</html>
