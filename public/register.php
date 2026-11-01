

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create Your Account | ÉLAN BRIDAL</title>
  <meta name="description" content="Create your Élan Bridal account and keep your wedding style inspiration close.">
  <meta name="theme-color" content="#FAF8F5">
  <meta name="color-scheme" content="light">
  <link rel="icon" href="assets/icons/favicon.svg" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/main.js?v=<?php echo filemtime(__DIR__ . '/assets/js/main.js'); ?>" defer></script>
  <script src="assets/js/auth.js" defer></script>
  <script src="assets/js/cart.js" defer></script>
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>
  <header class="site-header site-header--solid" id="site-header">
    <section class="announcement" aria-label="Announcement"><p class="announcement__text"><span class="announcement__long">The Art of Your Forever &mdash; Discover the New Wedding Collection</span><span class="announcement__short">The Art of Your Forever</span></p></section>
    <div class="navbar"><div class="navbar__inner container">
      <a class="brand" href="index.php" aria-label="Élan Bridal — home"><span class="brand__primary">Élan</span><span class="brand__secondary">Bridal</span></a>
      <nav class="primary-nav" aria-label="Primary"><ul class="primary-nav__list">
        <li><a class="primary-nav__link" href="index.php">Home</a></li><li><a class="primary-nav__link" href="dresses.php">Dress</a></li><li><a class="primary-nav__link" href="suits.php">Suits</a></li><li><a class="primary-nav__link" href="accessories.php">Accessories</a></li><li><a class="primary-nav__link" href="index.php#about">About Us</a></li><li><a class="primary-nav__link is-active" href="register.php" aria-current="page">Register</a></li><li><a class="primary-nav__link" href="login.php">Log In</a></li><li><a class="primary-nav__link" href="cart.php">Bag <span data-cart-count>0</span></a></li>
      </ul></nav>
      <button class="menu-toggle" type="button" id="menu-toggle" aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu"><span class="menu-toggle__bars" aria-hidden="true"><span></span><span></span></span></button>
    </div></div>
    <div class="mobile-menu" id="mobile-menu" aria-hidden="true" inert><nav class="mobile-menu__inner container" aria-label="Mobile">
      <p class="mobile-menu__eyebrow">Explore Élan Bridal</p><ul class="mobile-menu__list">
        <li><a class="mobile-menu__link" href="index.php"><span>01</span>Home</a></li><li><a class="mobile-menu__link" href="dresses.php"><span>02</span>Dress</a></li><li><a class="mobile-menu__link" href="suits.php"><span>03</span>Suits</a></li><li><a class="mobile-menu__link" href="accessories.php"><span>04</span>Accessories</a></li><li><a class="mobile-menu__link" href="index.php#about"><span>05</span>About Us</a></li><li><a class="mobile-menu__link is-active" href="register.php" aria-current="page"><span>06</span>Register</a></li><li><a class="mobile-menu__link" href="login.php"><span>07</span>Log In</a></li><li><a class="mobile-menu__link" href="cart.php"><span>08</span>Bag <span data-cart-count>0</span></a></li>
      </ul><div class="mobile-menu__footer"><p>The Art of Forever &mdash; Collection 2026</p></div>
    </nav></div>
  </header>
  <main id="main" class="auth-main" tabindex="-1">
    <section class="auth-layout" aria-labelledby="auth-title">
      <div class="auth-editorial" role="img" aria-label="A bride and groom together in a sunlit garden"><div class="auth-editorial__caption"><span>Élan Bridal</span><span>The Art of Your Forever</span></div></div>
      <div class="auth-panel"><div class="auth-panel__inner">
        <p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span>Your Élan account</p>
        <h1 class="auth-title" id="auth-title">Create Your<br><em>Account</em></h1>
        <p class="auth-intro">A considered space for the details and inspiration that make your celebration yours.</p>
        <form class="auth-form" id="register-form" novalidate>
          <div class="auth-field"><label for="full-name">Full Name <span aria-hidden="true">*</span></label><input id="full-name" name="name" type="text" autocomplete="name" placeholder="Your full name" required aria-describedby="full-name-error"><p class="auth-error" id="full-name-error"></p></div>
          <div class="auth-field"><label for="register-email">Email Address <span aria-hidden="true">*</span></label><input id="register-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required aria-describedby="register-email-error"><p class="auth-error" id="register-email-error"></p></div>
          <div class="auth-field"><label for="phone">Phone Number <span class="auth-optional">Optional</span></label><input id="phone" name="phone" type="tel" autocomplete="tel" placeholder="Your phone number"></div>
          <div class="auth-field"><label for="register-password">Password <span aria-hidden="true">*</span></label><div class="auth-input-wrap"><input id="register-password" name="password" type="password" autocomplete="new-password" placeholder="Create a password" required aria-describedby="register-password-error"><button class="auth-toggle" type="button" data-toggle-password="register-password" aria-label="Show password">Show</button></div><p class="auth-error" id="register-password-error"></p></div>
          <div class="auth-field"><label for="confirm-password">Confirm Password <span aria-hidden="true">*</span></label><div class="auth-input-wrap"><input id="confirm-password" name="confirmPassword" type="password" autocomplete="new-password" placeholder="Enter your password again" required aria-describedby="confirm-password-error"><button class="auth-toggle" type="button" data-toggle-password="confirm-password" aria-label="Show confirm password">Show</button></div><p class="auth-error" id="confirm-password-error"></p></div>
          <p class="auth-status" id="register-status" role="status" aria-live="polite"></p>
          <button class="btn auth-submit" type="submit">Create Account</button>
        </form>
        <p class="auth-switch">Already have an account? <a href="login.php">Log in</a></p>
        <a class="text-link auth-home" href="index.php">Return to the landing page <span aria-hidden="true">&rarr;</span></a>
      </div></div>
    </section>
  </main>
  <footer class="site-footer auth-footer"><div class="container auth-footer__inner"><a class="brand brand--footer" href="index.php" aria-label="Élan Bridal — home"><span class="brand__primary">Élan</span><span class="brand__secondary">Bridal</span></a><p class="footer__copyright">&copy; <span id="year">2026</span> Élan Bridal. A frontend demonstration.</p><nav aria-label="Account"><a href="register.php" aria-current="page">Register</a><a href="login.php">Log In</a></nav></div></footer>
</body>
</html>
