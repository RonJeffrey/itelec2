<?php
$host = 'localhost';
$db = 'itelec2';
$user = 'root';
$pass = '';

require_once 'authentication/admin-class.php';
$admin = new ADMIN();

header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$stmt = $admin->runQuery("SELECT email FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user_data) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid session or user']);
    exit;
}

$login_email = $user_data['email'];

$input = file_get_contents('php://input');
$data = json_decode($input, true);

error_log("Raw POST Data: " . print_r($data, true));

if (isset($data['payer_name'], $data['payer_email'], $data['amount'], $data['transaction_id'], $data['plan'], $data['billing_cycle'])) {
    $payer_name = htmlspecialchars(trim($data['payer_name']));
    $payer_email = htmlspecialchars(trim($data['payer_email']));
    $amount = floatval($data['amount']);
    $transaction_id = htmlspecialchars(trim($data['transaction_id']));
    $plan = htmlspecialchars(trim($data['plan']));
    $billing_cycle = htmlspecialchars(trim($data['billing_cycle']));

    error_log("Transaction received: $payer_name, $payer_email, $amount, $transaction_id, $plan, $billing_cycle, $login_email");

    try {
        $pdo->beginTransaction();

        $expiration_date = date('Y-m-d', strtotime("+1 month"));

        if ($billing_cycle == 'Monthly') {
            $expiration_date = date('Y-m-d', strtotime("+1 month"));
        } elseif ($billing_cycle == 'Annual') {
            $expiration_date = date('Y-m-d', strtotime("+1 year"));
        }

        $stmt = $pdo->prepare("INSERT INTO transactions (login_email, payer_name, payer_email, amount, transaction_id, plan, billing_cycle, expiration_date) 
                              VALUES (:login_email, :payer_name, :payer_email, :amount, :transaction_id, :plan, :billing_cycle, :expiration_date)");

        $stmt->bindParam(':login_email', $login_email);
        $stmt->bindParam(':payer_name', $payer_name);
        $stmt->bindParam(':payer_email', $payer_email);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':transaction_id', $transaction_id);
        $stmt->bindParam(':plan', $plan);
        $stmt->bindParam(':billing_cycle', $billing_cycle);
        $stmt->bindParam(':expiration_date', $expiration_date);

        $stmt->execute();

        $pdo->commit();

        echo json_encode(['status' => 'success', 'message' => 'Transaction logged successfully']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Transaction failed: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Failed to log transaction']);
    }
} else {
    error_log("Missing required data: " . print_r($data, true));
    echo json_encode(['status' => 'error', 'message' => 'Missing required data']);
}
?>
