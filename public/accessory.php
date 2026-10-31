<?php
// =====================================================
// One accessory (store)
// Opened when the user clicks an item on the accessories page:
// accessory.php?id=22
// Shows the product's photo, details and sizes (for the shoes).
// =====================================================

// STEP 1: Load the files we need
require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/models/accessories.php';


// STEP 2: Connect to the database and create the model
$database = new Database();
$db = $database->connect();

$accessoriesModel = new Accessories($db);


// STEP 3: Which section of the accessories page each subcategory belongs to
// (used for the "Back" link, so it goes back to the right section)
$sections = [
    "groom-shoes"    => [15, 16, 17, 18],
    "bow-ties"       => [21],
    "neck-ties"      => [22],
    "cuff-links"     => [26],
    "pocket-squares" => [23],
    "bridal-shoes"   => [19, 20, 24, 25],
    "bridal-jewelry" => [7],
    "bridal-gloves"  => [6],
    "tiaras-crowns"  => [8],
    "bridal-veils"   => [9],
];


// STEP 4: Find the product from the id in the URL
$id = 0;
if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
}

$product = $accessoriesModel->getById($id);

// Find the product's section (only accessories have one)
$sectionId = "";
if ($product !== null) {
    foreach ($sections as $section => $subcategoryIds) {
        if (in_array($product["subcategory_id"], $subcategoryIds)) {
            $sectionId = $section;
        }
    }
}

// Not found, or not an accessory (a dress or a suit) -> show the "not found" message
$found = ($product !== null && $sectionId !== "");


// STEP 5: Get the sizes (only the shoes have sizes)
$sizes = [];
if ($found) {
    $allSizes = $accessoriesModel->getSizes([$product["id"]]);
    $sizes = $allSizes[$product["id"]] ?? [];
}


// =====================================================
// Helper functions
// =====================================================

// Makes text safe to print in HTML
function safe($text)
{
    if ($text === null) {
        return "";
    }
    return htmlspecialchars($text, ENT_QUOTES, "UTF-8");
}

// Returns the photo path if the file really exists, otherwise ""
function productImage($imageUrl)
{
    if ($imageUrl === null || $imageUrl === "") {
        return "";
    }

    if (file_exists(__DIR__ . "/" . $imageUrl)) {
        return $imageUrl;
    }

    return "";
}

if ($found) {
    $image = productImage($product["image_url"]);
    $price = number_format($product["price"], 2) . " JOD";
    $pageTitle = $product["product_name"];
    $backLink = "accessories.php#" . $sectionId;
} else {
    $pageTitle = "Accessory not found";
    $backLink = "accessories.php";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo safe($pageTitle); ?> | ÉLAN BRIDAL</title>
  <meta name="description" content="Wedding accessories from the Élan Bridal collection.">
  <meta name="theme-color" content="#FAF8F5">
  <meta name="color-scheme" content="light">
  <link rel="icon" href="assets/icons/favicon.svg" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/accessories.css?v=<?php echo filemtime(__DIR__ . '/assets/css/accessories.css'); ?>">
  <script src="assets/js/accessories.js?v=<?php echo filemtime(__DIR__ . '/assets/js/accessories.js'); ?>" defer></script>
  <script src="assets/js/main.js?v=<?php echo filemtime(__DIR__ . '/assets/js/main.js'); ?>" defer></script>
  <script src="assets/js/cart.js" defer></script>
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
      <nav class="detail-breadcrumb" aria-label="Breadcrumb">
        <a href="index.php">Home</a><span aria-hidden="true">/</span>
        <a href="<?php echo safe($backLink); ?>">Accessories</a><span aria-hidden="true">/</span>
        <span aria-current="page"><?php echo safe($pageTitle); ?></span>
      </nav>

      <?php if ($found) { ?>
        <!-- ============ PRODUCT ============ -->
        <div class="detail-layout">
          <section class="detail-gallery" aria-label="Product photo">
            <figure class="detail-gallery__main">
              <?php if ($image !== "") { ?>
                <img src="<?php echo safe($image); ?>" alt="<?php echo safe($product["product_name"]); ?>" width="900" height="1200" fetchpriority="high">
              <?php } else { ?>
                <span class="acc-no-photo">Photo coming soon</span>
              <?php } ?>
            </figure>
          </section>

          <section class="detail-info" aria-labelledby="detail-name">
            <p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span><?php echo safe($product["subcategory_name"]); ?></p>
            <h1 class="detail-name" id="detail-name"><?php echo safe($product["product_name"]); ?></h1>
            <p class="detail-price"><?php echo $price; ?></p>
            <p class="detail-description"><?php echo safe($product["description"]); ?></p>

            <!-- accessories.js reads the data-... values and the chosen size, then puts the product in the bag -->
            <div class="detail-form" data-product>

              <!-- Sizes: one button for each size in the product_variants table -->
              <?php if (count($sizes) > 0) { ?>
                <fieldset class="detail-option" data-size-options>
                  <legend>Size <span>*</span></legend>
                  <div class="detail-size-options">
                    <?php foreach ($sizes as $size) { ?>
                      <?php $inputId = "size-" . safe($size["size"]); ?>
                      <input class="acc-size-radio" type="radio" name="size" id="<?php echo $inputId; ?>"
                             value="<?php echo safe($size["size"]); ?>"
                             <?php if ($size["stock_quantity"] <= 0) { echo "disabled"; } ?>>
                      <label class="detail-size-button acc-size-label" for="<?php echo $inputId; ?>">
                        <?php echo safe($size["size"]); ?>
                        <?php if ($size["stock_quantity"] <= 0) { ?><span class="visually-hidden">(sold out)</span><?php } ?>
                      </label>
                    <?php } ?>
                  </div>
                  <p class="detail-error" data-size-error role="alert"></p>
                </fieldset>
              <?php } ?>

              <?php if ($product["stock_quantity"] > 0) { ?>
                <button type="button" class="btn detail-add-button" data-add-to-bag
                        data-id="<?php echo $product["id"]; ?>"
                        data-name="<?php echo safe($product["product_name"]); ?>"
                        data-price="<?php echo $product["price"]; ?>"
                        data-image="<?php echo safe($image); ?>">
                  Add to Bag
                </button>
              <?php } else { ?>
                <button type="button" class="btn detail-add-button" disabled>Sold Out</button>
              <?php } ?>

              <p class="detail-cart-status" id="acc-bag-status" role="status" aria-live="polite"></p>
            </div>

            <a class="text-link detail-back-link" href="<?php echo safe($backLink); ?>">Back to accessories <span aria-hidden="true">&rarr;</span></a>
          </section>
        </div>
      <?php } else { ?>
        <!-- ============ NOT FOUND ============ -->
        <section class="detail-missing">
          <p class="eyebrow"><span class="eyebrow__line" aria-hidden="true"></span>Accessories</p>
          <h1 class="detail-name">This accessory could not be found.</h1>
          <p>Choose a piece from our accessories collection to see its details.</p>
          <a class="btn detail-add-button" href="accessories.php">Explore the accessories</a>
        </section>
      <?php } ?>
    </div>
  </main>

  <footer class="site-footer auth-footer" id="footer"><div class="container auth-footer__inner"><a class="brand brand--footer" href="index.php" aria-label="Élan Bridal — home"><span class="brand__primary">Élan</span><span class="brand__secondary">Bridal</span></a><p class="footer__copyright">&copy; <span id="year">2026</span> Élan Bridal. A frontend demonstration.</p><nav aria-label="Account"><a href="cart.php">Bag <span data-cart-count>0</span></a><a href="index.php">Home</a></nav></div></footer>
</body>
</html>
