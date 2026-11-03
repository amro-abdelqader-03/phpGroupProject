<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
            throw new RuntimeException('Online payment verification is not configured.');
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
    $errorMessage = $e->getMessage();
}

function escapeOrderValue(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$paymentConfirmed = $order !== null && $order['payment_status'] === 'Paid';
$deliveryAddress = $order
    ? implode(', ', array_filter([
        $order['shipping_address'] ?? '',
        $order['building_details'] ?? '',
        $order['area'] ?? '',
        $order['city'] ?? '',
    ], static fn($part): bool => trim((string) $part) !== ''))
    : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $order ? 'Order Confirmation' : 'Payment Status'; ?> | ÉLAN BRIDAL</title>
    <meta name="description" content="View your Élan Bridal order confirmation and delivery details.">
    <meta name="theme-color" content="#FAF8F5">
    <meta name="color-scheme" content="light">
    <link rel="icon" href="assets/icons/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/assets/css/style.css'); ?>">
    <script src="assets/js/main.js?v=<?php echo filemtime(__DIR__ . '/assets/js/main.js'); ?>" defer></script>
    <script src="assets/js/cart.js?v=<?php echo filemtime(__DIR__ . '/assets/js/cart.js'); ?>" defer></script>
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>
  <header class="site-header site-header--solid" id="site-header">
    <section class="announcement" aria-label="Announcement">
      <p class="announcement__text">
        <span class="announcement__long">The Art of Your Forever &mdash; Discover the New Wedding Collection</span>
        <span class="announcement__short">The Art of Your Forever</span>
      </p>
    </section>
    <div class="navbar">
      <div class="navbar__inner container">
        <a class="brand" href="index.php" aria-label="Élan Bridal — home">
          <span class="brand__primary">Élan</span>
          <span class="brand__secondary">Bridal</span>
        </a>
        <nav class="primary-nav" aria-label="Primary">
          <ul class="primary-nav__list">
            <li><a class="primary-nav__link" href="index.php">Home</a></li>
            <li><a class="primary-nav__link" href="dresses.php">Dress</a></li>
            <li><a class="primary-nav__link" href="suits.php">Suits</a></li>
            <li><a class="primary-nav__link" href="accessories.php">Accessories</a></li>
            <li><a class="primary-nav__link" href="index.php#about">About Us</a></li>
            <li><a class="primary-nav__link" href="register.php">Register</a></li>
            <li><a class="primary-nav__link" href="login.php">Log In</a></li>
            <li><a class="primary-nav__link" href="cart.php">Bag <span data-cart-count>0</span></a></li>
          </ul>
        </nav>
        <button class="menu-toggle" type="button" id="menu-toggle" aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu">
          <span class="menu-toggle__bars" aria-hidden="true"><span></span><span></span></span>
        </button>
      </div>
    </div>
    <div class="mobile-menu" id="mobile-menu" aria-hidden="true" inert>
      <nav class="mobile-menu__inner container" aria-label="Mobile">
        <p class="mobile-menu__eyebrow">Explore Élan Bridal</p>
        <ul class="mobile-menu__list">
          <li><a class="mobile-menu__link" href="index.php"><span>01</span>Home</a></li>
          <li><a class="mobile-menu__link" href="dresses.php"><span>02</span>Dress</a></li>
          <li><a class="mobile-menu__link" href="suits.php"><span>03</span>Suits</a></li>
          <li><a class="mobile-menu__link" href="accessories.php"><span>04</span>Accessories</a></li>
          <li><a class="mobile-menu__link" href="index.php#about"><span>05</span>About Us</a></li>
          <li><a class="mobile-menu__link" href="register.php"><span>06</span>Register</a></li>
          <li><a class="mobile-menu__link" href="login.php"><span>07</span>Log In</a></li>
          <li><a class="mobile-menu__link" href="cart.php"><span>08</span>Bag <span data-cart-count>0</span></a></li>
        </ul>
        <div class="mobile-menu__footer">
          <p>The Art of Forever &mdash; Collection 2026</p>
        </div>
      </nav>
    </div>
  </header>

  <main class="order-confirmation" id="main" tabindex="-1">
    <div class="container">
      <?php if ($order): ?>
        <section class="order-confirmation__hero<?php echo $paymentConfirmed ? ' is-paid' : ' is-pending'; ?>" aria-labelledby="order-title">
          <span class="order-confirmation__mark" aria-hidden="true">
            <?php if ($paymentConfirmed): ?>
              <svg viewBox="0 0 24 24" fill="none"><path d="m5 12.5 4.5 4.5L19 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <?php else: ?>
              <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M12 8v4l2.5 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <?php endif; ?>
          </span>
          <p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span><?php echo $paymentConfirmed ? 'Payment confirmed' : 'Order received'; ?></p>
          <h1 class="order-confirmation__title" id="order-title">
            <?php echo $paymentConfirmed ? 'Thank you for your' : 'Your order is'; ?><br><em><?php echo $paymentConfirmed ? 'order.' : 'confirmed.'; ?></em>
          </h1>
          <p class="order-confirmation__intro">
            <?php echo $paymentConfirmed
                ? 'Your payment was successful. We are preparing your selected pieces.'
                : 'Your order has been received. Payment is due when your order is delivered.'; ?>
          </p>
          <p class="order-confirmation__reference">Order <strong>#<?php echo escapeOrderValue($order['id']); ?></strong></p>
        </section>

        <div class="order-confirmation__layout">
          <section class="order-confirmation__card" aria-labelledby="items-title">
            <div class="order-confirmation__card-head">
              <div>
                <p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span>Your selection</p>
                <h2 id="items-title">Order details</h2>
              </div>
              <span class="order-confirmation__item-count"><?php echo count($order['items']); ?> <?php echo count($order['items']) === 1 ? 'item' : 'items'; ?></span>
            </div>
            <ul class="order-confirmation__items">
              <?php foreach ($order['items'] as $index => $item): ?>
                <li class="order-confirmation__item">
                  <span class="order-confirmation__item-index" aria-hidden="true"><?php echo str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT); ?></span>
                  <span class="order-confirmation__item-info">
                    <strong><?php echo escapeOrderValue($item['product_name']); ?></strong>
                    <span>Quantity <?php echo escapeOrderValue($item['quantity']); ?> &middot; $<?php echo number_format((float) $item['unit_price'], 3); ?> each</span>
                  </span>
                  <strong class="order-confirmation__item-total">$<?php echo number_format((float) $item['unit_price'] * (int) $item['quantity'], 3); ?></strong>
                </li>
              <?php endforeach; ?>
            </ul>
            <div class="order-confirmation__total">
              <span>Total <?php echo $paymentConfirmed ? 'paid' : 'due'; ?></span>
              <strong>$<?php echo number_format((float) $order['total_amount'], 3); ?> <small>USD</small></strong>
            </div>
          </section>

          <aside class="order-confirmation__card order-confirmation__summary" aria-label="Delivery and payment summary">
            <p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span>Next steps</p>
            <h2>Delivery details</h2>
            <dl class="order-confirmation__meta">
              <div><dt>Order date</dt><dd><?php echo escapeOrderValue($order['order_date']); ?></dd></div>
              <div><dt>Estimated delivery</dt><dd><?php echo escapeOrderValue($order['estimated_delivery'] ?? 'To be confirmed'); ?></dd></div>
              <div><dt>Order status</dt><dd><?php echo escapeOrderValue($order['order_status']); ?></dd></div>
              <div><dt>Payment status</dt><dd><?php echo escapeOrderValue($order['payment_status']); ?></dd></div>
              <div><dt>Deliver to</dt><dd><?php echo escapeOrderValue($order['full_name']); ?><br><?php echo escapeOrderValue($deliveryAddress); ?></dd></div>
            </dl>
          </aside>
        </div>

        <div class="order-confirmation__actions">
          <a class="btn order-confirmation__continue" href="suits.php">Continue exploring</a>
          <a class="text-link" href="index.php">Return to home <span aria-hidden="true">&rarr;</span></a>
        </div>
      <?php else: ?>
        <section class="order-confirmation__hero order-confirmation__hero--error" aria-labelledby="order-title">
          <span class="order-confirmation__mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none"><path d="M7 7l10 10M17 7 7 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          </span>
          <p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span>Payment status</p>
          <h1 class="order-confirmation__title" id="order-title">Payment not<br><em>confirmed.</em></h1>
          <p class="order-confirmation__intro"><?php echo escapeOrderValue($errorMessage); ?></p>
          <div class="order-confirmation__actions">
            <a class="btn order-confirmation__continue" href="cart.php">Return to your bag</a>
            <a class="text-link" href="index.php">Return to home <span aria-hidden="true">&rarr;</span></a>
          </div>
        </section>
      <?php endif; ?>
    </div>
  </main>

  <footer class="site-footer auth-footer" id="footer">
    <div class="container auth-footer__inner">
      <a class="brand brand--footer" href="index.php" aria-label="Élan Bridal — home">
        <span class="brand__primary">Élan</span>
        <span class="brand__secondary">Bridal</span>
      </a>
      <p class="footer__copyright">&copy; <span id="year">2026</span> Élan Bridal. All rights reserved.</p>
      <nav aria-label="Continue shopping"><a href="dresses.php">Dresses</a><a href="accessories.php">Accessories</a></nav>
    </div>
  </footer>
</body>
</html>