# ÉLAN BRIDAL — Wedding Fashion Site

A premium, editorial wedding-fashion site for a fictional atelier: a cinematic landing page plus three dedicated collection pages for men's wedding suits, wedding dresses, and wedding accessories. Built with semantic HTML5, custom CSS3, and vanilla JavaScript — no frameworks, build step, or backend required.

## Run locally

Open `index.php` directly in a browser, or serve the folder with any static server:

```bash
# Option 1 — Node (no install required)
npx serve .

# Option 2 — VS Code
# Install the "Live Server" extension and choose "Open with Live Server"
```

Then visit the URL printed by the server (or open `index.php` directly). The collection pages are at `suits.php`, `dresses.php`, and `accessories.php`.

> Google Fonts are loaded from the network. Without a connection, the pages fall back to Georgia and the system UI font; all layout and interactions still work.

## Files

```
index.php                  Landing page: hero film, featured dress/suit/accessory sections, about, newsletter
suits.php                  Men's wedding suits collection, five categories
dresses.php                Wedding dress collection, five silhouettes
accessories.php            Wedding accessories for the groom and bride, nine categories
product.php                Shared detail page for suit and dress products
cart.php                   Frontend-only shopping bag
checkout.php               Delivery and payment method checkout preview
js/product-details.js       Product lookup, gallery, variant selection, quantity, and cart actions
js/cart.js                  Local browser cart with per-variant merging and navigation count
js/cart-page.js             Shopping bag renderer
js/checkout.js              Address validation, payment selection, and live order summary
register.php               Frontend-only account registration form
login.php                  Frontend-only account login form
css/style.css               Design system, landing sections, collection lookbooks, accessories, responsive + reduced motion
js/main.js                  Navigation, hero film, scroll reveals, dialog, newsletter validation, active nav
js/auth.js                  Account form validation and demo responses; credentials are not stored or sent
js/collections.js           Suit/dress product data, shared look-card renderer, view toggles, category scroll-spy
js/accessories.js           Accessories data, category/product card renderers, accessories category scroll-spy
assets/videos/              Optimized hero campaign film (desktop + mobile crops)
assets/images/              Locally hosted editorial photography, collection lookbooks, accessories, placeholders
assets/icons/favicon.svg    Élan Bridal monogram favicon
README.md                   This file
CREDITS.md                  Image sources, placeholder notes, and licensing
```

## Pages

### `index.php` — landing page

The original editorial landing page is preserved: transparent navigation over the campaign film, featured dress and suit collections, accessories, about section, footer newsletter, information dialog, and back-to-top control. Collection links in the header and footer now point to the two dedicated pages, while the hero CTAs and the featured sections still work as in-page anchors.

### `suits.php` — men's wedding suits

Five categories in this exact order:

1. Tuxedo Suits
2. Three-Piece Suits
3. Regular Fit Suits
4. Slim Fit Suits
5. Double-Breasted Suits

Each category has its own editorial heading, description, and a four-look grid. Three-piece looks are presented as complete jacket, waistcoat, and trouser outfits; the copy never implies a waistcoat in the two-piece categories.

### `dresses.php` — wedding dresses

Five silhouettes in this exact order:

1. Princess
2. Mermaid
3. Royal
4. A-Line
5. Sheath

Each silhouette has its own editorial heading, description, and a four-gown grid.

### `accessories.php` — wedding accessories

A shared page for both sides of the wedding party, split into two editorial sections:

1. **Suit Accessories** — Wedding Shoes (Derby, Loafers, Monk Strap, Oxford subcategory cards plus a product grid), Bow Ties, Neck Ties, and Pocket Squares.
2. **Dress Accessories** — Bridal Shoes (Block Heels, Bridal Sandals, Flats, High Heels subcategory cards plus a product grid), Jewelry, Bridal Gloves, Tiaras & Crowns, and Veils.

The page opens with a centred ivory intro and a sticky three-link category bar: All Accessories, Suit Accessories, and Dress Accessories. All three links smooth-scroll, expose an active bronze indicator, and remain horizontally scrollable on narrow screens without causing page overflow. Every required section ID (`suit-accessories`, `groom-shoes`, `bow-ties`, `neck-ties`, `pocket-squares`, `dress-accessories`, `bridal-shoes`, `bridal-jewelry`, `bridal-gloves`, `tiaras-crowns`, `bridal-veils`) is present in the static HTML so deep links work with or without JavaScript.

