<?php
session_start();
include 'config.php';

$product_id = $_POST['product_id'];
$product_total = $_POST['product_total'];
$product_qty = $_POST['product_qty'];

if (empty($product_total) || empty($product_id)) {
    die("Invalid request.");
}

// Generate Order ID
$order_id = uniqid("cod_");
$_SESSION['TID'] = $order_id;
// Store COD Order in Database
$db = new Database();
$db->insert('payments', [
    'item_number' => $product_id,
    'txn_id' => $order_id,
    'payment_gross' => $product_total,
    'payment_status' => 'COD'
]);
$db->insert('order_products', [
    'product_id' => $product_id,
    'product_qty' => $product_qty,
    'total_amount' => $product_total,
    'product_user' => $_SESSION['user_id'],
    'order_date' => date('Y-m-d'),
    'pay_req_id' => $order_id
]);
$db->getResult();

// Redirect to Confirmation Page
header("Location: success_cod.php?payment_request_id=" . $order_id . "&payment_status=Credit");
exit();
?>
