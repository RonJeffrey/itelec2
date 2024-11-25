<?php
require_once 'authentication/admin-class.php';
$admin = new ADMIN();

$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Paypal Payment</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Lato&family=Nunito:wght@300&display=swap");

        body {
            font-family: 'Nunito', sans-serif;
            position: relative;
            z-index: 1;
        }

        *>* {
            margin: 0%;
            padding: 0%;
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

        .text-white {
            color: white;
        }

        .text-gray {
            color: #e9ecef;
        }

        .font-title {
            font-family: 'Lato', sans-serif;
        }

        .item {
            border-top: 1px solid #e9ecef;
            border-bottom: 1px solid #e9ecef;
            padding: 1.5em 3em;
            margin: 1em 0;
        }

        .price {
            color: #ef476f;
            font-size: 1.5em;
            margin-top: 1em;
        }

        .flex {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .plan-selector {
            display: flex;
            justify-content: space-around;
            margin-top: 2rem;
        }

        .plan-selector label {
            display: inline-block;
            margin-right: 1rem;
        }

        .plan-selector input[type="radio"] {
            margin-right: 5px;
        }

        .plan-info {
            margin-top: 1.5rem;
            font-size: 1.2em;
        }

        .text-red {
            color: #ef476f;
        }

        .subscription-options {
            margin-top: 1em;
            display: flex;
            flex-direction: column;
        }

        .subscription-options label {
            margin-bottom: 0.5rem;
        }

        #paypal-payment-button {
            margin-top: 2rem;
            z-index: 1;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 500px;
            text-align: center;
        }

        .modal button {
            background-color: #ef476f;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .modal button:hover {
            background-color: #d13a58;
        }
    </style>
</head>

<body>
    <main id="cart-main">
        <div class="site-title text-center">
            <h1>Welcome, <?php echo htmlspecialchars($user_data['email']); ?></h1>
            <h1 class="font-title">Choose Your Subscription</h1>
        </div>

        <div class="container">
            <div class="item">
                <div class="text-center">
                    <h3>Subscription Plans</h3>

                    <div class="plan-selector">
                        <label>
                            <input type="radio" name="plan" value="bronze" onclick="updatePrice('bronze')" checked>
                            Bronze Plan
                        </label>
                        <label>
                            <input type="radio" name="plan" value="silver" onclick="updatePrice('silver')">
                            Silver Plan
                        </label>
                        <label>
                            <input type="radio" name="plan" value="gold" onclick="updatePrice('gold')">
                            Gold Plan
                        </label>
                    </div>

                    <div class="subscription-options">
                        <label for="billing-cycle">Choose Billing Cycle:</label>
                        <select id="billing-cycle" onchange="updatePrice()">
                            <option value="daily">Daily</option>
                            <option value="monthly">Monthly</option>
                            <option value="annual">Annual</option>
                        </select>
                    </div>

                    <div class="plan-info">
                        <p>Your selected plan: <span id="plan-name">Bronze Plan</span></p>
                        <p>Your selected billing cycle: <span id="billing-cycle-name">Daily</span></p>
                        <p>Price: <span id="plan-price" class="text-red">$1</span></p>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <div id="paypal-payment-button"></div>
            </div>
        </div>
    </main>

    <div id="success-modal" class="modal">
        <div class="modal-content">
            <h3>Transaction Successful</h3>
            <p id="success-message"></p>
            <button onclick="closeModal()">Close</button>
        </div>
    </div>

    <script
        src="https://www.paypal.com/sdk/js?client-id=AbK55LvlOsjjx2fGcNmroImu7SCw_S7Z4E8iK73Hsrn9h9X5E-hiYfo3GZPsUD3n0UFiHpZMgxwtsqSb&disable-funding=credit,card"></script>
        <script>

    const prices = {
        bronze: { daily: 1, monthly: 1.5, annual: 1.75 },
        silver: { daily: 2, monthly: 2.25, annual: 2.5 },
        gold: { daily: 3, monthly: 3.25, annual: 3.5 }
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

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function showSuccessPopup(message) {
        document.getElementById('success-message').textContent = message;
        const modal = document.getElementById('success-modal');
        modal.style.display = 'block';
    }

    function closeModal() {
        const modal = document.getElementById('success-modal');
        modal.style.display = 'none';
    }

    updatePrice();
</script>

</body>

</html>