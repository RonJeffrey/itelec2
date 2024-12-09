<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();

if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $admin->runQuery("SELECT * FROM transactions WHERE login_email = :email ORDER BY created_at DESC LIMIT 1");
$stmt->execute(array(":email" => $user_data['email']));
$user_plan = $stmt->fetch(PDO::FETCH_ASSOC);
$current_plan = $user_plan ? htmlspecialchars($user_plan['plan']) : 'No Plan';
$current_billing_cycle = $user_plan ? htmlspecialchars($user_plan['billing_cycle']) : '';
$current_plan_display = ($current_plan !== 'No Plan') ? "$current_plan ($current_billing_cycle)" : 'No Plan';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Subscription</title>
    <link rel="icon" type="image/png" href="src/img/PrimeStrength.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
    @import url("https://fonts.googleapis.com/css2?family=Lato&family=Nunito:wght@300&display=swap");

   
    body {
        font-family: 'Nunito', sans-serif;
        margin: 0;
        display: flex;
        height: 100vh;
        overflow: hidden;
        background-color: #1a1a1a;
        color: white;
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

    .main-content {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background-color: #333;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 20px;
        border-radius: 10px;
        background-color: #222;
        color: #fff;
    }

    .header h1 {
        margin: 0;
        font-size: 1.8em; 
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

    .container {
        width: 80%;
        margin: auto;
    }

    @media screen and (max-width: 768px) {
        .container {
            width: 100%;
        }
    }

    .text-center {
        text-align: center;
    }

   
    .plan-selector {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
        font-size: 1.5em; 
    }

    .plan-selector label {
        display: inline-block;
        margin-right: 1.5rem;
    }

    .plan-selector input[type="radio"] {
        margin-right: 5px;
    }

    .plan-info {
        margin-top: 1.5rem;
        font-size: 1.5em; 
    }

    .text-red {
        color: #ef476f;
    }

    .subscription-options {
        margin-top: 1em;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .subscription-options label {
        margin-bottom: 1rem; 
        font-size: 1.3em; 
    }

   
    #billing-cycle {
        padding: 12px; 
        border-radius: 8px;
        font-size: 1.5em; 
        background-color: #444;
        color: white;
        border: none;
        margin-top: 1rem;
        width: 60%; 
    }

   
    #paypal-payment-button {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .paypal-button {
        background-color: #28a745;
        color: white;
        padding: 16px 24px; 
        border: none;
        border-radius: 10px; 
        cursor: pointer;
        font-size: 1.4em; 
        transition: background-color 0.3s ease;
        width: 50%; 
    }

    .paypal-button:hover {
        background-color: #218838; 
    }

    
    .modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
        background-color: #222;
        margin: 15% auto;
        padding: 20px;
        border-radius: 8px;
        width: 80%;
        max-width: 500px;
        text-align: center;
        color: #fff;
    }

    .modal button {
        background-color: #f00;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
    }

    .modal button:hover {
        background-color: #c82333;
    }

    .logo-membership {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }
        .logo-membership img {
            max-width: 150px; 
            max-height: 150px; 
            width: auto;
            height: auto;
            border-radius: 50%; 
        }
</style>



</head>

<body>
    <div class="sidebar">
    <div class="logo-membership">
            <img src="../../src/img/PrimeStrength_BlackWhite.png" alt="Company Logo">
        </div>
        <h2>User Dashboard</h2>
        <a href="user_dashboard.php">Home</a>
        <a href="user_profile.php">Profile</a>
        <a href="plans.php">Membership Plans</a>
        <a href="user_notif.php">Notifications</a>
        <a href="authentication/admin-class.php?admin_signout" class="logout-button">
    <i class="fas fa-sign-out-alt"></i> Sign Out
