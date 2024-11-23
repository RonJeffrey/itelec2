<?php
include_once 'config/settings-configuration.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="src/img/PrimeStrength.png">
    <title>Authentication</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            width: 100%;
            background-color: #007bff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navbar .logo {
            font-size: 1.5em;
            font-weight: bold;
        }

        .navbar .nav-links {
            display: flex;
            gap: 20px;
        }

        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1em;
            padding: 5px 10px;
            transition: background-color 0.3s ease;
        }

        .navbar .nav-links a:hover {
            background-color: #0056b3;
            border-radius: 4px;
        }

        .main-container {
            display: flex;
            width: 80%;
            max-width: 1200px;
            height: 80vh;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin: 80px auto;
            justify-content: center;
        }

        .left-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e0e0e0;
        }

        .right-side {
            flex: 1;
            background: #fff;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .container {
            max-width: 300px;
            margin: auto;
        }

        h1 {
            font-size: 1.2em;
            margin-bottom: 15px;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }

        .button {
            display: inline-block;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            width: 100%;
        }

        .button:hover {
            background-color: #0056b3;
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
        <img src="src/img/PrimeStrength.png" alt="Company Logo" style="width: 80%; height: 80%;" onerror="alert('Image not found or path is incorrect')">
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
                    <h5>Already have an account?<a href="login.php">Login here.</a></h5>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
