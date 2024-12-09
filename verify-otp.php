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
            font-family: Arial, sans-serif;
            background-color: #1a1a1a; /* Dark background */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #f0f0f0; /* Light text color */
        }

        .wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 1200px;
            width: 90%;
        }

        .image-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-right: 20px;
        }

        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .container {
            flex: 1;
            background: #2e2e2e; /* Darker container background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #fff; /* White text for header */
        }

        input[type="number"] {
            width: 90%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #444; /* Subtle border */
            border-radius: 6px;
            background-color: #444; /* Dark input background */
            color: #f0f0f0; /* Light text for inputs */
            font-size: 1em;
        }

        input[type="number"]::placeholder {
            color: #ccc; /* Placeholder color */
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
            width: 90%;
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

        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }

            .image-container {
                margin-bottom: 20px;
                padding-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="image-container">
            <img src="src/img/otp.png" alt="OTP Verification Illustration">
        </div>
        <div class="container">
            <h1>Verify OTP</h1>
            <form action="dashboard/admin/authentication/admin-class.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <input type="number" name="otp" placeholder="Enter OTP" required>
                <button type="submit" name="btn-verify">VERIFY</button>
            </form>
        </div>
    </div>
</body>
</html>
