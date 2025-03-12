<?php
include 'config.php';
session_start();

if (isset($_GET['session_id']) && isset($_SESSION['TID'])) {
    require_once('vendor/autoload.php');
    // Retrieve the session ID from the query parameter
    $session_id = $_GET['session_id'];
    \Stripe\Stripe::setApiKey('sk_test_51PPoLbECxkP4UAgfPIUKlmoCju2hQBQ8SrJ3h6ZAllZHXGunWhDBF670xkEoSMXOnCn3nhf2AT2Z6L5Rlux0Uppc00OEunD4NB'); // Replace with your secret key

    // Retrieve the session object from Stripe API to check payment status
    try {
        $session = \Stripe\Checkout\Session::retrieve($session_id);

        // Verify that the session matches the one stored in the session
        if ($session->id === $_SESSION['TID'] && $session->payment_status === 'paid') {
            // Payment was successful
            $payment_status = 'Credit';
            $title = 'Payment Successful';
            $response = '<div class="panel-body">
                        <i class="fa fa-check-circle text-success"></i>
                        <h3>Payment Successful</h3>
                        <p>Your Product Will be Delivered within 3 to 4 days.</p>
                        <a href="' . $hostname . '" class="btn btn-md btn-primary">Continue Shopping</a>
                      </div>';

            // Update the payment status in the database
            $db = new Database();
            $db->update('payments', ['payment_status' => $payment_status], "txn_id = '{$session_id}'");
            $db->select('order_products', 'product_id,product_qty', null, "pay_req_id ='{$session->id}'", null, null);
            $result = $db->getResult();
            $products = array_filter(explode(',', $result[0]['product_id']));
            $qty = array_filter(explode(',', $result[0]['product_qty']));
            for ($i = 0; $i < count($products); $i++) {
                // Reduce quantity from each product
                $db->sql("UPDATE products SET qty = qty - '{$qty[$i]}' WHERE product_id = '{$products[$i]}'");
            }

            // Handle cart removal (if the user has items in the cart cookie)
            if (isset($_COOKIE['user_cart'])) {
                setcookie('cart_count', '', time() - 180, '/', '', '', true);
                setcookie('user_cart', '', time() - 180, '/', '', '', true);
            }
            // Update other relevant tables, like order_products, if needed
        } else {
            // Payment failed or invalid session
            $payment_status = 'Failed';
            $title = 'Payment Unsuccessful';
            $response = '<div class="panel-body">
                        <i class="fa fa-times-circle text-danger"></i>
                        <h3>Payment Unsuccessful</h3>
                        <a href="' . $hostname . '" class="btn btn-md btn-primary">Continue Shopping</a>
                      </div>';
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Handle Stripe API error
        $title = 'Error';
        $response = '<div class="panel-body">
                    <i class="fa fa-times-circle text-danger"></i>
                    <h3>Error: ' . $e->getMessage() . '</h3>
                    <a href="' . $hostname . '" class="btn btn-md btn-primary">Continue Shopping</a>
                  </div>';
    }
} else {
    // Invalid or missing session ID
    $title = 'Error';
    $response = '<div class="panel-body">
                <i class="fa fa-times-circle text-danger"></i>
                <h3>Invalid Payment Request</h3>
                <a href="' . $hostname . '" class="btn btn-md btn-primary">Continue Shopping</a>
              </div>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="payment-response">
        <div class="container">
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <div class="panel panel-default">
                        <?php echo $response; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>