<?php
require_once __DIR__ . '/../stripe-php/stripe-php-master/init.php';
require_once __DIR__ . '/../app/config/Database.php';

$sessionId = $_GET['session_id'] ?? null;
$paymentSuccess = false;
$errorMessage = '';

if ($sessionId) {
    // Set your secret Stripe API key
    \Stripe\Stripe::setApiKey('sk_test_51UWep4CDQG6C8EVj6v4II3pKd9KRnntvXBXBYOHBQxdUVAyFkJ7MLRci8SpR6Tu6dImQja5QqQjgxggcKdIulS6M009UphvI9I');

    try {
        // Retrieve session details from Stripe
        $session = \Stripe\Checkout\Session::retrieve([
            'id' => $sessionId,
            'expand' => ['line_items', 'line_items.data.price.product']
        ]);

        if ($session->payment_status == 'paid') {
            
            $database = new Database();
            $db = $database->connect();

            // Check if this payment/order has already been saved to prevent duplication
            $checkStmt = $db->prepare("SELECT p.id FROM payments p JOIN orders o ON p.order_id = o.id WHERE p.transaction_reference = ?");
            $checkStmt->execute([$session->id]);
            
            if ($checkStmt->rowCount() == 0) {
                $userId = $_SESSION['user_id'] ?? 2; 
                $totalAmount = $session->amount_total / 100; 
                $customerName = $session->customer_details->name ?? 'Customer Name';
                $customerPhone = $session->customer_details->phone ?? '0700000000';
                
                $shippingCost = 5.00; 
                $productsTotal = $totalAmount - $shippingCost;
                $revenue = $productsTotal * 0.40; 

                // 1. Insert order details into the 'orders' table
                $orderQuery = "INSERT INTO orders (user_id, total_amount, order_status, full_name, phone, country, city, shipping_address, shipping_cost, revenue) 
                               VALUES (?, ?, 'Confirmed', ?, ?, 'Jordan', 'Amman', 'Online Stripe Payment', ?, ?)";
                $orderStmt = $db->prepare($orderQuery);
                $orderStmt->execute([
                    $userId,
                    $totalAmount,
                    $customerName,
                    $customerPhone,
                    $shippingCost,
                    $revenue
                ]);
                
                $orderId = $db->lastInsertId();

                // 2. Insert payment details into the 'payments' table
                $paymentQuery = "INSERT INTO payments (order_id, payment_method, amount, payment_status, transaction_reference, paid_at) 
                                 VALUES (?, 'Credit Card', ?, 'Paid', ?, NOW())";
                $paymentStmt = $db->prepare($paymentQuery);
                $paymentStmt->execute([
                    $orderId,
                    $totalAmount,
                    $session->id
                ]);
            }

            $paymentSuccess = true;
        }
    } catch (\Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="font-family: sans-serif; text-align: center; padding: 50px;">
    <?php if ($paymentSuccess): ?>
        <h1 style="color: green;">Payment and Order Recorded Successfully!</h1>
        <p>Thank you for your order. The payment was processed via Stripe and saved to the database successfully.</p>
        <p style="color: #666; font-size: 14px;">Session ID: <?php echo htmlspecialchars($sessionId); ?></p>
    <?php else: ?>
        <h1 style="color: red;">Oops!</h1>
        <p>Payment verification failed or an error occurred while processing your order.</p>
        <?php if ($errorMessage): ?>
            <p style="color: red; font-size: 14px;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
    <?php endif; ?>
    <br>
    <a href="index.php" style="padding: 10px 20px; background: #000; color: #fff; text-decoration: none;">Return to Store</a>
</body>
</html>