<?php
include_once '../config/settings-configuration.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
            color: #f0f0f0; /* Light text color for contrast */
        }

        .wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 1200px;
            width: 100%;
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
            text-align: left;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #fff; /* White text for header */
        }

        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #444; /* Subtle border */
            border-radius: 4px;
            background-color: #444; /* Dark input background */
            color: #f0f0f0; /* Light text for inputs */
        }

        input[type="email"]::placeholder {
            color: #ccc; /* Light placeholder text */
        }

        button {
            padding: 10px 20px;
            background-color: #d9534f;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #c9302c;
        }

        .message {
            margin-top: 15px;
            font-size: 0.9em;
            color: #ccc; /* Subtle text color for the message */
        }

        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
                text-align: center;
            }

            .image-container {
                padding-right: 0;
                margin-bottom: 20px;
            }

            .container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="image-container">
            <img src="../src/img/forgot.png" alt="Forgot Password Illustration">
        </div>
        <div class="container">
            <h1>Forgot Password</h1>
            <form action="process-forgot-password.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit" name="btn-reset">Send Reset Link</button>
            </form>
            <div class="message">
                <p>We'll send you a link to reset your password.</p>
            </div>
        </div>
    </div>
</body>
</html>