## Design system

- **Palette:** warm ivory `#FAF8F5`, champagne `#F1ECE6`, charcoal `#24211F`, taupe `#77716B`, bronze `#9C795D`, espresso `#211D1B`, border `#E6DED5`.
- **Type:** Cormorant Garamond for display typography, Inter for interface and body copy. Fluid sizing via `clamp()`.
- **Layout:** CSS Grid and Flexbox, a 1320px maximum content width, fluid gutters, and full-bleed editorial imagery.
- **Shared components:** `.site-header`, `.site-footer`, `.section-head`, `.btn`, `.text-link`, `.dialog`, `.reveal`, and the `--nav-h` / `--gutter` / `--section-y` tokens are reused across all four pages.
- **Collection cards:** a dedicated `.look-card` system layered on the existing tokens. It is separate from the landing page's `.product-card` so the older hover zoom cannot conflict with the front/back crossfade.
- **Accessories cards:** `.acc-cat-card` and `.acc-product` reuse the same type scale, bronze accents, and `text-link` action style. Both keep a stable `4 / 5` media box with `object-fit: cover`, so lazy images cannot shift the layout.

## Collection lookbook

### Responsive grids

- **≥1024px:** four columns.
- **640–1023px:** two columns.
- **<640px:** one column.

Cards keep a portrait `3 / 4` media box with `object-fit: cover` and a tuned `object-position`, so the image does not resize or jump between views. Verified at 1440, 1024, 768, 390, and 320px with no horizontal page overflow.

### Card interaction

- **Desktop pointer:** hovering the image crossfades from the primary view (complete suit or dress front) to the alternate view.
- **All devices:** a visible toggle button switches between views. It updates `aria-pressed`, its visible label, and its accessible name. The same explicit state works with touch and keyboard.
- **No hover dependency:** once a visitor uses the toggle, `data-touched` prevents pointer hover from contradicting the chosen state.
- **Motion:** the crossfade runs for 550ms and respects `prefers-reduced-motion`.
- **Keyboard:** the toggle is a native button, reachable in normal tab order and activated with `Enter` or `Space`.

The category bar sticks below the fixed header, tracks the section in view, and updates `aria-current`. In-page scrolling accounts for the navbar, announcement bar, and sticky category bar.

### Product data

All 40 look records live in `js/collections.js` under `COLLECTIONS`. Each record contains:

```js
{
  slug: "tuxedo-midnight",
  name: "The Midnight Tuxedo",
  desc: "Black-tie sophistication with a clean, ceremonial line.",
  secondaryReady: false
}
```

The shared renderer builds every card from the same template. Asset paths are derived from the collection configuration:

- Dresses: `assets/images/dresses/<slug>-front.jpg` and `<slug>-back.jpg`
- Suits: `assets/images/suits/<slug>-complete.jpg` and `<slug>-pieces.jpg`

To add a look, add a record to the relevant category and supply both images at those paths. To make an alternate view permanent, replace the placeholder and set `secondaryReady: true` on that record.

## Accessories lookbook

### Responsive grids

- **≥1024px:** four columns for subcategory cards and product cards.
- **640–1023px:** two columns.
- **<640px:** one product column; subcategory cards remain two-up so the four shoe styles stay scannable.

Every media box uses a fixed `4 / 5` ratio, `object-fit: cover`, and a tuned `object-position`. Verified at 1440, 1024, 768, 390, and 320px with no horizontal page overflow; at 320px the three-link category bar scrolls inside its own track.

### Product data

All 36 accessory records and 8 subcategory cards live in `js/accessories.js`. Product records are shaped for a future backend:

```js
{
  id: "groom-derby-heritage",
  name: "The Heritage Derby",
  category: "Suit Accessories",
  subcategory: "groom-shoes",
  description: "A burnished leather Derby with open lacing and a softly rounded toe.",
  image: "suits/shoes/derby-heritage.jpg",
  hoverImage: null,
  price: null,
  productUrl: null
}
```

`image` and `hoverImage` are relative to `assets/images/accessories/`, which is grouped as `suits/{shoes,bow-ties,neck-ties,pocket-squares}/` and `dresses/{shoes,jewelry,gloves,tiaras-crowns,veils}/`. `price` and `productUrl` are deliberately `null`: the renderer omits the price line when it is empty and the action link opens the shared information dialog instead of a fabricated store page. Swap in a real price or URL later and the card markup accepts it without restructuring.

