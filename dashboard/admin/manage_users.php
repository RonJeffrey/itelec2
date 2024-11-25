<?php
session_start();
require_once 'authentication/admin-class.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$admin = new ADMIN();

if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

$stmt = $admin->runQuery("SELECT * FROM user");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$users) {
    $users = [];
}

$stmt = $admin->runQuery("SELECT * FROM email_config");
$stmt->execute();
$emailConfig = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$emailConfig) {
    $emailConfig = [];
}

// Send Announcement to All Users
if (isset($_POST['send_announcement'])) {
    $announcement = trim($_POST['announcement']);

    if (empty($announcement)) {
        echo "<script>alert('Announcement cannot be empty!');</script>";
    } else {
        if (count($emailConfig) > 0) {
            $email = $emailConfig[0]['email'];
            $password = $emailConfig[0]['password'];

            require '../../src/vendor/phpmailer/phpmailer/src/PHPMailer.php';
            require '../../src/vendor/phpmailer/phpmailer/src/SMTP.php';
            require '../../src/vendor/phpmailer/phpmailer/src/Exception.php';

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $email;
                $mail->Password = $password;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom($email, 'Admin');
                $mail->isHTML(true);
                $mail->Subject = 'Announcement from Admin';
                $mail->Body = $announcement;

                foreach ($users as $user) {
                    $mail->addAddress($user['email']);
                }

                $mail->send();
                echo "<script>alert('Announcement sent successfully!');</script>";
            } catch (Exception $e) {
                echo "<script>alert('Error sending email: " . $mail->ErrorInfo . "');</script>";
            }
        } else {
            echo "<script>alert('Email configuration not set up!');</script>";
        }
    }
}

// Send Individual Email
if (isset($_POST['send_individual'])) {
    $recipientEmail = $_POST['user_email'];
    $message = trim($_POST['message']);

    if (empty($message)) {
        echo "<script>alert('Message cannot be empty!');</script>";
    } elseif (empty($recipientEmail)) {
        echo "<script>alert('No recipient selected!');</script>";
    } else {
        if (count($emailConfig) > 0) {
            $email = $emailConfig[0]['email'];
            $password = $emailConfig[0]['password'];

            require '../../src/vendor/phpmailer/phpmailer/src/PHPMailer.php';
            require '../../src/vendor/phpmailer/phpmailer/src/SMTP.php';
            require '../../src/vendor/phpmailer/phpmailer/src/Exception.php';

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $email;
                $mail->Password = $password;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom($email, 'Admin');
                $mail->isHTML(true);
                $mail->Subject = 'Message from Admin';
                $mail->Body = $message;

                $mail->addAddress($recipientEmail);

                $mail->send();
                echo "<script>alert('Email sent successfully to $recipientEmail!');</script>";
            } catch (Exception $e) {
                echo "<script>alert('Error sending email: " . $mail->ErrorInfo . "');</script>";
            }
        } else {
            echo "<script>alert('Email configuration not set up!');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - User Management</title>
    <link rel="icon" type="image/png" href="src/img/PrimeStrength.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .sidebar {
            width: 215px;
            height: 100vh;
            background-color: #2C3E50;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: bold;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            margin: 15px 0;
            padding: 12px 15px;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #16A085;
        }

        .sidebar a.active {
            background-color: #1ABC9C;
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 20px;
        }

        .sidebar .logout-btn {
            padding: 10px 15px;
            background-color: #E74C3C;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            width: 100%;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .sidebar .logout-btn:hover {
            background-color: #C0392B;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
            width: 100%;
            background-color: #f4f4f4;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        textarea,
        select {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : ''; ?>">
            <div class="icon"><i class="fa fa-tachometer-alt"></i></div> Dashboard
        </a>
        <a href="manage_users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>">
            <div class="icon"><i class="fa fa-users"></i></div> Manage Users
        </a>
        <a href="logs.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'logs.php' ? 'active' : ''; ?>">
            <div class="icon"><i class="fa fa-list-alt"></i></div> Logs
        </a>
        <form method="POST" action="">
            <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="content">
        <h1>Create Announcements</h1>
        <form method="POST" action="">
            <textarea name="announcement" placeholder="Write your announcement here..." rows="5"></textarea>
            <button type="submit" name="send_announcement">Send Announcement</button>
        </form>

        <h1>Send Individual Email</h1>
        <form method="POST" action="">
            <label for="user_email">Select User:</label>
            <select name="user_email" id="user_email">
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo htmlspecialchars($user['email']); ?>">
                        <?php echo htmlspecialchars($user['username']); ?> (<?php echo htmlspecialchars($user['email']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <textarea name="message" placeholder="Write your message here..." rows="5"></textarea>
            <button type="submit" name="send_individual">Send to User</button>
        </form>
    </div>
</body>

</html>
