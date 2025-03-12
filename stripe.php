<?php 
// Successful Payment:
// Card Number: 4242 4242 4242 4242
// Card Declined:
// Card Number: 4000 0000 0000 9995
// Insufficient Funds:
// Card Number: 4000 0000 0000 9999
include 'config.php';

session_start();
$user = $_SESSION['username'];

// Retrieve site name from the database
$db = new Database();
$db->select('options', 'site_name', null, null, null, null);
$site_name = $db->getResult();

// Stripe API credentials
require_once('vendor/autoload.php');
\Stripe\Stripe::setApiKey('sk_test_51PPoLbECxkP4UAgfPIUKlmoCju2hQBQ8SrJ3h6ZAllZHXGunWhDBF670xkEoSMXOnCn3nhf2AT2Z6L5Rlux0Uppc00OEunD4NB'); // Use your actual secret key

// Get the total amount and product ID from the form submission
$product_total = $_POST['product_total'];
$product_id = $_POST['product_id'];
$product_qty = $_POST['product_qty'];
$product_qtys_array = explode(',', $product_qty);
$total_qty = array_sum($product_qtys_array); 
// Generate a unique order ID
$order_id = uniqid("order_");

// Create the Stripe checkout session
try {
    // Create a Stripe Checkout session
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [
            [
                'price_data' => [
                    'currency' => 'PKR', // or 'PKR' or your desired currency
                    'product_data' => [
                        'name' => 'Payment to ' . $site_name[0]['site_name'],
                        'description' => 'Order ID: ' . $order_id,
                    ],
                    'unit_amount' => $product_total * 100, // Amount in cents (or minor units of your currency)
                ],
                'quantity' => $total_qty,
            ],
        ],
        'mode' => 'payment',
        'success_url' => $hostname . '/success_stripe.php?session_id={CHECKOUT_SESSION_ID}',  // Redirect URL on success
        'cancel_url' => $hostname . '/success_stripe.php',  // URL when payment is cancelled
        'client_reference_id' => $order_id,  // Store the order ID for later reference
    ]);

    // Store the Stripe session ID in the session for later use
    $_SESSION['TID'] = $session->id;

    // Insert order and payment details into the database
    $params1 = [
        'item_number' => $product_id,
        'txn_id' => $session->id,
        'payment_gross' => $product_total,
        'payment_status' => 'pending', // Initially set the status to pending
    ];
    $params2 = [
        'product_id' => $product_id,
        'product_qty' => $product_qty,
        'total_amount' => $product_total,
        'product_user' => $_SESSION['user_id'],
        'order_date' => date('Y-m-d'),
        'pay_req_id' => $session->id
    ];

    // Insert payment and order information into the database
    $db->insert('payments', $params1);
    $db->insert('order_products', $params2);
    $db->getResult();

    // Redirect to the Stripe Checkout page
    header('Location: ' . $session->url);
    exit();

} catch (\Stripe\Exception\ApiErrorException $e) {
    // Handle the error
    echo 'Error creating payment session: ' . $e->getMessage();
}
?>
