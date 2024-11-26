<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    <link rel="icon" type="image/png" href="src/img/PrimeStrength.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url(src/img/ggg.jpg) no-repeat center center/cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: white;
        }

        .navbar {
            width: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navbar .logo {
            font-size: 1.8em;
            font-weight: 600;
            filter: grayscale(100%);
        }

        .navbar .nav-links {
            display: flex;
            gap: 20px;
        }

        .navbar .nav-links a {
            color:red;
            text-decoration: none;
            font-size: 1em;
            padding: 5px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .navbar .nav-links a:hover {
            background-color: #555;
        }

        .main-container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            margin: 100px auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            background-color: rgba(0, 0, 0, 0.7);
        }

        .left-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .left-side img {
            width: 80%;
            height: auto;
            animation: float 3s infinite;
            filter: grayscale(100%);
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .right-side {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
        }

        .container {
            max-width: 400px;
            margin: auto;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 15px;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }

        input::placeholder {
            color: #888;
        }

        .button {
             width: 100%;
             padding: 10px;
             background-color:white;
             border: none;
             border-radius: 4px;
             color: black;
             font-size: 1em;
            font-weight: 600;
             cursor: pointer;
             transition: all 0.3s ease;
        }

        .button:hover {
            background-color: #800000;
            color: black;
        }      


        h5 {
            margin-top: 15px;
            font-size: 0.9em;
        }

        h5 a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        h5 a:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="logo">PrimeStrength</div>
        <div class="nav-links">
            <a href="memplans.php">MEMBERSHIP PLANS</a>
            <a href="#">CONTACT</a>
            <a href="login.php">LOGIN</a>
        </div>
    </div>

    <div class="main-container">
        <div class="left-side">
            <img src="src/img/PrimeStrength.png" alt="Company Logo" onerror="alert('Image not found or path is incorrect')">
        </div>

        <div class="right-side">
            <div class="container">
                <h1>Register</h1>
                <form action="dashboard/admin/authentication/admin-class.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="btn-signup" class="button">Sign Up</button>
                    <h5>Already have an account? <a href="login.php">Login here.</a></h5>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
