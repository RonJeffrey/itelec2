<?php
session_start();

// Include the database connection
require_once __DIR__ . "/../database/dbconnection.php";

$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);

if (!$token) {
    die("Invalid token.");
}

$token_hash = hash("sha256", $token);

// Create a new instance of the Database class
$database = new Database();
$mysqli = $database->dbConnection(); 

$sql = "SELECT * FROM user WHERE reset_token_hash = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bindValue(1, $token_hash, PDO::PARAM_STR);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result === false) {
    die("Token not found.");
}

if (strtotime($result["reset_token_expires_at"]) <= time()) {
    die("Token has expired.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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

        label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            font-size: 1em;
            color: #ccc; /* Subtle label text color */
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #444; /* Subtle border */
            border-radius: 4px;
            background-color: #444; /* Dark input background */
            color: #f0f0f0; /* Light text for inputs */
        }

        input[type="password"]::placeholder {
            color: #ccc; /* Placeholder color */
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
            width: 100%;
        }

        button:hover {
            background-color: #c9302c;
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
            <img src="../src/img//reset.png" alt="Reset Password Illustration">
        </div>
        <div class="container">
            <h1>Reset Password</h1>
            <form method="post" action="process-reset-password.php">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required>

                <label for="password_confirmation">Repeat Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>

                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
