<?php
include_once 'config/settings-configuration.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="icon" type="image/png" href="src/img/PrimeStrength.png">
    <style>
       
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center; 
            align-items: center; 
            height: 100vh;
            overflow: hidden;
            background: url('src/img/lobbyy.png') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        
        .container {
            background: rgba(255, 255, 255, 0.9); 
            border: 2px solid #ccc;
            padding: 20px 15px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
            text-align: center;
            width: 80%; 
            max-width: 400px; 
        }

        h1 {
            font-size: 1.6em;
            margin-bottom: 15px;
            color: #333;
            letter-spacing: 1.5px;
        }

        input[type="number"] {
            width: 90%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #f7f7f7;
            color: #333;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        input[type="number"]:focus {
            border-color: #ff4545;
            outline: none;
            box-shadow: 0 0 8px rgba(255, 69, 69, 0.5);
        }

 
        button {
            padding: 12px 20px;
            background: #ff4545;
            border: none;
            border-radius: 6px;
            color: #fff;
            cursor: pointer;
            font-size: 1em;
            transition: all 0.3s ease;
            width: 100%;
        }

        button:hover {
            background: #ff2a2a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 69, 69, 0.5);
        }

       
        button:active {
            transform: translateY(2px);
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Verify OTP</h1>
        <form action="dashboard/admin/authentication/admin-class.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            <input type="number" name="otp" placeholder="Enter OTP" required>
            <button type="submit" name="btn-verify">VERIFY</button>
        </form>
    </div>
</body>
</html>
