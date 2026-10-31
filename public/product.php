


<?php
require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/models/Dress.php';

$dress = null;
$dressImages = [];

if (
    ($_GET['category'] ?? '') === 'dresses'
    && isset($_GET['id'])
    && ctype_digit((string) $_GET['id'])
) {
    $database = new Database();
    $db = $database->connect();

    $dressModel = new Dress($db);

    $dress = $dressModel->getById((int) $_GET['id']);

    if ($dress) {
        $dressImages = $dressModel->getImages((int) $dress['id']);
    }
}
?>








<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Product Details | ÉLAN BRIDAL</title>
  <meta name="description" content="Discover considered wedding looks from the Élan Bridal collection.">
  <meta name="theme-color" content="#FAF8F5">
  <meta name="color-scheme" content="light">
  <link rel="icon" href="assets/icons/favicon.svg" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/collections.js" defer></script>
  <script src="assets/js/main.js?v=<?php echo filemtime(__DIR__ . '/assets/js/main.js'); ?>" defer></script>
  <script src="assets/js/cart.js" defer></script>
  <?php if ($dress): ?>
<script>
    window.ELAN_DB_DRESS = <?= json_encode(
        [
            'id' => (int) $dress['id'],
            'name' => $dress['product_name'],
            'description' => $dress['description'],
            'price' => (float) $dress['price'],
            'image' => $dress['image_url'],
            'images' => $dressImages
        ],
        JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
    ) ?>;
