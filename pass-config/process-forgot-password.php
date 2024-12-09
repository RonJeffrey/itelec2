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
    $mail->setFrom($smtp_email, "Ron");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";

    $mail->Body = "Click <a href=\"http://localhost/group2/itelec2/pass-config/reset-password.php?token=$token&id=$user_id\">HERE</a> to reset your password.";

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