`js/accessories.js` must load before `js/main.js`; it renders the cards first, then `main.js` attaches scroll reveals and dialog handling. `js/collections.js` is not loaded on this page, so its lookbook scroll-spy cannot conflict with the accessory category bar.

### `register.php` and `login.php` — account form demonstrations

These responsive pages reuse the Élan navigation, palette, display and body fonts, and locally hosted editorial photography. The forms validate fields in the browser, but there is no account service, database, or API connected. Submitting valid details shows an explicit demonstration message; no credentials or form values are stored or transmitted. Password reset is unavailable until an authentication service is added.

### `product.php`, `cart.php`, and `checkout.php` — product, bag, and checkout demonstrations

Suit and dress lookbook cards link to one shared product page using `category` and `slug` query parameters. The page reads the existing records in `js/collections.js`. The catalog has no prices, verified size or colour variants, or inventory, so price is shown as available on request and selection choices are marked as preview options. The cart is frontend-only and persists non-sensitive item selections in local storage.

The checkout reads and updates the same cart, validates a manually entered delivery address, and offers Cash on Delivery and Visa as selections. There is no order API or payment gateway: placing an order returns an explicit not-connected message, preserves the cart, and never collects card details. Prices and delivery rates are missing, so totals remain to be confirmed; JOD is the display currency when numeric prices are supplied.

## Interactions

- Solid ivory navigation on the collection pages; the landing page keeps its transparent-to-solid scroll behavior.
- Accessible mobile menu with focus trapping, `Escape` to close, scroll lock, and an `inert` closed state.
- Cinematic hero film on the landing page only; collection pages use still photography.
- Scroll-spy active states in the desktop and mobile navigation, including the three collection-page links.
- Accessories category bar with its own scroll-spy: All Accessories, then Suit Accessories, then Dress Accessories, with `aria-current` updated and all three anchors scrollable and keyboard-operable.
- Suit and dress product links open the shared product detail page; the bag count follows local cart contents.
- Checkout validation and summary are frontend-only; no order or payment is submitted.
- Newsletter demo with inline validation and an honest success message — no data is stored or sent.
- Back-to-top control, smooth in-page scrolling, dynamic copyright year.
- IntersectionObserver scroll reveals that reveal content immediately when reduced motion is requested. Content and section headings remain present if JavaScript is unavailable.

## No-JavaScript behavior

The look cards are rendered from the shared data module, so the interactive grids require JavaScript. Every collection page therefore includes:

- A visible `<noscript>` notice explaining that the interactive lookbook is unavailable.
- A per-category `<noscript>` list of every look, linked directly to its primary photograph.
- On the accessories page, nine `<noscript>` product lists (four links each) plus the static category headings and section anchors.
- Static hero copy, category headings, descriptions, navigation, footer, and newsletter markup that remain readable without JavaScript.

## Placeholders and limitations

This is a **frontend demonstration**, not a shop:

- There is no order processing, payment gateway, authentication backend, inventory, stock information, reviews, or commercial availability. Product details, cart, and checkout are frontend demonstrations; cart contents persist only in the current browser.
- The registration and login forms are frontend demonstrations only. A production authentication service, secure account storage, session handling, and password-reset flow still need to be integrated.
- No verified same-dress front/back pairs or same-suit pieces photography were available. Every secondary card state is a clearly labelled detail-study placeholder generated from the primary photograph. The placeholder image itself reads “Back view / Photography to follow” or “Suit pieces / Piece photography to follow”; the card tag reads “Detail study”, and the card note says the secondary photography is still to follow. These files are never presented as matching views or real garment pieces.
- Product links, About CTA, social icons, and customer-care links open a clearly labelled in-page dialog explaining that the destination is not implemented.
- Accessory product names, descriptions, and prices are editorial examples only. Prices are `null`, so no price is displayed and no stock, review, or inventory claim is made.
- Accessory photography was matched to categories from Pexels' published descriptions and checked for file type and intrinsic dimensions. The build environment could not display images for a visual content review, so a manual visual pass is recommended before production use; every source is listed in `CREDITS.md`.
- The newsletter is a demonstration only; nothing is transmitted or stored.
- Open Graph image paths are relative. Once a production domain exists, replace them with absolute URLs and add `og:url` plus canonical links.
- The brand, products, campaign copy, and collection names are fictional.
- The landing-page hero film is licensed stock footage (Pexels), not original campaign footage.

