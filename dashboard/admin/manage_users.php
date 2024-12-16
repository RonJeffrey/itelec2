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

$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

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

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../../");
    exit();
}

function announcementTemplate($announcement)
{
    return "<html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }
            .container {
                width: 100%;
                max-width: 600px;
                margin: 20px auto;
                background-color: #ffffff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .header h1 {
                color: #e74c3c;
                font-size: 28px;
                margin-bottom: 10px;
            }
            .content {
                font-size: 16px;
                line-height: 1.5;
                color: #333;
            }
            .announcement {
                font-size: 18px;
                color: #e74c3c;
                font-weight: bold;
                margin: 20px 0;
                padding: 15px;
                border: 1px solid #e74c3c;
                border-radius: 5px;
                background-color: #f9f9f9;
            }
            .footer {
                text-align: center;
                font-size: 12px;
                color: #888;
                margin-top: 30px;
            }
            .footer p {
                margin: 5px 0;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>GYM ANNOUNCEMENT</h1>
            </div>
            <div class='content'>
                <p>We have an important update for you:</p>
                <div class='announcement'>
                    <p><span style='color: red;'>$announcement</span></p>
                </div>
                <p>We hope this information helps, and we appreciate your continued participation in our gym community!</p>
            </div>
            <div class='footer'>
                <p>If you have any questions or need assistance, feel free to contact us.</p>
                <p>Thank you for being part of our community!</p>
            </div>
        </div>
    </body>
    </html>";
}
function messageTemplate($message)
{
    return "<html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }
            .container {
                width: 100%;
                max-width: 600px;
                margin: 20px auto;
                background-color: #ffffff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .header h1 {
                color: #e74c3c;
                font-size: 28px;
                margin-bottom: 10px;
            }
            .content {
                font-size: 16px;
                line-height: 1.5;
                color: #333;
            }
            .notice {
                font-size: 18px;
                color: #e74c3c;
                font-weight: bold;
                margin: 20px 0;
                padding: 15px;
                border: 1px solid #e74c3c;
                border-radius: 5px;
                background-color: #f9f9f9;
            }
            .footer {
                text-align: center;
                font-size: 12px;
                color: #888;
                margin-top: 30px;
            }
            .footer p {
                margin: 5px 0;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>NOTICE!</h1>
            </div>
            <div class='content'>
                <p>We have an important update for you:</p>
                <div class='notice'>
                    <p><span style='color: red;'>$message</span></p>
                </div>
                <p>We hope this information helps, and we appreciate your continued participation in our gym community!</p>
            </div>
            <div class='footer'>
                <p>If you have any questions or need assistance, feel free to contact us.</p>
                <p>Thank you for being part of our community!</p>
            </div>
        </div>
    </body>
    </html>";
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
                // Send Email to All Users
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
                $mail->Body = announcementTemplate($announcement);

                foreach ($users as $user) {
                    $mail->addAddress($user['email']);
                }

                $mail->send();

                // Insert the announcement into the notifications table
                $stmt = $admin->runQuery("INSERT INTO notifications (message) VALUES (:message)");
                $stmt->bindParam(':message', $announcement);
                $stmt->execute();

                echo "<script>alert('Announcement sent and posted in notifications successfully!');</script>";
            } catch (Exception $e) {
                echo "<script>alert('Error sending email: " . $mail->ErrorInfo . "');</script>";
            }
        } else {
            echo "<script>alert('Email configuration not set up!');</script>";
        }
    }
    $activity = 'Created a new Announcement';
    $date = date('Y-m-d H:i:s');
    $userId = $_SESSION['adminSession'];

    $stmt = $admin->runQuery("INSERT INTO logs (user_id, activity, created_at) VALUES (:user_id, :activity, :created_at)");
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':activity', $activity);
    $stmt->bindParam(':created_at', $date);
    $stmt->execute();
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
                $mail->Body = messageTemplate($message);

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
    
    $activity = 'Created a notice to:' . $recipientEmail;
    $date = date('Y-m-d H:i:s');
    $userId = $_SESSION['adminSession'];

    $stmt = $admin->runQuery("INSERT INTO logs (user_id, activity, created_at) VALUES (:user_id, :activity, :created_at)");
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':activity', $activity);
    $stmt->bindParam(':created_at', $date);
    $stmt->execute();
}

