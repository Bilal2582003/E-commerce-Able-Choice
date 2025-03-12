<?php
session_start();
include 'config.php'; // Ensure database connection

// XPay Test Credentials (Replace with actual test credentials)
define("XPAY_API_KEY", "1234567890"); 
define("XPAY_SECRET_KEY", "abcdefghijklmnop"); 
define("XPAY_API_URL", "https://sandbox.xpay.com/v1/orders"); 

// Get order details from the form
$product_id = $_POST['product_id'];
$product_total = $_POST['product_total'];
$product_qty = $_POST['product_qty'];

if (empty($product_total) || empty($product_id)) {
    die("Invalid payment request.");
}

// Generate unique order ID
$order_id = uniqid("order_");

// Define callback URLs
$success_url = "https://yourwebsite.com/success.php";
$cancel_url = "https://yourwebsite.com/cancel.php";

// Prepare payment request data
$data = [
    "amount" => $product_total * 100, // Convert to paisa (smallest currency unit)
    "currency" => "PKR", 
    "orderId" => $order_id,
    "customer" => [
        "email" => "testuser@example.com",
        "name" => "Test User"
    ],
    "callbackUrl" => $success_url,
    "cancelUrl" => $cancel_url
];

// Initialize cURL request
$ch = curl_init(XPAY_API_URL);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . XPAY_API_KEY
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable debugging

// Execute API request
$response = curl_exec($ch);
curl_close($ch);

// Decode API response
$responseData = json_decode($response, true);

// Check if payment initiation was successful
if (isset($responseData['redirectUrl'])) {
    $_SESSION['TID'] = $responseData['transactionId'];

    // Store Order in Database
    $db = new Database();
    $db->insert('payments', [
        'item_number' => $product_id,
        'txn_id' => $responseData['transactionId'],
        'payment_gross' => $product_total,
        'payment_status' => 'pending'
    ]);
    $db->insert('order_products', [
        'product_id' => $product_id,
        'product_qty' => $product_qty,
        'total_amount' => $product_total,
        'product_user' => $_SESSION['user_id'],
        'order_date' => date('Y-m-d'),
        'pay_req_id' => $responseData['transactionId']
    ]);
    $db->getResult();

    // Redirect to Payment Gateway
    header("Location: " . $responseData['redirectUrl']);
    exit();
} else {
    echo "Payment initiation failed: " . json_encode($responseData);
}
?>
