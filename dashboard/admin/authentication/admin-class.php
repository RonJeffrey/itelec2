<?php
require_once __DIR__ . '/../../../database/dbconnection.php';
include_once __DIR__ . '/../../../config/settings-configuration.php';
require_once __DIR__ . '/../../../src/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ADMIN
{
    private $conn;
    private $settings;
    private $smtp_email;
    private $smtp_password;

    public function __construct()
    {
        $this->settings = new SystemConfig();
        $this->smtp_email = $this->settings->getSmtpEmail();
        $this->smtp_password = $this->settings->getSmtpPassword();

        $database = new Database();
        $this->conn = $database->dbConnection();

    }
    
    private function generateEmailTemplate($email, $otp)
    {
        return "<html><body style='font-family: Arial, sans-serif; color: #333;'>
                <div style='text-align: center; padding: 20px; border: 1px solid #ddd; background-color: #fff;'>
                    <h1 style='color: maroon;'>OTP Verification</h1>
                    <p style='color: black;'>Hello, <strong>$email</strong></p>
                    <p style='font-size: 18px;'><strong>Your OTP is: <span style='color: red;'>$otp</span></strong></p>
                    <p style='color: gray;'>Please do not share it with anyone.</p>
                </div>
            </body></html>";
    }

    private function generateWelcomeTemplate($email)
    {
        return "<html><body style='font-family: Arial, sans-serif; color: #333;'>
                <div style='text-align: center; padding: 20px; border: 1px solid #ddd; background-color: #fff;'>
                    <h1 style='color: maroon;'>Welcome</h1>
                    <p style='color: black;'>Hello, <strong>$email</strong></p>
                    <p style='font-size: 18px;'>Thank you for joining <span style='color: maroon;'>PrimeStrength</span>!</p>
                </div>
            </body></html>";
    }
    
    public function sendOtp($otp, $email)
    {
        if ($email == NULL) {
            echo "<script>alert('No email found.'); window.location.href = '../../../';</script>";
            exit;
        } else {
            $stmt = $this->runQuery("SELECT * FROM user WHERE email = :email");
            $stmt->execute(array(":email" => $email));
            $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() > 0) {
                echo "<script>alert('Email Already Taken. Please try another one'); window.location.href = '../../../';</script>";
                exit;
            } else {
                $_SESSION['OTP'] = $otp;

                $subject = "OTP VERIFICATION"; 
                $message = $this->generateEmailTemplate($email, $otp);

                $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);
                echo "<script>alert('We sent the OTP to $email'); window.location.href = '../../../verify-otp.php';</script>";

            }
        }
    }

    public function verifyOTP($username, $otp, $password, $tokencode, $email, $csrf_token)
    {
        if ($otp == $_SESSION['OTP']) {
            unset($_SESSION['OTP']);

            $this->addAdmin($csrf_token, $username, $email, $password);

            $subject = "VERIFICATION SUCCESS";
            $message = $this->generateWelcomeTemplate($email);

            $this->send_email($email, $message, $subject);

            echo "<script>alert('Thank you!'); window.location.href = '../../../';</script>";

            unset($_SESSION['not_verify_username']);
            unset($_SESSION['not_verify_email']);
            unset($_SESSION['not_verify_password']);
        } else if ($otp == NULL) {
            echo "<script>alert('No OTP Found'); window.location.href = '../../../verify-otp.php';</script>";
            exit;
        } else {
            echo "<script>alert('It appears that the OTP you entered is invalid'); window.location.href = '../../../verify-otp.php';</script>";
            exit;
        }
    }

    public function addAdmin($csrf_token, $username, $email, $password)
    {
        $stmt = $this->runQuery("SELECT * FROM user WHERE email = :email");
        $stmt->execute(array(":email" => $email));

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Email already exists.'); window.location.href = '../../../';</script>";
            exit;
        }

        if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            echo "<script>alert('Invalid CSRF Token.'); window.location.href = '../../../';</script>";
            exit;
        }

        unset($_SESSION['csrf_token']);

        $hash_password = md5(string: $password);

        $stmt = $this->runQuery('INSERT INTO user (username, email, password) VALUES (:username, :email, :password)');
        $exec = $stmt->execute(array
        (
            ":username" => $username,
            ":email" => $email,
            ":password" => $hash_password
        ));

        if ($exec) {
            echo "<script>alert('User Added Successfully'); window.location.href = '../../../';</script>";
            exit;
        } else {
            echo "<script>alert('Error Adding User.'); window.location.href = '../../../';</script>";
            exit;
        }
    }
    public function signin($email, $password, $csrf_token)
    {
        try {
            if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
                echo "<script>alert('Invalid CSRF Token.'); window.location.href = '../../../';</script>";
                exit;
            }

            unset($_SESSION['csrf_token']);

            $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = :email AND status = :status");
            $stmt->execute(array(":email" => $email, ":status" => "active"));

            if ($stmt->rowCount() == 1) {
                $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($userRow['password'] == md5($password)) {
                    $activity = "Has Successfully signed in.";
                    $user_id = $userRow['id'];
                    $this->logs($activity, $user_id);

                    $_SESSION['adminSession'] = $user_id;

                    if ($userRow['Type'] == "admin") {
                        echo "<script>alert('Welcome admin'); window.location.href = '../admin_dashboard.php';</script>";
                    } elseif ($userRow['Type'] == "user") {
                        echo "<script>alert('Welcome user'); window.location.href = '../user_dashboard.php';</script>";
                    } else {
                        echo "<script>alert('Invalid user type.'); window.location.href = '../../../';</script>";
                    }
                    exit;
                } else {
                    echo "<script>alert('Password is incorrect.'); window.location.href = '../../../login.php';</script>";
                    exit;
                }
            } else {
                echo "<script>alert('No account found for this email.'); window.location.href = '../../../';</script>";
                exit;
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }


    public function adminSignout()
    {
        unset($_SESSION['adminSession']);
        echo "<script>alert('Signed out successfully.'); window.location.href = '../../../';</script>";
        exit;
    }

    private function send_email($email, $message, $subject)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->isHTML(true);
        $mail->addAddress($email);
        $mail->Username = $this->smtp_email;
        $mail->Password = $this->smtp_password;
        $mail->setFrom($this->smtp_email, "PrimeStrength Support");
        $mail->Subject = $subject;
        $mail->Body = $message;

        return $mail->send();
    }

    public function logs($activity, $user_id)
    {
        $stmt = $this->runQuery("INSERT INTO logs (user_id, activity) VALUES (:user_id, :activity)");
        $stmt->execute(array(":user_id" => $user_id, ":activity" => $activity));
    }

    public function isUserLoggedIn()
    {
        if (isset($_SESSION['adminSession'])) {
            return true;
        }
        return false;
    }


    public function redirect()
    {
        if (!isset($_SESSION['adminSession'])) {
            echo "<script>alert('Admin must log in first'); window.location.href = '../../../';</script>";
            exit;
        }
    }


    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

}