// Remove Announcement
if (isset($_POST['remove_announcement'])) {
    $announcementId = $_POST['announcement_id'];

    if (empty($announcementId)) {
        echo "<script>alert('Announcement ID cannot be empty!');</script>";
    } else {
        // Delete the announcement from the notifications table
        $stmt = $admin->runQuery("DELETE FROM notifications WHERE id = :id");
        $stmt->bindParam(':id', $announcementId);
        $stmt->execute();

        // Log the removal action in the logs table
        $activity = 'Removed announcement ID: ' . $announcementId;
        $date = date('Y-m-d H:i:s');  // Get the current timestamp

        $userId = $_SESSION['adminSession'];

        // Insert into logs table
        $stmt = $admin->runQuery("INSERT INTO logs (user_id, activity, created_at) VALUES (:user_id, :activity, :created_at)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':activity', $activity);
        $stmt->bindParam(':created_at', $date);
        $stmt->execute();

        echo "<script>alert('Announcement removed and logged successfully!');</script>";
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
            background-color: #f4f4f4;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .sidebar {
            width: 215px;
            height: 100vh;
            background-color: #2c2c2c;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            box-shadow: 3px 0px 8px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            animation: fadeIn 0.6s ease;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: bold;
            color: #e74c3c;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            margin: 15px 0;
            padding: 12px 15px;
            border-radius: 5px;
            font-size: 18px;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar a:hover {
            background-color: #e74c3c;
            transform: translateX(5px);
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .sidebar a.active {
            background-color: #e74c3c;
        }

        .sidebar .logout-btn {
            padding: 10px 15px;
            background-color: #c0392b;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            width: 100%;
            font-size: 16px;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .sidebar .logout-btn:hover {
            background-color: #a93226;
            transform: scale(1.05);
        }

        .content {
            margin-left: 215px;
            padding: 20px;
            width: 100%;
            min-height: 100vh;
            background-color: #fff;

            transition: margin-left 0.3s ease;
        }

        h1 {
            text-align: center;
            color: #333;

            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        textarea,
        select {
            margin-bottom: 10px;
            padding: 12px;
            border: 1px solid #ddd;

            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            max-width: 500px;
            background-color: #fff;

        }

        textarea:focus,
        select:focus {
            border-color: #e74c3c;

            outline: none;
        }

        button {
            padding: 12px 20px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            max-width: 500px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #c0392b;
            transform: scale(1.05);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);

        }

        select {
            padding: 12px;
            font-size: 16px;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        textarea {
            font-size: 16px;
            resize: vertical;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        input[type="submit"]:hover {
            background-color: #e74c3c;

            cursor: pointer;
        }

        select:focus,
        textarea:focus {
            border-color: #333;

            outline: none;
        }

        input[type="submit"] {
            font-size: 18px;
            padding: 12px;
            background-color:#e74c3c;

            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #e74c3c;

        }

        textarea {
            padding: 12px;
        }

        input[type="submit"]:hover {
            background-color: #e74c3c;
        }

        textarea {
            padding: 12px;
        }

        select,
        textarea {
            max-width: 600px;
            width: 100%;
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
            <select name="user_email">
                <option value="">Select a user</option>
                <?php foreach ($users as $user) { ?>
                    <option value="<?php echo $user['email']; ?>"><?php echo $user['email']; ?></option>
                <?php } ?>
            </select>
            <textarea name="message" placeholder="Write your message here..." rows="5"></textarea>
            <button type="submit" name="send_individual">Send Email</button>
        </form>
        <h1>Delete Announcement</h1>
<form action="">
    <table border="1" 
    style="width: 70%; 
    border-collapse: collapse; 
    margin-bottom: 20px;
    animation: fadeIn 0.8s ease">
        <thead>
            <tr style="background-color: #e74c3c; 
            color: #fff; 
            font-weight: bold; ">
                <th>ID</th>
                <th>Message</th>
                <th>Timestamp</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $admin->runQuery("SELECT id, message, created_at FROM notifications ORDER BY created_at DESC");
            $stmt->execute();
            $notif = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($notif as $notification) {
                $id = htmlspecialchars($notification['id']);
                $message = htmlspecialchars($notification['message']);
                $created_at = htmlspecialchars($notification['created_at']);
                
                echo "<tr>
                        <td>$id</td>
                        <td>$message</td>
                        <td>$created_at</td>
                        <td>
                            <form method='POST' action=''>
                                <input type='hidden' name='announcement_id' value='$id'>
                                <button type='submit' name='remove_announcement'>Remove</button>
                            </form>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</form>
</form>
</div>

<?php
if (isset($_POST['remove_announcement'])) {
    $log_id = $_POST['announcement_id'];
    $stmt = $admin->runQuery("DELETE FROM logs WHERE id = :id");
    $stmt->bindParam(':id', $log_id);
    $stmt->execute();
}
?>
</body>
</html>
