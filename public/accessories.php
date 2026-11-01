<?php
// =====================================================
// Accessories page (store)
// Gets the accessories from the database and shows them.
// =====================================================

// STEP 1: Load the files we need
require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/models/accessories.php';
 require_once 'session.php';

// STEP 2: Connect to the database and create the model
$database = new Database();
$db = $database->connect();

$accessoriesModel = new Accessories($db);


// STEP 3: Which subcategories (from the database) belong to each section of the page
// The key is the section's id on the page, the value is the list of subcategory ids
$sections = [
    "groom-shoes"    => [15, 16, 17, 18],   // Derby, Loafers, Monk Strap, Oxford
    "bow-ties"       => [21],
    "neck-ties"      => [22],
    "cuff-links"     => [26],
    "pocket-squares" => [23],
    "bridal-shoes"   => [19, 20, 24, 25],   // Bloch Heels, High Heels, Bridal Sandal, Flats
    "bridal-jewelry" => [7],
    "bridal-gloves"  => [6],
    "tiaras-crowns"  => [8],
    "bridal-veils"   => [9],
];


// STEP 4: Get the products for every section
// $products["bow-ties"] will be the list of bow ties, and so on
$products = [];

foreach ($sections as $sectionId => $subcategoryIds) {
    $products[$sectionId] = $accessoriesModel->getBySubcategories($subcategoryIds);
}


// STEP 5: Get the category cards for the two shoe sections
// (each card's photo is in the image_url column of the subcategories table)
$groomShoeTypes = $accessoriesModel->getSubcategories([15, 16, 17, 18]);
$bridalShoeTypes = $accessoriesModel->getSubcategories([19, 20, 24, 25]);


// =====================================================
// Helper functions (small functions we use in the HTML)
// =====================================================

// Makes text safe to print in HTML (stops <script> in a product name from running)
function safe($text)
{
    if ($text === null) {
        return "";
    }

    return htmlspecialchars($text, ENT_QUOTES, "UTF-8");
}

// Returns the image path if the photo really exists, or "" if it does not
// (some products have no photo yet, or still have an old broken path)
function productImage($imageUrl)
{
    if ($imageUrl === null || $imageUrl === "") {
        return "";
    }

    // The path is relative to the public folder, e.g. "assets/images/accessories/..."
    if (file_exists(__DIR__ . "/" . $imageUrl)) {
        return $imageUrl;
    }

    return "";
}

// The small arrow icon used in the "View details" and "Explore Collection" links
function arrowIcon()
{
    return '<svg class="icon" aria-hidden="true"><use href="#icon-arrow"></use></svg>';
}

// Prints the category cards at the top of a shoe section (Derby, Loafers...)
function printCategoryCards($shoeTypes, $sectionId)
{
    foreach ($shoeTypes as $type) {
        $image = productImage($type["image_url"]);
        ?>
        <a class="acc-cat-card" href="#<?php echo $sectionId; ?>-products" data-reveal
           aria-label="Explore <?php echo safe($type["name"]); ?>">
            <span class="acc-cat-card__visual">
                <?php if ($image !== "") { ?>
                    <img class="acc-cat-card__image" src="<?php echo safe($image); ?>"
                         alt="<?php echo safe($type["name"]); ?>" loading="lazy" decoding="async">
                <?php } else { ?>
                    <span class="acc-no-photo">Photo coming soon</span>
                <?php } ?>
            </span>
            <span class="acc-cat-card__body">
                <span class="acc-cat-card__name"><?php echo safe($type["name"]); ?></span>
                <span class="acc-cat-card__desc"><?php echo safe($type["description"]); ?></span>
                <span class="text-link text-link--sm acc-cat-card__link">
                    Explore Collection <?php echo arrowIcon(); ?>
                </span>
            </span>
        </a>
        <?php
    }
}