if (isset($_POST['btn-signup'])) {
    $_SESSION['not_verify_csrf_token'] = trim($_POST['csrf_token']);
    $_SESSION['not_verify_username'] = trim($_POST['username']);
    $_SESSION['not_verify_email'] = trim($_POST['email']);
    $_SESSION['not_verify_password'] = trim($_POST['password']);

    $email = trim($_POST['email']);
    $otp = rand(100000, 999999);
    $addAdmin = new ADMIN();
    $addAdmin->sendOtp($otp, $email);
}

if (isset($_POST['btn-verify'])) {
    $csrf_token = trim(string: $_POST['csrf_token']);
    $username = $_SESSION['not_verify_username'];
    $email = $_SESSION['not_verify_email'];
    $password = $_SESSION['not_verify_password'];

    $tokencode = md5(uniqid(rand()));
    $otp = trim($_POST['otp']);

    $adminVerify = new ADMIN();
    $adminVerify->verifyOTP($username, $otp, $password, $tokencode, $email, $csrf_token);
}

if (isset($_POST['btn-signin'])) {
    $csrf_token = trim($_POST['csrf_token']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $admin = new ADMIN();
    $admin->signin($email, $password, $csrf_token);
}

if (isset($_GET['admin_signout'])) {
    $adminSignout = new ADMIN();
    $adminSignout->adminSignout();
}
?>