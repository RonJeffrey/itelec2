<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="src/img/PrimeStrength.png">
    <title>Bronze Membership Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 400px;
            width: 100%;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
        }
        .membership-option {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .membership-option:last-child {
            border-bottom: none;
        }
        .membership-option h3 {
            font-size: 1.2em;
            color: #555;
            margin: 0;
        }
        .price {
            font-size: 1.5em;
            color: #333;
            margin: 5px 0;
        }
        .purchase-btn {
            background-color:  #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 25px;
            display: inline-block;
            font-size: 1em;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .purchase-btn:hover {
            background-color: #45a049;
        }
        .description {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bronze Membership Details</h1>
        
        <div class="membership-option">
            <h3>Per Entry</h3>
            <p class="price">₱100</p>
            <p class="description">Valid for a single entry</p>
            <a href="purchase.php?plan=bronze&option=entry" class="purchase-btn">Purchase</a>
        </div>

        <div class="membership-option">
            <h3>Per Month</h3>
            <p class="price">₱1500</p>
            <p class="description">Valid for 1 month</p>
            <a href="purchase.php?plan=bronze&option=month" class="purchase-btn">Purchase</a>
        </div>

        <div class="membership-option">
            <h3>Per Year</h3>
            <p class="price">₱15000</p>
            <p class="description">Valid for 1 year</p>
            <a href="purchase.php?plan=bronze&option=year" class="purchase-btn">Purchase</a>
        </div>
    </div>
</body>
</html>
