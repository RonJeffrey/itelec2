<?php
require_once __DIR__ . '/../../database/dbconnection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents('php://input'), true);

header('Content-Type: application/json');

if (isset($input['payer_name'], $input['payer_email'], $input['amount'], $input['transaction_id'], $input['plan'], $input['billing_cycle'])) {
    $payer_name = $input['payer_name'];
    $payer_email = $input['payer_email'];
    $amount = $input['amount'];
    $transaction_id = $input['transaction_id'];
    $plan = $input['plan'];
    $billing_cycle = $input['billing_cycle'];

    $query = "INSERT INTO transactions (payer_name, payer_email, amount, transaction_id, plan, billing_cycle) 
              VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ssdsis", $payer_name, $payer_email, $amount, $transaction_id, $plan, $billing_cycle);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Transaction logged successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to log transaction: ' . $stmt->error]);
    }

    $stmt->close();
    $db->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required data']);
}
?>
