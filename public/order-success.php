<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/config/Env.php';
require_once __DIR__ . '/../app/models/OrderModel.php';
require_once __DIR__ . '/../stripe-php/stripe-php-master/init.php';

$order = null;
$errorMessage = '';
$sessionId = trim((string) ($_GET['session_id'] ?? ''));
$requestedOrderId = filter_var($_GET['order_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

try {
    $pdo = (new Database())->connect();
    if ($sessionId !== '') {
        if (!preg_match('/^cs_(test|live)_[A-Za-z0-9]+$/', $sessionId)) {
            throw new RuntimeException('The payment confirmation link is invalid.');
        }

        Env::load(dirname(__DIR__) . '/.env');
        $stripeSecret = Env::get('STRIPE_SECRET_KEY');
        if (!$stripeSecret || !str_starts_with($stripeSecret, 'sk_')) {
            throw new RuntimeException('Online payment verification is not configured. Contact support with your order details.');
        }
        \Stripe\Stripe::setApiKey($stripeSecret);
        $stripeSession = \Stripe\Checkout\Session::retrieve($sessionId);
        $metadataOrderId = filter_var($stripeSession->metadata->order_id ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $metadataUserId = filter_var($stripeSession->metadata->user_id ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (
            $stripeSession->payment_status !== 'paid'
            || $metadataOrderId === false
            || $metadataUserId === false
            || (int) $metadataUserId !== (int) $_SESSION['user_id']
        ) {
            throw new RuntimeException('Payment has not been confirmed for this account.');
        }

        $order = OrderModel::completeStripePayment(
            $pdo,
            (int) $metadataOrderId,
            (int) $_SESSION['user_id'],
            $sessionId,
            (int) ($stripeSession->amount_total ?? 0),
            (string) ($stripeSession->currency ?? '')
        );
    } elseif ($requestedOrderId !== false) {
        $order = OrderModel::getOrderForCustomer($pdo, (int) $requestedOrderId, (int) $_SESSION['user_id']);
    } else {
        throw new RuntimeException('The order details link is invalid.');
    }

    if (!$order) {
        throw new RuntimeException('We could not match this payment to an order on your account.');
    }
} catch (Throwable $e) {
    error_log($e->getMessage());
    $errorMessage = $e instanceof RuntimeException ? $e->getMessage() : 'We could not verify the payment right now. Please contact support if your card was charged.';
}

function escapeOrderValue(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $order ? 'Order Details' : 'Payment Status'; ?> | ÉLAN BRIDAL</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <main class="container" style="max-width: 760px; padding-block: 4rem;">
    <?php if ($order): ?>
        <p class="eyebrow">Élan Bridal</p>
        <h1>Thank you for your order</h1>
        <p><?php echo $order['payment_status'] === 'Paid' ? 'Your payment is confirmed.' : 'Your order has been received. Payment is due on delivery.'; ?> Your order details and current status are below.</p>
        <dl>
            <dt>Order</dt><dd>#<?php echo escapeOrderValue($order['id']); ?></dd>
            <dt>Placed</dt><dd><?php echo escapeOrderValue($order['order_date']); ?></dd>
            <dt>Estimated delivery</dt><dd><?php echo escapeOrderValue($order['estimated_delivery'] ?? 'Not available'); ?></dd>
            <dt>Order status</dt><dd><?php echo escapeOrderValue($order['order_status']); ?></dd>
            <dt>Payment status</dt><dd><?php echo escapeOrderValue($order['payment_status']); ?></dd>
            <dt>Total paid</dt><dd><?php echo number_format((float) $order['total_amount'], 3); ?> JOD</dd>
            <dt>Delivery address</dt><dd><?php echo escapeOrderValue($order['shipping_address'] . ', ' . $order['area'] . ', ' . $order['city']); ?></dd>
        </dl>
        <h2>Items</h2>
        <ul>
            <?php foreach ($order['items'] as $item): ?>
                <li><?php echo escapeOrderValue($item['product_name']); ?> × <?php echo escapeOrderValue($item['quantity']); ?> — <?php echo number_format((float) $item['unit_price'] * (int) $item['quantity'], 3); ?> JOD</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <h1>Payment not confirmed</h1>
        <p><?php echo escapeOrderValue($errorMessage); ?></p>
    <?php endif; ?>
    <p><a class="btn" href="index.php">Return to store</a></p>
  </main>
</body>
</html>