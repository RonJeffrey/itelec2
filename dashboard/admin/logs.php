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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            padding: 8px 12px;
            color: #007bff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
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
