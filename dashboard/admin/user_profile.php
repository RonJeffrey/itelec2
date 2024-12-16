<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();

if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated_name = $_POST['name'];
    $updated_email = $_POST['email'];
    $updated_age = $_POST['age'];
    $updated_weight = $_POST['weight'];
    $updated_height = $_POST['height'];
    $updated_birthdate = $_POST['birthdate'];
    $updated_address = $_POST['address'];
    $updated_contact_number = $_POST['contact_number'];

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $updated_birthdate)) {
        die("Invalid birthdate format. Please use YYYY-MM-DD.");
    }

    // Calculate BMI
    if (is_numeric($updated_weight) && is_numeric($updated_height) && $updated_height > 0) {
        $height_in_meters = $updated_height / 100;
        $bmi = $updated_weight / ($height_in_meters * $height_in_meters);
        $bmi = round($bmi, 1);
    } else {
        $bmi = null;
    }

    try {
        $stmt = $admin->runQuery("UPDATE user SET 
            username = :username, 
            email = :email, 
            age = :age, 
            weight = :weight, 
            height = :height, 
            bmi = :bmi, 
            birthdate = :birthdate, 
            address = :address, 
            contact_number = :contact_number 
            WHERE id = :id");
        $stmt->execute(array(
            ":username" => $updated_name,
            ":email" => $updated_email,
            ":age" => $updated_age,
            ":weight" => $updated_weight,
            ":height" => $updated_height,
            ":bmi" => $bmi,
            ":birthdate" => $updated_birthdate,
            ":address" => $updated_address,
            ":contact_number" => $updated_contact_number,
            ":id" => $_SESSION['adminSession']
        ));
        $_SESSION['profile_update_success'] = true;
    } catch (Exception $e) {
        die("Error updating profile: " . $e->getMessage());
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Settings</title>
    <link rel="icon" type="image/png" href="src/img/PrimeStrength.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            background-color: #f4f4f4;
            height: 100vh;
            overflow: hidden;
            background-color: #610000;

        }

        .sidebar {
            width: 250px;
            background-color: rgba(0, 0, 0, 0.8);
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);

        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.5em;
        }

        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 1em;
            margin: 15px 0;
            padding: 10px;
            border-radius: 5px;
            display: block;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }


        .logout-button {
            background-color: #dc3545;
            border: none;
            border-radius: 5px;
            color: #fff;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 0.9em;
            text-align: center;
            text-decoration: none;
        }

        .logout-button:hover {
            background-color: #c82333;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            overflow-y: auto;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.1), rgba(0, 0, 0, 0.5));
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 2em;
            color: #f4f4f4;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
            }

            50% {
                text-shadow: 2px 2px 8px rgba(255, 255, 255, 0.8);
            }
        }

        .content {
            background: rgba(255, 255, 255, 0.8);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1s ease-in-out;
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

        .content h2 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #610000;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);F
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 8px;
            color: #610000;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        .form-group input {
            width: 98%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            background-color: rgba(255, 255, 255, 0.9);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-group input:focus {
            border-color: #e74c3c;
            box-shadow: 0 0 10px rgba(231, 76, 60, 0.8);
            outline: none;
            transform: scale(1.03);
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            margin: 15px 0;
            background: linear-gradient(90deg, #e74c3c, #ff7675);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 30px;
            transition: all 0.4s ease-in-out;
            cursor: pointer;
            border: none;
            font-size: 1.1em;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .btn:hover {
            background: linear-gradient(90deg, #c0392b, #d63031);
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.5s ease-in-out;
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 15px;
            width: 80%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            animation: zoomIn 0.5s ease-in-out;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.5);
            }

            to {
                transform: scale(1);
            }
        }

        .modal button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .modal button:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        .logo-membership img {
            max-width: 150px;
            max-height: 150px;
            width: auto;
            height: auto;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo-membership">
            <img src="../../src/img/PrimeStrength_BlackWhite.png" alt="Company Logo" onerror="alert('Image not found or path is incorrect')">
        </div>
        <h2>User Dashboard</h2>
        <a href="user_dashboard.php">Home</a>
        <a href="user_profile.php">Profile</a>
        <a href="plans.php">Membership Plans</a>
        <a href="user_notif.php">Notifications</a>
        <a href="authentication/admin-class.php?admin_signout" class="logout-button">Sign Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Profile Settings</h1>
        </div>
        <div class="content">
            <h2>Update Your Profile</h2>
            <form id="profileForm" method="POST" onsubmit="showModal(event)">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user_data['username'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" required>
                </div>

                <h4>Others</h4>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="text" name="age" id="age" value="<?php echo htmlspecialchars($user_data['age'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="weight">Weight (kg)</label>
                    <input type="number" name="weight" id="weight" value="<?php echo htmlspecialchars($user_data['weight'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="height">Height (cm)</label>
                    <input type="number" name="height" id="height" value="<?php echo htmlspecialchars($user_data['height'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="bmi">BMI</label>
                    <?php echo htmlspecialchars($user_data['bmi'] ?? ''); ?>
                </div>
                <div class="form-group">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" name="birthdate" id="birthdate" value="<?php echo htmlspecialchars($user_data['birthdate'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($user_data['address'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" name="contact_number" id="contact_number" value="<?php echo htmlspecialchars($user_data['contact_number'] ?? ''); ?>">
                </div>

                <button type="submit" class="btn">Update Profile</button>
            </form>
        </div>
    </div>

    <div class="modal" id="successModal" style="display: none;">
        <div class="modal-content">
            <h2>Profile updated successfully!</h2>
            <button onclick="closeModal()">OK</button>
        </div>
    </div>

    <script>
        function showModal(event) {
            event.preventDefault();
            document.getElementById('successModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
            document.getElementById('profileForm').submit();
        }
    </script>
</body>

</html>
