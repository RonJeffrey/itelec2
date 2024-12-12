<?php
session_start();
require_once 'authentication/admin-class.php';

$admin = new ADMIN();

// Redirect if not logged in
if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

// Get the admin's email from the session
$admin_email = isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : 'N/A';

// Pagination setup for logs
$items_per_page = 15;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Count total logs
$total_logs_stmt = $admin->runQuery("SELECT COUNT(*) as total FROM logs");
$total_logs_stmt->execute();
$total_logs = $total_logs_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages_logs = ceil($total_logs / $items_per_page);

// Fetch logs for the current page
$stmt = $admin->runQuery("SELECT * FROM logs ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch transaction logs with pagination
$transaction_page = isset($_GET['transaction_page']) ? (int) $_GET['transaction_page'] : 1;
$transaction_offset = ($transaction_page - 1) * $items_per_page;

// Count total transaction logs
$total_transaction_logs_stmt = $admin->runQuery("SELECT COUNT(*) as total FROM transactions");
$total_transaction_logs_stmt->execute();
$total_transaction_logs = $total_transaction_logs_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages_transactions = ceil($total_transaction_logs / $items_per_page);

// Fetch transaction logs for the current page
$transaction_stmt = $admin->runQuery("SELECT * FROM transactions ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$transaction_stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$transaction_stmt->bindValue(':offset', $transaction_offset, PDO::PARAM_INT);
$transaction_stmt->execute();
$transaction_logs = $transaction_stmt->fetchAll(PDO::FETCH_ASSOC);

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../../");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Logs</title>
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
    margin-left: 250px;
    padding: 20px;
    width: calc(100% - 250px);
    background-color: #ffffff;
    min-height: 100vh;
    animation: fadeIn 0.6s ease;
}

h1, h2 {
    color: #333;
    animation: fadeIn 0.8s ease;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    animation: fadeIn 0.8s ease;
}

table, th, td {
    border: 1px solid #ddd;
}

th {
    background-color: #e74c3c;
    color: #fff;
    font-weight: bold;
}

td, th {
    padding: 10px;
    text-align: left;
}

tr:hover {
    background-color: #f8d7da;
}

.pagination {
    margin-top: 20px;
    text-align: center;
}

.pagination a {
    margin: 0 5px;
    text-decoration: none;
    padding: 8px 12px;
    color: #e74c3c;
    border: 1px solid #ddd;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.pagination a.active {
    background-color: #e74c3c;
    color: white;
}

.pagination a:hover {
    background-color: #c0392b;
    color: white;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

input, select {
    margin-bottom: 10px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 100%;
    transition: all 0.3s ease;
}

input:focus, select:focus {
    border-color: #e74c3c;
    box-shadow: 0 0 5px rgba(231, 76, 60, 0.5);
    outline: none;
}

.modal {
    display: none;
    position: fixed;
    z-index: 10;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    animation: fadeIn 0.6s ease;
}

.modal.active {
    display: block;
}

.modal h3 {
    margin: 0 0 20px;
    color: #333;
}


    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php"
            class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : ''; ?>">
            <div class="icon"><i class="fa fa-tachometer-alt"></i></div> Dashboard
        </a>
        <a href="manage_users.php"
            class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>">
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
        <h1>Logs</h1>
        <table>
            <tr>
                <th>Log ID</th>
                <th>User ID</th>
                <th>Activity</th>
                <th>Date</th>
            </tr>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo htmlspecialchars($log['id']); ?></td>
                    <td><?php echo htmlspecialchars($log['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($log['activity']); ?></td>
                    <td><?php echo htmlspecialchars($log['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages_logs; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>

        <h1>Transaction Logs</h1>
        <table>
            <tr>
                <th>Transaction ID</th>
                <th>Payer Name</th>
                <th>Payer Email</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Plan</th>
                <th>Billing Cycle</th>
                <th>Expiration Date</th>
                <th>Date</th>
            </tr>
            <?php foreach ($transaction_logs as $log): ?>
                <tr>
                    <td><?php echo htmlspecialchars($log['transaction_id']); ?></td>
                    <td><?php echo htmlspecialchars($log['payer_name']); ?></td>
                    <td><?php echo htmlspecialchars($log['payer_email']); ?></td>
                    <td><?php echo htmlspecialchars($log['login_email']); ?></td>
                    <td><?php echo htmlspecialchars($log['amount']); ?></td>
                    <td><?php echo htmlspecialchars($log['plan']); ?></td>
                    <td><?php echo htmlspecialchars($log['billing_cycle']); ?></td>
                    <td><?php echo htmlspecialchars($log['expiration_date']); ?></td>
                    <td><?php echo htmlspecialchars($log['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages_transactions; $i++): ?>
                <a href="?transaction_page=<?php echo $i; ?>"
                    class="<?php echo $i == $transaction_page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>

</html>
