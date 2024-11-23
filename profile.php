<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="src/img/PrimeStrength.png">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid #ccc;
        }
        .profile-header h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-group label {
            flex: 1;
        }
        .form-group .value {
            flex: 2;
        }
        .form-group .edit-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .form-group .edit-btn:hover {
            background-color: #0056b3;
        }
        .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        #edit-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
        #edit-modal .modal-content {
            background: #fff;
            padding: 20px;
            margin: 100px auto;
            width: 400px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($user_data['profile_picture'] ?? 'default-profile.png'); ?>" alt="Profile Picture">
            <h2><?php echo htmlspecialchars($user_data['name'] ?? 'Your Name'); ?></h2>
        </div>

        <h2>Edit Profile</h2>
        <?php 
        $fields = [
            'name' => 'Name',
            'age' => 'Age',
            'weight' => 'Weight (kg)',
            'height' => 'Height (cm)',
            'bmi' => 'BMI',
            'birthday' => 'Birthday',
            'address' => 'Address',
            'contact' => 'Contact Number',
            'email' => 'Email',
            'profile_picture' => 'Profile Picture'
        ];

        foreach ($fields as $field => $label): ?>
            <div class="form-group">
                <label><?php echo $label; ?>:</label>
                <div class="value">
                    <?php if ($field === 'profile_picture' && !empty($user_data[$field])): ?>
                        <img src="<?php echo htmlspecialchars($user_data[$field]); ?>" alt="Profile Picture" style="max-width: 100px;">
                    <?php else: ?>
                        <?php echo htmlspecialchars($user_data[$field] ?? ''); ?>
                    <?php endif; ?>
                </div>
                <button class="edit-btn" onclick="editField('<?php echo $field; ?>', '<?php echo $label; ?>')">Edit</button>
            </div>
        <?php endforeach; ?>
    </div>

   
    <div id="edit-modal">
        <div class="modal-content">
            <h3 id="modal-title"></h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="field" id="field-name">
                <div class="form-group">
                    <label for="value-input">Update your information:</label>
                    <input type="text" name="value" id="value-input" required>
                </div>
                <div class="form-group" id="bmi-fields" style="display: none;">
                    <label for="weight-input">Weight (kg):</label>
                    <input type="number" id="weight-input" placeholder="Enter your weight" onchange="calculateBMI()">
                    <label for="height-input">Height (cm):</label>
                    <input type="number" id="height-input" placeholder="Enter your height" onchange="calculateBMI()">
                    <p><strong>BMI: </strong><span id="bmi-result">0</span></p>
                </div>
                <div class="form-group">
                    <input type="file" name="value" id="file-input" style="display: none;">
                </div>
                <button type="submit" class="btn">Save</button>
                <button type="button" class="btn" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function editField(field, label) {
            const modal = document.getElementById('edit-modal');
            const modalTitle = document.getElementById('modal-title');
            const fieldName = document.getElementById('field-name');
            const valueInput = document.getElementById('value-input');
            const fileInput = document.getElementById('file-input');
            const bmiFields = document.getElementById('bmi-fields');

            modal.style.display = 'block';
            modalTitle.textContent = `Edit ${label}`;
            fieldName.value = field;

            if (field === 'weight' || field === 'height') {
                valueInput.style.display = 'none';
                fileInput.style.display = 'none';
                bmiFields.style.display = 'block';
            } else if (field === 'profile_picture') {
                valueInput.style.display = 'none';
                fileInput.style.display = 'block';
                bmiFields.style.display = 'none';
            } else {
                valueInput.style.display = 'block';
                fileInput.style.display = 'none';
                bmiFields.style.display = 'none';
            }
        }

        function calculateBMI() {
            const weight = parseFloat(document.getElementById('weight-input').value);
            const height = parseFloat(document.getElementById('height-input').value) / 100;

            if (weight && height) {
                const bmi = (weight / (height * height)).toFixed(2);
                document.getElementById('bmi-result').textContent = bmi;
            } else {
                document.getElementById('bmi-result').textContent = "0";
            }
        }

        function closeModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }
    </script>
</body>
</html>
