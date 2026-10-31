


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log In | ÉLAN BRIDAL</title>
  <meta name="description" content="Log in to your Élan Bridal account.">
  <meta name="theme-color" content="#FAF8F5">
  <meta name="color-scheme" content="light">
  <link rel="icon" href="assets/icons/favicon.svg" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/main.js" defer></script>
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
        <li><a class="primary-nav__link" href="index.php">Home</a></li><li><a class="primary-nav__link" href="suits.php">Suits</a></li><li><a class="primary-nav__link" href="dresses.php">Dress</a></li><li><a class="primary-nav__link" href="accessories.php">Accessories</a></li><li><a class="primary-nav__link" href="index.php#about">About Us</a></li><li><a class="primary-nav__link" href="register.php">Register</a></li><li><a class="primary-nav__link is-active" href="login.php" aria-current="page">Log In</a></li><li><a class="primary-nav__link" href="cart.php">Bag <span data-cart-count>0</span></a></li>
      </ul></nav>
      <button class="menu-toggle" type="button" id="menu-toggle" aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu"><span class="menu-toggle__bars" aria-hidden="true"><span></span><span></span></span></button>
    </div></div>
    <div class="mobile-menu" id="mobile-menu" aria-hidden="true" inert><nav class="mobile-menu__inner container" aria-label="Mobile">
      <p class="mobile-menu__eyebrow">Explore Élan Bridal</p><ul class="mobile-menu__list">
        <li><a class="mobile-menu__link" href="index.php"><span>01</span>Home</a></li><li><a class="mobile-menu__link" href="suits.php"><span>02</span>Suits</a></li><li><a class="mobile-menu__link" href="dresses.php"><span>03</span>Dress</a></li><li><a class="mobile-menu__link" href="accessories.php"><span>04</span>Accessories</a></li><li><a class="mobile-menu__link" href="index.php#about"><span>05</span>About Us</a></li><li><a class="mobile-menu__link" href="register.php"><span>06</span>Register</a></li><li><a class="mobile-menu__link is-active" href="login.php" aria-current="page"><span>07</span>Log In</a></li><li><a class="mobile-menu__link" href="cart.php"><span>08</span>Bag <span data-cart-count>0</span></a></li>
      </ul><div class="mobile-menu__footer"><p>The Art of Forever &mdash; Collection 2026</p></div>
    </nav></div>
  </header>
  <main id="main" class="auth-main" tabindex="-1">
    <section class="auth-layout auth-layout--login" aria-labelledby="auth-title">
      <div class="auth-editorial" role="img" aria-label="A bride and groom together in a sunlit garden"><div class="auth-editorial__caption"><span>Élan Bridal</span><span>The Art of Your Forever</span></div></div>
      <div class="auth-panel"><div class="auth-panel__inner">
        <p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span>Welcome to Élan</p>
        <h1 class="auth-title" id="auth-title">Welcome<br><em>Back</em></h1>
        <p class="auth-intro">Return to your personal edit of considered pieces and wedding-day inspiration.</p>
        <form class="auth-form" id="login-form" novalidate>
          <div class="auth-field"><label for="login-email">Email Address <span aria-hidden="true">*</span></label><input id="login-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required aria-describedby="login-email-error"><p class="auth-error" id="login-email-error"></p></div>
          <div class="auth-field"><label for="login-password">Password <span aria-hidden="true">*</span></label><div class="auth-input-wrap"><input id="login-password" name="password" type="password" autocomplete="current-password" placeholder="Your password" required aria-describedby="login-password-error"><button class="auth-toggle" type="button" data-toggle-password="login-password" aria-label="Show password">Show</button></div><p class="auth-error" id="login-password-error"></p></div>
          <div class="auth-forgot"><button class="auth-inline-button" type="button" id="forgot-password">Forgot Password?</button><span>Password reset is not available in this demonstration.</span></div>
          <p class="auth-status" id="login-status" role="status" aria-live="polite"></p>
          <button class="btn auth-submit" type="submit">Log In</button>
        </form>
        <p class="auth-switch">Don't have an account? <a href="register.php">Create one</a></p>
        <a class="text-link auth-home" href="index.php">Return to the landing page <span aria-hidden="true">&rarr;</span></a>
      </div></div>
    </section>
  </main>
  <footer class="site-footer auth-footer"><div class="container auth-footer__inner"><a class="brand brand--footer" href="index.php" aria-label="Élan Bridal — home"><span class="brand__primary">Élan</span><span class="brand__secondary">Bridal</span></a><p class="footer__copyright">&copy; <span id="year">2026</span> Élan Bridal. A frontend demonstration.</p><nav aria-label="Account"><a href="register.php">Register</a><a href="login.php" aria-current="page">Log In</a></nav></div></footer>
</body>
</html>