</a>


    </div>

    <div class="main-content">
        <div class="header">
            <h1>Welcome, <?php echo htmlspecialchars($user_data['email']); ?></h1>
            <h1>Current Plan: [<?php echo $current_plan; ?>]<?php echo $current_billing_cycle; ?></h1>

        </div>
        <main id="cart-main">
            <div class="container">
                <div class="item text-center">
                    <h3>Choose Your Subscription</h3>
                    <div class="plan-selector">
                        <label><input type="radio" name="plan" value="Bronze" onclick="updatePrice('bronze')" checked>
                            Bronze</label>
                        <label><input type="radio" name="plan" value="Silver" onclick="updatePrice('silver')">
                            Silver</label>
                        <label><input type="radio" name="plan" value="Gold" onclick="updatePrice('gold')"> Gold</label>
                    </div>
                    <div class="subscription-options">
                        <label for="billing-cycle">Billing Cycle:</label>
                        <select id="billing-cycle" onchange="updatePrice()">
                            <option value="Daily">Daily</option>
                            <option value="Monthly">Monthly</option>
                            <option value="Annual">Annual</option>
                        </select>
                    </div>
                    <div class="plan-info">
                        <p>Your selected plan: <span id="plan-name">Bronze Plan</span></p>
                        <p>Billing cycle: <span id="billing-cycle-name">Daily</span></p>
                        <p>Price: <span id="plan-price" class="text-red">$1</span></p>
                    </div>
                    <div id="paypal-payment-button"></div>
                </div>
            </div>
        </main>
    </div>

   
    <div id="success-modal" class="modal">
        <div class="modal-content">
            <h3>Success!</h3>
            <p id="success-message"></p>
            <button onclick="closeModal()">Close</button>
        </div>
    </div>

  
    <div id="error-modal" class="modal">
        <div class="modal-content">
            <h3>Error!</h3>
            <p id="error-message"></p>
            <button onclick="closeModal()">Close</button>
        </div>
    </div>

    <script
        src="https://www.paypal.com/sdk/js?client-id=AbK55LvlOsjjx2fGcNmroImu7SCw_S7Z4E8iK73Hsrn9h9X5E-hiYfo3GZPsUD3n0UFiHpZMgxwtsqSb&disable-funding=credit,card"></script>
    <script>
        function capitalizeFirstLetter(string) {
            if (typeof string !== 'string' || string.length === 0) {
                return string;
            }
            return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
        }

        const prices = {
            bronze: { Daily: 1, Monthly: 1.5, Annual: 1.75 },
            silver: { Daily: 2, Monthly: 2.25, Annual: 2.5 },
            gold: { Daily: 3, Monthly: 3.25, Annual: 3.5 }
        };

        let currentPlan = 'bronze';
        let currentBillingCycle = 'daily';
        let currentPrice = prices[currentPlan][currentBillingCycle];

        function updatePrice(plan = currentPlan) {
            currentPlan = plan;
            currentBillingCycle = document.getElementById('billing-cycle').value;
            currentPrice = prices[currentPlan][currentBillingCycle];

            document.getElementById('plan-name').textContent = capitalizeFirstLetter(currentPlan) + ' Plan';
            document.getElementById('billing-cycle-name').textContent = capitalizeFirstLetter(currentBillingCycle);
            document.getElementById('plan-price').textContent = `$${currentPrice}`;

            const paypalButtonContainer = document.getElementById('paypal-payment-button');
            paypalButtonContainer.innerHTML = '';

            paypal.Buttons({
                createOrder: function (data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: { value: currentPrice }
                        }]
                    });
                },
                onApprove: function (data, actions) {
                    return actions.order.capture().then(function (details) {
                        const transactionData = {
                            payer_name: details.payer.name.given_name,
                            payer_email: details.payer.email_address,
                            amount: details.purchase_units[0].amount.value,
                            transaction_id: details.id,
                            plan: currentPlan,
                            billing_cycle: currentBillingCycle
                        };

                        fetch('process_payment.php', {
                            method: 'POST',
                            body: JSON.stringify(transactionData),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    showSuccessPopup('Transaction completed by ' + details.payer.name.given_name);
                                } else {
                                    showErrorPopup('Error logging transaction: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showErrorPopup('An error occurred while logging the transaction');
                            });
                    });
                }
            }).render('#paypal-payment-button');
        }


        function showSuccessPopup(message) {
            document.getElementById('success-message').textContent = message;
            document.getElementById('success-modal').style.display = 'block';
        }

        function showErrorPopup(message) {
            document.getElementById('error-message').textContent = message;
            document.getElementById('error-modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('success-modal').style.display = 'none';
            document.getElementById('error-modal').style.display = 'none';
        }

        updatePrice();
    </script>
</body>

</html>