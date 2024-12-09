<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Membership Plans</title>
    <link rel="icon" type="image/png" href="src/img/PrimeStrength.png">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            color: white;
            background: url(src/img/d.png) no-repeat center center fixed;
            background-size: cover;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #000;
            padding: 15px 20px;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            box-sizing: border-box;
        }

        .navbar .logo {
            font-size: 1.5em;
            font-weight: bold;
            white-space: nowrap;
        }

        .nav-links {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1em;
            padding: 8px 12px;
        }

        .nav-links a:hover {
            color: #ff4500;
        }

        .header {
            text-align: center;
            margin-bottom: 2%;
        }

        .logo {
            width: 100px;
            height: auto;
            filter: grayscale(100%);
            margin-bottom: 15px;
        }

        .title {
            font-size: 1.8em;
            font-weight: bold;
        }

        .membership-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            max-width: 90%;
            padding: 10px;
            background: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
        }

        .membership-card {
            background: rgba(255, 255, 255, 0.2);
            width: 28%;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            height: 100%;
        }

        .membership-title{
            text-align: center;
            margin-bottom: 10px;
            font-size: 1.5em;
        }

        .price {
            text-align: center;
            margin-bottom: 10px;
            font-size: 2.0em;
        }

        .features {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 0.9em;
            text-align: left;
            flex-grow: 1;
        }

        .features li {
            margin-bottom: 5px;
        }

        .enroll-btn {
            background-color: #800000;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
            align-self: flex;
        }

        .enroll-btn:hover {
            background-color: #600000;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="logo">PrimeStrength</div>
        <div class="nav-links">
            <a href="memplans.php">MEMBERSHIP PLANS</a>
            <a href="contact.php">CONTACT</a>
            <a href="login.php">LOGIN</a>
        </div>
    </div>

    <div class="header">
        <img src="src/img/PrimeStrength.png" alt="Prime Strength Logo" class="logo">
        <div class="title">Prime Strength Membership Plans</div>
    </div>

    <div class="membership-container">
        <div class="membership-card">
            <div class="membership-title">Bronze Membership</div>
            <div class="price">$1.50 <small>/mo</small></div><br>
            <ul class="features">
                <li>✔ Unlimited use of gym equipment</li>
                <li>✔ Free health and fitness assessment</li>
                <li>✔ Free drinking water</li>
                <li>✔ Free WIFI</li>
                <li>✔ Free use of locker</li>
            </ul>
            <a href="index.php" class="enroll-btn">ENROLL NOW</a>
        </div>

        <div class="membership-card">
            <div class="membership-title">Silver Membership</div>
            <div class="price">$2.25 <small>/mo</small></div><br>
            <ul class="features">
                <li>✔ Unlimited use of gym equipment</li>
                <li>✔ Free health and fitness assessment</li>
                <li>✔ Free drinking water</li>
                <li>✔ Free WIFI</li>
                <li>✔ Free use of locker</li>
                <li>✔ Free 2 sessions with trainer</li>
                <li>✔ Free use of hot/cold shower</li>
                <li>✔ Free 5 guest pass</li>
            </ul>
            <a href="index.php" class="enroll-btn">ENROLL NOW</a>
        </div>

        <div class="membership-card">
            <div class="membership-title">Gold Membership</div>
            <div class="price">$3.25 <small>/mo</small></div><br>
            <ul class="features">
                <li>✔ Unlimited use of gym equipment</li>
                <li>✔ Free health and fitness assessment</li>
                <li>✔ Free drinking water</li>
                <li>✔ Free WIFI</li>
                <li>✔ Free use of locker</li>
                <li>✔ Free 5 sessions with trainer</li>
                <li>✔ Free use of hot/cold shower</li>
                <li>✔ Free 10 guest pass</li>
                <li>✔ Free parking space</li>
                <li>✔ Free group classes (Zumba, Pilates, Yoga)</li>
            </ul>
            <a href="index.php" class="enroll-btn">ENROLL NOW</a>
        </div>
    </div>

</body>

</html>
