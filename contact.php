<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="src/img/PrimeStrength.png">
    <title>Contact Us - PrimeStrength</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #000;
            padding: 15px 20px;
            color: white;
        }

        .navbar .logo {
            font-size: 1.5em;
            font-weight: bold;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 1em;
        }

        .nav-links a:hover {
            color: #ff4500;
        }

        .hero-section {
            background: url(src/img/4.jpg) no-repeat center center/cover;
            height: 300px;
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .hero-section h1 {
            font-size: 2.5em;
            margin: 0;
        }

        .hero-section p {
            font-size: 1.2em;
            margin: 5px 0;
        }

        .contact-section {
            flex: 1;
            padding: 50px;
            text-align: center;
            background: #fff;
        }

        .contact-section h2 {
            font-size: 2em;
            color: red;
            margin-bottom: 20px;
        }

        .contact-section h1 {
            font-size: 2em;
            color: black;
            margin-bottom: 20px;
        }

        .contact-details {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 30px;
        }

        .contact-details .detail {
            text-align: center;
        }

        .contact-details i {
            font-size: 2.5em;
            color:red;
            margin-bottom: 10px;
        }

        .contact-details p {
            font-size: 1em;
            color: black;
            margin: 5px 0;
        }

        footer {
            text-align: center;
            padding: 10px;
            background: #000;
            color: white;
            font-size: 0.9em;
            margin-top: auto;
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

   
    <div class="hero-section">
        <h1>Contact Us</h1>
    </div>

   
    <div class="contact-section">
        <h2>Get In Touch</h2>
        <h1>Message Us For Any Queries</h1>
        <div class="contact-details">
            <div class="detail">
                <i class="fa fa-map-marker-alt"></i>
                <p>Address</p>
                <p>DHVSU Bacolor, Pampanga</p>
            </div>
            <div class="detail">
                <i class="fa fa-phone-alt"></i>
                <p>Phone</p>
                <p>09123456789</p>
            </div>
            <div class="detail">
                <i class="fa fa-envelope"></i>
                <p>Email</p>
                <p>primestrength@gmail.com</p>
            </div>
        </div>
    </div>

   
    <footer>
        © 2024 PrimeStrength. All rights reserved.
    </footer>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</body>
</html>