## Replacing imagery and video

### Landing-page imagery and film

All landing images live in `assets/images/` and are referenced by descriptive filenames. Replace a file with a new image of the same filename, or update the `src` in `index.php`. Keep the `width`/`height` attributes in sync to avoid layout shifts.

The hero uses two optimized video sources plus a poster:

- `assets/videos/wedding-hero.mp4` — desktop/tablet, 1920×1080, used at 768px and wider.
- `assets/videos/wedding-hero-mobile.mp4` — portrait crop, 720×1280, used below 768px.
- `assets/images/hero-poster.jpg` — poster shown while loading, on failure, and under reduced motion.

The `media` attributes on the `<source>` elements select the correct file, and the selection is refreshed if the viewport crosses the 768px breakpoint. Keep the MP4/H.264 format, strip audio, and retain `+faststart` for progressive playback.

### Collection primary photography

Replace the file at the path documented in `js/collections.js`, or add a new record with matching filenames. Primary images are authored at 900×1200 (3:4). Keep `width="900" height="1200"` in the renderer to reserve space.

### Replacing a detail-study placeholder

A genuine secondary image must show the **same garment** as the primary:

- **Dress back view:** same dress, colour, fabric, silhouette, lace, bodice, train, model, and setting.
- **Suit pieces:** jacket, trousers, optional waistcoat, shirt, tie or bow tie, and pocket square that belong to the same suit.

Save the image at the existing `-back.jpg` or `-pieces.jpg` path, then set `secondaryReady: true` on that product. The card switches its labels from “View detail / View full look” to “View back / View front” or “View pieces / View complete”, and the “Detail study” tag and note disappear. If the replacement is not a verified match, leave `secondaryReady: false` and keep the labelled placeholder.

### Accessories photography

Accessory images live under `assets/images/accessories/` in the folder structure described above. Each record in `js/accessories.js` carries its own `width` and `height`, which are written into the rendered `<img>` tags to reserve space. To replace an image, keep the same filename and update those numbers if the replacement has a different ratio. To add a product, add a record to `PRODUCTS` with a unique `subcategory` matching one of the nine section IDs and supply the file at the documented path.

The subcategory cards are edited in `CATEGORY_CARDS` and only appear for the two shoe groups. Their “Explore Collection” links target `#groom-shoes-products` or `#bridal-shoes-products`, both of which exist in the static page.

## Testing

The pages were checked in Microsoft Edge via Playwright at 1440, 1024, 768, 390, and 320px. The checks covered:

- All 40 primary and 40 secondary image requests on the suit/dress pages, plus all 45 accessories images, with no broken images or 404s.
- Four-column, two-column, and one-column grids at the intended breakpoints, including the accessories category and product grids.
- No horizontal page overflow at any tested width; the accessories category bar scrolls internally at 320px.
- Hover zoom, focus states, and text-link underlines on accessory cards, with no hover-only information.
- Accessories category-nav clicks, hash updates, active state transitions (All → Suit → Dress), and heading clearance under the sticky bars.
- All 36 accessories action links open the shared dialog with the correct product title; all in-page hashes resolve.
- Hover crossfade, toggle state, `aria-pressed`, label and accessible-name updates.
- Keyboard activation with `Enter` and `Space`.
- Category-nav click, hash update, active state, and heading clearance under the sticky bars.
- Dialog open/close and newsletter validation.
- Mobile-menu open/close, focus return, and `inert` state.
- No-JavaScript fallback lists and visible static content.
- Reduced-motion rendering.
- Landing-page collection links and retained in-page anchors.

HTML validation for the collection pages passes with `html-validate`; the only landing-page finding is the intentional hero `<video autoplay>`.

## Image licensing

Photography and the hero film are sourced from Pexels under the [Pexels License](https://www.pexels.com/license/) and stored locally for stability. See `CREDITS.md` for photographer attribution, source links, the 45 accessories photographs, and the distinction between Pexels photography and generated detail-study placeholders. Attribution is not required by the license but is included as good practice.
