<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../database/dbconnection.php';
include_once __DIR__ . '/../config/settings-configuration.php';
require_once __DIR__ . '/../src/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$token = filter_input(INPUT_GET, 'token');


try {
    $systemConfig = new SystemConfig();
    $smtp_email = $systemConfig->getSmtpEmail();
    $smtp_password = $systemConfig->getSmtpPassword();
} catch (Exception $e) {
    die("Error loading SMTP configuration: " . $e->getMessage());
}

if (!isset($smtp_email) || !isset($smtp_password)) {
    die("SMTP configuration is missing.");
}

$email = $_POST['email'];

$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 60 * 24);

$database = new Database();
$mysqli = $database->dbConnection(); 

$sql = "SELECT id FROM user WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->execute([$email]);

$user = $stmt->fetch();
if (!$user) {
    echo "<script>
            alert('User not found.');
          </script>";
    exit;
}
$user_id = $user['id'];


$sql = "UPDATE user SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->execute([$token_hash, $expiry, $email]);

if ($stmt->rowCount()) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->isHTML(true);
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->Username = $smtp_email;
    $mail->Password = $smtp_password;
    $mail->setFrom($smtp_email, "PrimeStrength Support");
    $mail->addAddress($email);
    $mail->Subject = "Reset Password";

    $mail->Body = "<html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }
            .container {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            .header {
                text-align: center;
                padding-bottom: 20px;
            }
            .content {
                font-size: 16px;
                color: #333;
                line-height: 1.5;
                padding-bottom: 20px;
            }
            .button {
                display: inline-block;
                padding: 12px 20px;
                background-color: #007bff;
                color: #ffffff;
                text-decoration: none;
                border-radius: 4px;
                text-align: center;
                font-size: 16px;
            }
            .footer {
                font-size: 12px;
                color: #888;
                text-align: center;
                margin-top: 30px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Password Reset Request</h2>
            </div>
            <div class='content'>
                <p>Hello,</p>
                <p>We received a request to reset your password. If you did not request a password reset, please ignore this email.</p>
                <p>To reset your password, click the link below:</p>
                <p><a href='http://localhost/itelec2/pass-config/reset-password.php?token=$token&id=$user_id' class='button'>Click Here to Reset Your Password</a></p>
            </div>
            <div class='footer'>
                <p>If you have any questions, please contact our support team.</p>
            </div>
        </div>
    </body>
    </html>";


    try {
        $mail->send();
        echo "<script>
                alert('Message sent, please check your inbox.');
                window.location.href = '../';
              </script>";
        exit;
    } catch (Exception $e) {
        echo "<script>
                alert('Mailer Error: " . $mail->ErrorInfo . "');
              </script>";
        exit;
    }

} else {
    echo "<script>
            alert('No record updated. Please check if the email exists.');
          </script>";
    exit;
}
?>