// Prints one card for each product in a section
function printProductCards($productList, $sectionId)
{
    // No products in this section yet
    if (count($productList) === 0) {
        echo '<p class="acc-empty">New pieces are coming soon.</p>';
        return;
    }

    $number = 1;   // the small 01, 02, 03... next to each product name

    foreach ($productList as $product) {
        $image = productImage($product["image_url"]);
        $price = number_format($product["price"], 2) . " JOD";

        // The "View details" message (product pages are not built yet)
        $dialog = $product["product_name"] . " is part of the Élan Bridal accessories collection. "
                . "Product detail pages are coming soon.";
        ?>
        <article class="acc-product" data-reveal>
            <figure class="acc-product__visual">
                <?php if ($image !== "") { ?>
                    <img class="acc-product__image" src="<?php echo safe($image); ?>"
                         alt="<?php echo safe($product["product_name"]); ?>" loading="lazy" decoding="async">
                <?php } else { ?>
                    <span class="acc-no-photo">Photo coming soon</span>
                <?php } ?>
            </figure>
            <div class="acc-product__body">
                <div class="acc-product__heading">
                    <h4 class="acc-product__name"><?php echo safe($product["product_name"]); ?></h4>
                    <span class="acc-product__index" aria-hidden="true"><?php echo str_pad($number, 2, "0", STR_PAD_LEFT); ?></span>
                </div>
                <p class="acc-product__desc"><?php echo safe($product["description"]); ?></p>
                <p class="acc-product__price"><?php echo $price; ?></p>
                <a class="text-link text-link--sm acc-product__link"
                   href="#<?php echo $sectionId; ?>"
                   data-placeholder="<?php echo safe($product["product_name"]); ?>"
                   data-dialog="<?php echo safe($dialog); ?>"
                   data-dialog-cta="Keep exploring"
                   data-dialog-target="#<?php echo $sectionId; ?>">
                    View details <?php echo arrowIcon(); ?>
                </a>
            </div>
        </article>
        <?php
        $number++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Wedding Accessories | ÉLAN BRIDAL</title>
  <meta name="description" content="Discover wedding accessories for the bride and groom — shoes, bow ties, neck ties, pocket squares, jewellery, gloves, tiaras, and veils — from Élan Bridal.">
  <meta name="theme-color" content="#FAF8F5">
  <meta name="color-scheme" content="light">

  <meta property="og:type" content="website">
  <meta property="og:site_name" content="ÉLAN BRIDAL">
  <meta property="og:title" content="Wedding Accessories | ÉLAN BRIDAL">
  <meta property="og:description" content="The finishing touch for the bride and groom: shoes, ties, pocket squares, jewellery, gloves, tiaras, and veils.">
  <meta property="og:image" content="assets/images/accessories/dresses/banner.jpg">
  <meta property="og:image:alt" content="A flat lay of bridal shoes, jewellery, and perfume.">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Wedding Accessories | ÉLAN BRIDAL">
  <meta name="twitter:description" content="The finishing touch for the bride and groom: shoes, ties, pocket squares, jewellery, gloves, tiaras, and veils.">
  <meta name="twitter:image" content="assets/images/accessories/dresses/banner.jpg">

  <link rel="icon" href="assets/icons/favicon.svg" type="image/svg+xml">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/accessories.css?v=<?php echo filemtime(__DIR__ . '/assets/css/accessories.css'); ?>">
  <script src="assets/js/accessories.js" defer></script>
  <script src="assets/js/main.js?v=<?php echo filemtime(__DIR__ . '/assets/js/main.js'); ?>" defer></script>
  <script src="assets/js/cart.js" defer></script>
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>

  <!-- Shared inline icon sprite -->
  <svg xmlns="http://www.w3.org/2000/svg" class="svg-sprite" aria-hidden="true" focusable="false">
    <symbol id="icon-arrow" viewBox="0 0 24 24">
      <path d="M4 12h15M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
    </symbol>
    <symbol id="icon-arrow-up" viewBox="0 0 24 24">
      <path d="M12 19V5M6 11l6-6 6 6" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
    </symbol>
    <symbol id="icon-close" viewBox="0 0 24 24">
      <path d="M6 6l12 12M18 6 6 18" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
    </symbol>
    <symbol id="icon-instagram" viewBox="0 0 24 24">
      <rect x="3.25" y="3.25" width="17.5" height="17.5" rx="5" fill="none" stroke="currentColor" stroke-width="1.4"/>
      <circle cx="12" cy="12" r="4.1" fill="none" stroke="currentColor" stroke-width="1.4"/>
      <circle cx="17.2" cy="6.8" r="1.1" fill="currentColor"/>
    </symbol>
    <symbol id="icon-pinterest" viewBox="0 0 24 24">
      <path d="M9.6 21.4c.5-1.9 1.2-4.8 1.5-6.2-1.6-1.9.2-4.7 2-4.7 1.4 0 2.1 1 2.1 2.3 0 1.4-.9 3.6-1.4 5.6-.4 1.5.8 2.8 2.3 2.8 2.7 0 4.5-3.5 4.5-7 0-3.6-3-6.4-6.8-6.4-4.5 0-7.2 3.4-7.2 6.9 0 1.4.5 2.7 1.2 3.4.1.1.2.3.1.5l-.4 1.5c-.1.4-.4.5-.7.3-1.9-.9-3-3.5-3-5.9C3.8 8 7.6 4 13.7 4c4.9 0 8.6 3.5 8.6 8.1 0 5.3-3 9.2-7.3 9.2-1.5 0-2.9-.8-3.4-1.8l-1 3.8c-.3 1-.9 2.1-1.5 2.9" fill="currentColor"/>
    </symbol>
    <symbol id="icon-facebook" viewBox="0 0 24 24">
      <path d="M13.7 21v-8h2.6l.4-3h-3V8.1c0-.9.3-1.5 1.6-1.5H17V4.1C16.7 4 15.7 4 14.6 4c-2.3 0-3.9 1.4-3.9 4v2H8v3h2.7v8h3Z" fill="currentColor"/>
    </symbol>
    <symbol id="icon-plus" viewBox="0 0 24 24">
      <path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
    </symbol>
    <symbol id="icon-check" viewBox="0 0 24 24">
      <path d="m4.5 12.5 4.5 4.5L19.5 6.5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
    </symbol>
  </svg>

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
            <li><a class="primary-nav__link is-active" href="accessories.php" aria-current="page">Accessories</a></li>
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
          <li><a class="mobile-menu__link is-active" href="accessories.php" aria-current="page"><span>04</span>Accessories</a></li>
          <li><a class="mobile-menu__link" href="index.php#about"><span>05</span>About Us</a></li>
          <li><a class="mobile-menu__link" href="register.php"><span>06</span>Register</a></li>
          <li><a class="mobile-menu__link" href="login.php"><span>07</span>Log In</a></li>
          <li><a class="mobile-menu__link" href="cart.php"><span>08</span>Bag <span data-cart-count>0</span></a></li>
        </ul>
        <div class="mobile-menu__footer">
          <p>The Art of Forever &mdash; Collection 2026</p>
          <div class="mobile-menu__socials">
            <button class="icon-button" type="button" data-placeholder="Instagram" data-dialog="Social profiles are not connected in this design demonstration." aria-label="Instagram (demonstration placeholder)">
              <svg class="icon" aria-hidden="true"><use href="#icon-instagram"></use></svg>
            </button>
            <button class="icon-button" type="button" data-placeholder="Pinterest" data-dialog="Social profiles are not connected in this design demonstration." aria-label="Pinterest (demonstration placeholder)">
              <svg class="icon" aria-hidden="true"><use href="#icon-pinterest"></use></svg>
            </button>
            <button class="icon-button" type="button" data-placeholder="Facebook" data-dialog="Social profiles are not connected in this design demonstration." aria-label="Facebook (demonstration placeholder)">
              <svg class="icon" aria-hidden="true"><use href="#icon-facebook"></use></svg>
            </button>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <main id="main" class="collection-main acc-main" tabindex="-1">
    <section class="acc-intro" id="accessories" aria-labelledby="accessories-title">
      <div class="container acc-intro__inner">
        <p class="eyebrow">
          <span class="eyebrow__line" aria-hidden="true"></span>
          The Finishing Touch
        </p>
        <h1 class="acc-intro__title" id="accessories-title">Complete Your <em>Wedding</em> Look</h1>
        <p class="acc-intro__text">Discover the finishing details that make every moment unforgettable, from impeccably selected groom accessories to exquisite bridal treasures.</p>
        <span class="acc-intro__rule" aria-hidden="true"></span>
      </div>
    </section>

    <nav class="collection-nav acc-nav" aria-label="Accessory categories">
      <div class="container collection-nav__inner">
        <ul class="collection-nav__list">
          <li><a class="collection-nav__link is-active" href="#accessories" aria-current="location">All Accessories</a></li>
          <li><a class="collection-nav__link" href="#suit-accessories">Suit Accessories</a></li>
          <li><a class="collection-nav__link" href="#dress-accessories">Dress Accessories</a></li>
        </ul>
      </div>
    </nav>

    <div class="acc-collection">
      <section class="acc-section acc-section--suit" id="suit-accessories" aria-labelledby="suit-accessories-title">
        <div class="container">
          <div class="section-head acc-section-head" data-reveal>
            <div class="section-head__intro">
              <p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span>For the Groom</p>
              <h2 class="section-title" id="suit-accessories-title">The Details of <em>Distinguished Style</em></h2>
            </div>
            <div class="section-head__aside">
              <p class="section-head__text">From perfectly polished footwear to refined finishing touches, discover accessories that bring the groom's wedding look together.</p>
            </div>
          </div>

          <article class="acc-block" id="groom-shoes" aria-labelledby="groom-shoes-title">
            <header class="acc-block__head" data-reveal>
              <p class="acc-block__index" aria-hidden="true">01</p>
              <h3 class="acc-block__title" id="groom-shoes-title">Wedding Shoes</h3>
              <p class="acc-block__text">Discover timeless footwear designed to complement formal tailoring and modern wedding suits.</p>
            </header>

            <div class="acc-cat-grid">
              <?php printCategoryCards($groomShoeTypes, "groom-shoes"); ?>
            </div>
            <div class="acc-products" id="groom-shoes-products">
              <?php printProductCards($products["groom-shoes"], "groom-shoes"); ?>
            </div>

          </article>

          <article class="acc-block" id="bow-ties" aria-labelledby="bow-ties-title">
            <header class="acc-block__head" data-reveal>
              <p class="acc-block__index" aria-hidden="true">02</p>
              <h3 class="acc-block__title" id="bow-ties-title">Bow Ties</h3>
              <p class="acc-block__text">A refined finishing touch for black-tie celebrations and sophisticated wedding looks.</p>
            </header>

            <div class="acc-products" id="bow-ties-products">
              <?php printProductCards($products["bow-ties"], "bow-ties"); ?>
            </div>

          </article>

          <article class="acc-block" id="neck-ties" aria-labelledby="neck-ties-title">
            <header class="acc-block__head" data-reveal>
              <p class="acc-block__index" aria-hidden="true">03</p>
              <h3 class="acc-block__title" id="neck-ties-title">Neck Ties</h3>
              <p class="acc-block__text">Explore elegant ties that bring color, texture, and personality to the groom's wedding ensemble.</p>
            </header>

            <div class="acc-products" id="neck-ties-products">
              <?php printProductCards($products["neck-ties"], "neck-ties"); ?>
            </div>

          </article>

          <article class="acc-block" id="cuff-links" aria-labelledby="cuff-links-title">
            <header class="acc-block__head" data-reveal>
              <p class="acc-block__index" aria-hidden="true">04</p>
              <h3 class="acc-block__title" id="cuff-links-title">Cuff Links</h3>
              <p class="acc-block__text">Finish the groom's shirt with a polished detail, from classic silver to understated gold.</p>
            </header>

            <div class="acc-products" id="cuff-links-products">
              <?php printProductCards($products["cuff-links"], "cuff-links"); ?>
            </div>

          </article>

          <article class="acc-block" id="pocket-squares" aria-labelledby="pocket-squares-title">
            <header class="acc-block__head" data-reveal>
              <p class="acc-block__index" aria-hidden="true">05</p>
              <h3 class="acc-block__title" id="pocket-squares-title">Pocket Squares</h3>
              <p class="acc-block__text">Small details make a lasting impression. Complete your look with carefully selected pocket squares.</p>
            </header>

            <div class="acc-products" id="pocket-squares-products">
              <?php printProductCards($products["pocket-squares"], "pocket-squares"); ?>
            </div>

          </article>
        </div>
      </section>

      <section class="acc-section acc-section--dress" id="dress-accessories" aria-labelledby="dress-accessories-title">
        <figure class="acc-banner">
          <img src="assets/images/accessories/dresses/banner.jpg"
               alt="A flat lay of ivory bridal shoes, pearl jewellery, and a perfume bottle on a soft neutral surface."
               width="1400" height="2100" loading="lazy" decoding="async">
          <figcaption class="acc-banner__caption">
            <span>Bridal Accessories</span>
            <span>The finishing layer</span>
          </figcaption>
        </figure>

        <div class="container">
          <div class="section-head acc-section-head" data-reveal>
            <div class="section-head__intro">
              <p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span>For the Bride</p>
              <h2 class="section-title" id="dress-accessories-title">Every Detail, <em>Beautifully Considered</em></h2>
            </div>
            <div class="section-head__aside">
              <p class="section-head__text">Discover the finishing touches that complete your bridal look, from elegant footwear and delicate jewelry to statement crowns and timeless veils.</p>
            </div>
          </div>

          <article class="acc-block" id="bridal-shoes" aria-labelledby="bridal-shoes-title">
            <header class="acc-block__head" data-reveal>
              <p class="acc-block__index" aria-hidden="true">01</p>
              <h3 class="acc-block__title" id="bridal-shoes-title">Bridal Shoes</h3>
              <p class="acc-block__text">Find the perfect balance of elegance, comfort, and sophistication for every moment of your celebration.</p>
            </header>

            <div class="acc-cat-grid">
              <?php printCategoryCards($bridalShoeTypes, "bridal-shoes"); ?>
            </div>
            <div class="acc-products" id="bridal-shoes-products">
              <?php printProductCards($products["bridal-shoes"], "bridal-shoes"); ?>
            </div>

          </article>

          <article class="acc-block" id="bridal-jewelry" aria-labelledby="bridal-jewelry-title">
            <header class="acc-block__head" data-reveal>
              <p class="acc-block__index" aria-hidden="true">02</p>
              <h3 class="acc-block__title" id="bridal-jewelry-title">Jewelry</h3>
              <p class="acc-block__text">Discover delicate and statement pieces that add a final touch of brilliance to your bridal look.</p>
            </header>

            <div class="acc-products" id="bridal-jewelry-products">
              <?php printProductCards($products["bridal-jewelry"], "bridal-jewelry"); ?>
            </div>

          </article>

          <article class="acc-block" id="bridal-gloves" aria-labelledby="bridal-gloves-title">
            <header class="acc-block__head" data-reveal>
              <p class="acc-block__index" aria-hidden="true">03</p>
              <h3 class="acc-block__title" id="bridal-gloves-title">Bridal Gloves</h3>
              <p class="acc-block__text">Add a touch of timeless romance with elegant bridal gloves, from delicate sheer designs to classic satin styles.</p>
            </header>

            <div class="acc-products" id="bridal-gloves-products">
              <?php printProductCards($products["bridal-gloves"], "bridal-gloves"); ?>
            </div>

          </article>

          <article class="acc-block" id="tiaras-crowns" aria-labelledby="tiaras-crowns-title">
            <header class="acc-block__head" data-reveal>
              <p class="acc-block__index" aria-hidden="true">04</p>
              <h3 class="acc-block__title" id="tiaras-crowns-title">Tiaras &amp; Crowns</h3>
              <p class="acc-block__text">Discover exquisite headpieces that bring a regal finishing touch to your wedding-day look.</p>
            </header>

            <div class="acc-products" id="tiaras-crowns-products">
              <?php printProductCards($products["tiaras-crowns"], "tiaras-crowns"); ?>
            </div>

          </article>

          <article class="acc-block" id="bridal-veils" aria-labelledby="bridal-veils-title">
            <header class="acc-block__head" data-reveal>
              <p class="acc-block__index" aria-hidden="true">05</p>
              <h3 class="acc-block__title" id="bridal-veils-title">Veils</h3>
              <p class="acc-block__text">From delicate fingertip veils to dramatic cathedral-length designs, discover the perfect finishing layer for your bridal look.</p>
            </header>

            <div class="acc-products" id="bridal-veils-products">
              <?php printProductCards($products["bridal-veils"], "bridal-veils"); ?>
            </div>

          </article>
        </div>
      </section>
    </div>

    <aside class="collection-note acc-note" aria-labelledby="acc-note-title">
      <div class="container collection-note__inner">
        <p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span>A Note on the Collection</p>
        <h2 class="collection-note__title" id="acc-note-title">The finishing touch, chosen with care.</h2>
        <p class="collection-note__text">Every accessory in our collection is selected to complete the look of our gowns and suits, from polished shoes and silk ties to delicate veils and heirloom-worthy jewelry. Prices are shown in Jordanian dinars, and new pieces are added throughout the season. For help pairing accessories with your dress or suit, visit our atelier and our stylists will guide you through every detail.</p>
      </div>
    </aside>
  </main>

  <footer class="site-footer" id="footer">
    <div class="container">
      <div class="footer__grid">
        <div class="footer__brand">
          <a class="brand brand--footer" href="index.php" aria-label="Élan Bridal — home">
            <span class="brand__primary">Élan</span>
            <span class="brand__secondary">Bridal</span>
          </a>
          <p class="footer__description">Exceptional wedding fashion for the moments that matter most.</p>
          <div class="footer__socials">
            <button class="icon-button icon-button--footer" type="button"
                    data-placeholder="Instagram"
                    data-dialog="Social profiles are not connected in this design demonstration. Join the atelier list below to stay in touch."
                    data-dialog-cta="Join the atelier list"
                    data-dialog-target="#newsletter" aria-label="Instagram (demonstration placeholder)">
              <svg class="icon" aria-hidden="true"><use href="#icon-instagram"></use></svg>
            </button>
            <button class="icon-button icon-button--footer" type="button"
                    data-placeholder="Pinterest"
                    data-dialog="Social profiles are not connected in this design demonstration. Join the atelier list below to stay in touch."
                    data-dialog-cta="Join the atelier list"
                    data-dialog-target="#newsletter" aria-label="Pinterest (demonstration placeholder)">
              <svg class="icon" aria-hidden="true"><use href="#icon-pinterest"></use></svg>
            </button>
            <button class="icon-button icon-button--footer" type="button"
                    data-placeholder="Facebook"
                    data-dialog="Social profiles are not connected in this design demonstration. Join the atelier list below to stay in touch."
                    data-dialog-cta="Join the atelier list"
                    data-dialog-target="#newsletter" aria-label="Facebook (demonstration placeholder)">
              <svg class="icon" aria-hidden="true"><use href="#icon-facebook"></use></svg>
            </button>
          </div>
        </div>

        <nav class="footer__col" aria-labelledby="footer-explore">
          <h2 class="footer__title" id="footer-explore">Explore</h2>
          <ul class="footer__list">
            <li><a href="index.php">Home</a></li>
            <li><a href="dresses.php">Wedding Dresses</a></li>
            <li><a href="suits.php">Men's Suits</a></li>
            <li><a href="accessories.php" aria-current="page">Accessories</a></li>
            <li><a href="index.php#about">About Us</a></li>
          </ul>
        </nav>

        <div class="footer__col">
          <h2 class="footer__title">Customer Care</h2>
          <ul class="footer__list">
            <li><button class="footer__link" type="button" data-placeholder="Contact Us"
                data-dialog="A contact page is not part of this frontend demonstration. In a production build, this would connect to the Élan Bridal client care team."
                data-dialog-cta="Join the atelier list" data-dialog-target="#newsletter">Contact Us</button></li>
            <li><button class="footer__link" type="button" data-placeholder="FAQs"
                data-dialog="The FAQ page is not implemented in this frontend demonstration. It would answer questions about collections, appointments, and ordering."
                data-dialog-cta="Join the atelier list" data-dialog-target="#newsletter">FAQs</button></li>
            <li><button class="footer__link" type="button" data-placeholder="Shipping &amp; Returns"
                data-dialog="Shipping and returns information is not implemented in this frontend demonstration. No orders or transactions can be placed on this page."
                data-dialog-cta="Join the atelier list" data-dialog-target="#newsletter">Shipping &amp; Returns</button></li>
            <li><button class="footer__link" type="button" data-placeholder="Size Guide"
                data-dialog="The size guide is not implemented in this frontend demonstration. A production build would include detailed measurement guidance for every collection."
                data-dialog-cta="Join the atelier list" data-dialog-target="#newsletter">Size Guide</button></li>
          </ul>
        </div>

        <div class="footer__col footer__col--newsletter" id="newsletter">
          <h2 class="footer__title">Stay Inspired</h2>
          <p class="footer__newsletter-text">Collection news and atelier notes, thoughtfully occasional.</p>
          <form class="newsletter" id="newsletter-form" action="#newsletter" method="get" novalidate>
            <label class="visually-hidden" for="newsletter-email">Email address</label>
            <div class="newsletter__field">
              <input class="newsletter__input" type="email" id="newsletter-email" name="email"
                     placeholder="Your email address" autocomplete="email" required
                     aria-describedby="newsletter-help newsletter-status">
              <button class="newsletter__submit" type="submit" aria-label="Subscribe to the atelier list">
                <svg class="icon" aria-hidden="true"><use href="#icon-arrow"></use></svg>
              </button>
            </div>
            <p class="newsletter__help" id="newsletter-help">Demonstration form &mdash; no emails are stored or sent.</p>
            <p class="newsletter__status" id="newsletter-status" role="status" aria-live="polite"></p>
          </form>
        </div>
      </div>

      <div class="footer__bottom">
        <p class="footer__copyright">&copy; <span id="year">2026</span> Élan Bridal. All rights reserved.</p>
        <p class="footer__demo">A frontend demonstration.</p>
        <ul class="footer__legal">
          <li><button class="footer__link" type="button" data-placeholder="Privacy Policy"
              data-dialog="A privacy policy page is not part of this frontend demonstration. This page does not collect, store, or transmit personal data."
              data-dialog-cta="Back to the top" data-dialog-target="#main">Privacy Policy</button></li>
          <li><button class="footer__link" type="button" data-placeholder="Terms &amp; Conditions"
              data-dialog="Terms and conditions are not part of this frontend demonstration. This page is a static design preview with no commercial transactions."
              data-dialog-cta="Back to the top" data-dialog-target="#main">Terms</button></li>
        </ul>
      </div>
    </div>
  </footer>

  <button class="back-to-top" id="back-to-top" type="button" aria-label="Back to top">
    <svg class="icon" aria-hidden="true"><use href="#icon-arrow-up"></use></svg>
  </button>

  <dialog class="dialog" id="placeholder-dialog" aria-labelledby="dialog-title" aria-describedby="dialog-body">
    <div class="dialog__inner">
      <button class="dialog__close" type="button" data-dialog-close aria-label="Close dialog">
        <svg class="icon" aria-hidden="true"><use href="#icon-close"></use></svg>
      </button>
      <p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span>Élan Bridal</p>
      <h2 class="dialog__title" id="dialog-title">A note from the atelier</h2>
      <p class="dialog__body" id="dialog-body">This is a frontend demonstration.</p>
      <div class="dialog__actions">
        <a class="btn btn--outline-dark dialog__cta" href="#suit-accessories" id="dialog-cta">
          <span id="dialog-cta-label">Explore the accessories</span>
          <svg class="icon" aria-hidden="true"><use href="#icon-arrow"></use></svg>
        </a>
        <button class="text-link dialog__dismiss" type="button" data-dialog-close>Close</button>
      </div>
      <p class="dialog__note">A design demonstration for Élan Bridal. No products are for sale and no personal data is collected.</p>
    </div>
  </dialog>
</body>
</html>
