
<?php
require_once __DIR__ . '/../stripe-php/stripe-php-master/init.php';
require_once __DIR__ . '/session.php';
if (!$isLoggedIn) {
  header('Location: login.php?next=checkout.php');
  exit();
}
$_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
$_SESSION['checkout_nonce'] ??= bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Complete Your Order | ÉLAN BRIDAL</title>
  <meta name="description" content="Review your Élan Bridal bag and enter delivery details.">
  <meta name="theme-color" content="#FAF8F5">
  <meta name="color-scheme" content="light">
  <link rel="icon" href="assets/icons/favicon.svg" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/main.js?v=<?php echo filemtime(__DIR__ . '/assets/js/main.js'); ?>" defer></script>
  <script src="assets/js/cart.js?v=<?php echo filemtime(__DIR__ . '/assets/js/cart.js'); ?>" defer></script>
  <script src="assets/js/checkout.js?v=<?php echo filemtime(__DIR__ . '/assets/js/checkout.js'); ?>" defer></script>
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>
  <header class="site-header site-header--solid" id="site-header">
    <section class="announcement" aria-label="Announcement"><p class="announcement__text"><span class="announcement__long">The Art of Your Forever &mdash; Discover the New Wedding Collection</span><span class="announcement__short">The Art of Your Forever</span></p></section>
    <div class="navbar"><div class="navbar__inner container">
      <a class="brand" href="index.php" aria-label="Élan Bridal — home"><span class="brand__primary">Élan</span><span class="brand__secondary">Bridal</span></a>
      <nav class="primary-nav" aria-label="Primary"><ul class="primary-nav__list">
        <li><a class="primary-nav__link" href="index.php">Home</a></li><li><a class="primary-nav__link" href="dresses.php">Dress</a></li><li><a class="primary-nav__link" href="suits.php">Suits</a></li><li><a class="primary-nav__link" href="accessories.php">Accessories</a></li><li><a class="primary-nav__link" href="register.php">Register</a></li><li><a class="primary-nav__link" href="login.php">Log In</a></li><li><a class="primary-nav__link" href="cart.php">Bag <span data-cart-count>0</span></a></li>
      </ul></nav>
      <button class="menu-toggle" type="button" id="menu-toggle" aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu"><span class="menu-toggle__bars" aria-hidden="true"><span></span><span></span></span></button>
    </div></div>
    <div class="mobile-menu" id="mobile-menu" aria-hidden="true" inert><nav class="mobile-menu__inner container" aria-label="Mobile"><p class="mobile-menu__eyebrow">Explore Élan Bridal</p><ul class="mobile-menu__list">
      <li><a class="mobile-menu__link" href="index.php"><span>01</span>Home</a></li><li><a class="mobile-menu__link" href="dresses.php"><span>02</span>Dress</a></li><li><a class="mobile-menu__link" href="suits.php"><span>03</span>Suits</a></li><li><a class="mobile-menu__link" href="accessories.php"><span>04</span>Accessories</a></li><li><a class="mobile-menu__link" href="register.php"><span>05</span>Register</a></li><li><a class="mobile-menu__link" href="login.php"><span>06</span>Log In</a></li><li><a class="mobile-menu__link" href="cart.php"><span>07</span>Bag <span data-cart-count>0</span></a></li>
    </ul><div class="mobile-menu__footer"><p>The Art of Forever &mdash; Collection 2026</p></div></nav></div>
  </header>
  <main id="main" class="checkout-main" tabindex="-1">
    <div class="container">
      <nav class="detail-breadcrumb" aria-label="Breadcrumb"><a href="index.php">Home</a><span aria-hidden="true">/</span><a href="cart.php">Your Bag</a><span aria-hidden="true">/</span><span aria-current="page">Checkout</span></nav>
      <header class="checkout-heading"><p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span>Élan Bridal</p><h1 class="checkout-title">Complete Your <em>Order</em></h1><p class="checkout-intro">Share your delivery details and review your selected looks.</p></header>
      <div class="checkout-layout">
              <form class="checkout-form" id="checkout-form" action="../app/controllers/process-order.php" method="POST" novalidate>
              <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
              <input type="hidden" name="checkout_nonce" value="<?php echo htmlspecialchars($_SESSION['checkout_nonce'], ENT_QUOTES, 'UTF-8'); ?>">
              <section class="checkout-section" aria-labelledby="delivery-title">
            <div class="checkout-section__heading"><span class="checkout-section__index">01</span><div><h2 id="delivery-title">Delivery Address</h2><p>Where should we deliver your selection?</p></div></div>
            <div class="checkout-fields">
              <div class="checkout-field checkout-field--wide"><label for="delivery-name">Full Name <span aria-hidden="true">*</span></label><input id="delivery-name" name="name" autocomplete="name" type="text" placeholder="Your full name" required aria-describedby="delivery-name-error"><p class="checkout-error" id="delivery-name-error"></p></div>
              <div class="checkout-field"><label for="delivery-phone">Phone Number <span aria-hidden="true">*</span></label><input id="delivery-phone" name="phone" autocomplete="tel" type="tel" placeholder="e.g. +962 7X XXX XXXX" required aria-describedby="delivery-phone-error"><p class="checkout-error" id="delivery-phone-error"></p></div>
              <div class="checkout-field"><label for="delivery-city">City <span aria-hidden="true">*</span></label><input id="delivery-city" name="city" autocomplete="address-level2" type="text" placeholder="City" required aria-describedby="delivery-city-error"><p class="checkout-error" id="delivery-city-error"></p></div>
              <div class="checkout-field"><label for="delivery-area">Area / District <span aria-hidden="true">*</span></label><input id="delivery-area" name="area" autocomplete="address-level3" type="text" placeholder="Area or district" required aria-describedby="delivery-area-error"><p class="checkout-error" id="delivery-area-error"></p></div>
              <div class="checkout-field"><label for="delivery-street">Street Address <span aria-hidden="true">*</span></label><input id="delivery-street" name="street" autocomplete="address-line1" type="text" placeholder="Street name" required aria-describedby="delivery-street-error"><p class="checkout-error" id="delivery-street-error"></p></div>
              <div class="checkout-field"><label for="delivery-building">Building Number <span class="checkout-optional">Optional</span></label><input id="delivery-building" name="building" autocomplete="address-line2" type="text" placeholder="Building number"></div>
              <div class="checkout-field"><label for="delivery-apartment">Apartment / Floor <span class="checkout-optional">Optional</span></label><input id="delivery-apartment" name="apartment" type="text" placeholder="Apartment or floor"></div>
              <div class="checkout-field checkout-field--wide"><label for="delivery-instructions">Additional Delivery Instructions <span class="checkout-optional">Optional</span></label><textarea id="delivery-instructions" name="instructions" rows="3" placeholder="Helpful directions for your courier"></textarea></div>
            </div>
            <p class="checkout-privacy-note">Your delivery details are saved with your order and used only to fulfil delivery.</p>
          </section>
          <section class="checkout-section checkout-payment" aria-labelledby="payment-title">
            <div class="checkout-section__heading"><span class="checkout-section__index">02</span><div><h2 id="payment-title">Payment Method</h2><p>Choose how you would prefer to pay.</p></div></div>
            <fieldset class="checkout-payment__options"><legend class="visually-hidden">Choose a payment method</legend>
              <label class="payment-option"><input type="radio" name="payment" value="cash-on-delivery"><span class="payment-option__icon" aria-hidden="true"><svg viewBox="0 0 32 32"><rect x="4" y="8" width="24" height="16" rx="1" fill="none" stroke="currentColor" stroke-width="1.5"/><circle cx="16" cy="16" r="4" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M7 12h2m14 8h2" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></span><span class="payment-option__copy"><strong>Cash on Delivery</strong><small>Pay in cash when your order arrives</small></span><span class="payment-option__radio" aria-hidden="true"></span></label>
              <label class="payment-option"><input type="radio" name="payment" value="visa"><span class="payment-option__icon payment-option__icon--visa" aria-hidden="true"><svg viewBox="0 0 36 24"><rect x="1" y="2" width="34" height="20" rx="2" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="m13 8-2 8h2l2-8zm7 0-2.2 5.1-.25-1.2-.8-3.1s-.1-.8-1.1-.8h-3.3l-.05.2s1 .2 1.9.8l1.6 7h2.1l3.2-8zm3 0-1.5 8h2l1.5-8z" fill="currentColor"/></svg></span><span class="payment-option__copy"><strong>Card payment with Stripe</strong><small>Credit or debit card · secure hosted checkout</small></span><span class="payment-option__radio" aria-hidden="true"></span></label>
            </fieldset>
            <p class="checkout-error" id="payment-error" role="alert"></p>
            <div class="payment-placeholder" id="visa-placeholder" hidden><strong>Secure card checkout</strong><span>You will continue to Stripe to enter your card details. Card information is never stored on this site.</span></div>
          </section>
          <div class="checkout-submit-area"><p class="checkout-result" id="checkout-result" role="status" aria-live="polite"></p><button class="btn checkout-submit" id="place-order" type="submit">Place Order</button><p class="checkout-terms-note">Card payments are securely processed by Stripe. Cash-on-delivery orders are confirmed when submitted.</p></div>
        </form>
        <aside class="checkout-summary" aria-labelledby="order-title">
          <div class="checkout-summary__head"><div><p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span>Review</p><h2 id="order-title">Your Order</h2></div><a href="cart.php">Edit bag</a></div>
          <div id="checkout-items" class="checkout-items" aria-live="polite"></div>
          <div class="checkout-empty" id="checkout-empty" hidden><p>Your bag is empty.</p><a class="text-link" href="suits.php">Explore the collections <span aria-hidden="true">&rarr;</span></a></div>
          <dl class="checkout-totals"><div><dt>Subtotal</dt><dd id="checkout-subtotal">—</dd></div><div><dt>Delivery Fee</dt><dd id="checkout-delivery">$5.000</dd></div><div class="checkout-totals__grand"><dt>Total Price</dt><dd id="checkout-total">—</dd></div></dl>
          <p class="checkout-currency-note">Totals use catalogue prices in USD ($) and a $5.000 delivery fee. An unavailable item price means the selection cannot be checked out.</p>
        </aside>
      </div>
    </div>
  </main>
  <footer class="site-footer auth-footer" id="footer"><div class="container auth-footer__inner"><a class="brand brand--footer" href="index.php" aria-label="Élan Bridal — home"><span class="brand__primary">Élan</span><span class="brand__secondary">Bridal</span></a><p class="footer__copyright">&copy; <span id="year">2026</span> Élan Bridal. A frontend demonstration.</p><nav aria-label="Continue shopping"><a href="cart.php">Your Bag</a><a href="suits.php">Suits</a><a href="dresses.php">Dresses</a></nav></div></footer>
</body>
</html>