</script>
<?php endif; ?>
  <script src="assets/js/product-details.js" defer></script>
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>
  <header class="site-header site-header--solid" id="site-header">
    <section class="announcement" aria-label="Announcement"><p class="announcement__text"><span class="announcement__long">The Art of Your Forever &mdash; Discover the New Wedding Collection</span><span class="announcement__short">The Art of Your Forever</span></p></section>
    <div class="navbar"><div class="navbar__inner container">
      <a class="brand" href="index.php" aria-label="Élan Bridal — home"><span class="brand__primary">Élan</span><span class="brand__secondary">Bridal</span></a>
      <nav class="primary-nav" aria-label="Primary"><ul class="primary-nav__list">
        <li><a class="primary-nav__link" href="index.php">Home</a></li><li><a class="primary-nav__link" href="dresses.php">Dress</a></li><li><a class="primary-nav__link" href="suits.php">Suits</a></li><li><a class="primary-nav__link" href="accessories.php">Accessories</a></li><li><a class="primary-nav__link" href="index.php#about">About Us</a></li><li><a class="primary-nav__link" href="register.php">Register</a></li><li><a class="primary-nav__link" href="login.php">Log In</a></li><li><a class="primary-nav__link" href="cart.php">Bag <span data-cart-count>0</span></a></li>
      </ul></nav>
      <button class="menu-toggle" type="button" id="menu-toggle" aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu"><span class="menu-toggle__bars" aria-hidden="true"><span></span><span></span></span></button>
    </div></div>
    <div class="mobile-menu" id="mobile-menu" aria-hidden="true" inert><nav class="mobile-menu__inner container" aria-label="Mobile"><p class="mobile-menu__eyebrow">Explore Élan Bridal</p><ul class="mobile-menu__list">
      <li><a class="mobile-menu__link" href="index.php"><span>01</span>Home</a></li><li><a class="mobile-menu__link" href="dresses.php"><span>02</span>Dress</a></li><li><a class="mobile-menu__link" href="suits.php"><span>03</span>Suits</a></li><li><a class="mobile-menu__link" href="accessories.php"><span>04</span>Accessories</a></li><li><a class="mobile-menu__link" href="index.php#about"><span>05</span>About Us</a></li><li><a class="mobile-menu__link" href="register.php"><span>06</span>Register</a></li><li><a class="mobile-menu__link" href="login.php"><span>07</span>Log In</a></li><li><a class="mobile-menu__link" href="cart.php"><span>08</span>Bag <span data-cart-count>0</span></a></li>
    </ul><div class="mobile-menu__footer"><p>The Art of Forever &mdash; Collection 2026</p></div></nav></div>
  </header>
  <main id="main" class="detail-main" tabindex="-1">
    <div class="container">
      <nav class="detail-breadcrumb" aria-label="Breadcrumb"><a href="index.php">Home</a><span aria-hidden="true">/</span><a id="detail-collection-link" href="suits.php">Suits</a><span aria-hidden="true">/</span><span id="detail-breadcrumb-name" aria-current="page">Product</span></nav>
      <div class="detail-layout" id="product-details" hidden>
        <section class="detail-gallery" aria-label="Product gallery">
          <figure class="detail-gallery__main"><img id="detail-primary-image" src="" alt="" width="900" height="1200" fetchpriority="high"><span class="detail-gallery__label" id="detail-image-label">Full look</span></figure>
          <div class="detail-thumbnails" role="group" aria-label="Choose a view">
            <button class="detail-thumbnail is-selected" type="button" data-gallery-view="0" aria-pressed="true" aria-label="Full product photograph"><img src="" alt="" data-thumb-image="0"></button>
            <button class="detail-thumbnail" type="button" data-gallery-view="1" aria-pressed="false" aria-label="Upper silhouette crop from the product photograph"><img src="" alt="" data-thumb-image="1"></button>
            <button class="detail-thumbnail" type="button" data-gallery-view="2" aria-pressed="false" aria-label="Bodice or jacket detail crop from the product photograph"><img src="" alt="" data-thumb-image="2"></button>
            <button class="detail-thumbnail" type="button" data-gallery-view="3" aria-pressed="false" aria-label="Lower silhouette crop from the product photograph"><img src="" alt="" data-thumb-image="3"></button>
          </div>
          <p class="detail-gallery__note">Alternate gallery views are crops from the available campaign photograph.</p>
        </section>
        <section class="detail-info" aria-labelledby="detail-name">
          <p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span><span id="detail-category">Élan Bridal Collection</span></p>
          <h1 class="detail-name" id="detail-name"></h1>
          <p class="detail-price" id="detail-price">Price available on request</p>
          <p class="detail-description" id="detail-description"></p>
          <p class="detail-data-note">Sizing and colour choices are sample selections for this frontend preview; fit, inventory, and pricing have not been supplied.</p>
          <form class="detail-form" id="detail-form" novalidate>
            <fieldset class="detail-option"><legend>Size <span>*</span></legend><div class="detail-size-options" id="size-options"></div><p class="detail-error" id="size-error" aria-live="polite"></p></fieldset>
            <fieldset class="detail-option"><legend>Colour <span>*</span><small id="selected-color-label">Choose a colour</small></legend><div class="detail-color-options" id="color-options"></div><p class="detail-error" id="color-error" aria-live="polite"></p></fieldset>
            <div class="detail-quantity"><label for="quantity-value">Quantity</label><div class="quantity-control"><button type="button" id="quantity-minus" aria-label="Decrease quantity">&minus;</button><output id="quantity-value" aria-live="polite">1</output><button type="button" id="quantity-plus" aria-label="Increase quantity">+</button></div></div>
            <p class="detail-error detail-error--form" id="detail-form-error" role="alert"></p>
            <button class="btn detail-add-button" type="submit">Add to Cart</button>
            <p class="detail-cart-status" id="detail-cart-status" role="status" aria-live="polite"></p>
          </form>
          <a class="text-link detail-back-link" id="detail-back-link" href="suits.php">Back to the collection <span aria-hidden="true">&rarr;</span></a>
        </section>
      </div>
      <section class="detail-missing" id="product-missing" hidden><p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span>Collection detail</p><h1 class="detail-name">This look could not be found.</h1><p>Choose a look from one of our current collections to see its details.</p><a class="btn detail-add-button" href="suits.php">Explore the suit collection</a><a class="text-link" href="dresses.php">Explore the dress collection</a></section>
    </div>
  </main>
  <footer class="site-footer auth-footer" id="footer"><div class="container auth-footer__inner"><a class="brand brand--footer" href="index.php" aria-label="Élan Bridal — home"><span class="brand__primary">Élan</span><span class="brand__secondary">Bridal</span></a><p class="footer__copyright">&copy; <span id="year">2026</span> Élan Bridal. A frontend demonstration.</p><nav aria-label="Account"><a href="cart.php">Bag <span data-cart-count>0</span></a><a href="index.php">Home</a></nav></div></footer>
</body>
</html>
