/* ==========================================================================
   ÉLAN BRIDAL — Accessories page
   Centralised product data + renderers for the wedding accessories lookbook.
   Loaded before js/main.js so the shared scroll-reveal layer sees the cards.
   ========================================================================== */
(() => {
  "use strict";

  const doc = document;
  const $ = (selector, context = doc) => context.querySelector(selector);
  const $$ = (selector, context = doc) => Array.from(context.querySelectorAll(selector));

  const escapeHtml = (value) =>
    String(value)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");

  const ASSET_ROOT = "assets/images/accessories/";

  /* ----------------------------------------------------------------------
     Subcategory cards
     ----------------------------------------------------------------------
     Four cards sit above each shoes product grid. Every asset is stored
     locally under assets/images/accessories/{suits,dresses}/<group>/.
     ---------------------------------------------------------------------- */
  const CATEGORY_CARDS = {
    "groom-shoes": [
      {
        name: "Derby",
        desc: "Open lacing and a quietly rounded silhouette.",
        image: "suits/shoes/derby-category.jpg",
        alt: "Brown leather Derby brogues photographed on a neutral studio surface.",
        width: 1400,
        height: 2100,
      },
      {
        name: "Loafers",
        desc: "Sleek, laceless ease for long celebrations.",
        image: "suits/shoes/loafers-category.jpg",
        alt: "Black leather loafers with gold hardware.",
        width: 1400,
        height: 1750,
      },
      {
        name: "Monk Strap",
        desc: "A buckled profile with understated authority.",
        image: "suits/shoes/monk-strap-category.jpg",
        alt: "Brown monk strap shoes in studio lighting.",
        width: 1400,
        height: 1960,
      },
      {
        name: "Oxford",
        desc: "The formal benchmark, closed-laced and clean.",
        image: "suits/shoes/oxford-category.jpg",
        alt: "Brown Oxford shoes seen from above on dark leather.",
        width: 1400,
        height: 2100,
      },
    ],
    "bridal-shoes": [
      {
        name: "Block Heels",
        desc: "Grounded elegance from ceremony to last dance.",
        image: "dresses/shoes/block-heel-category.jpg",
        alt: "White block heels with ribbon straps on a wooden fence.",
        width: 1400,
        height: 2100,
      },
      {
        name: "Bridal Sandals",
        desc: "Delicate straps with a celebratory shimmer.",
        image: "dresses/shoes/bridal-sandal-category.jpg",
        alt: "Glittering bridal sandals photographed with a wedding dress behind.",
        width: 1400,
        height: 2100,
      },
      {
        name: "Flats",
        desc: "Soft, graceful comfort for every step.",
        image: "dresses/shoes/flat-category.jpg",
        alt: "White flat shoes resting on green foliage.",
        width: 1400,
        height: 1867,
      },
      {
        name: "High Heels",
        desc: "A poised heel with a romantic finish.",
        image: "dresses/shoes/high-heel-category.jpg",
        alt: "White bridal heels with glitter and bow detail.",
        width: 1400,
        height: 2100,
      },
    ],
  };

  /* ----------------------------------------------------------------------
     Product data
     ----------------------------------------------------------------------
     Shape prepared for a future backend:
       id, name, category, subcategory, description, image, hoverImage,
       price, productUrl
     `price` and `productUrl` stay null because this demonstration has no
     inventory, pricing, or product pages. No stock or reviews are claimed.
     ---------------------------------------------------------------------- */
  const PRODUCTS = [
    /* Suit accessories — shoes */
    {
      id: "groom-derby-heritage",
      name: "The Heritage Derby",
      category: "Suit Accessories",
      subcategory: "groom-shoes",
      description: "A burnished leather Derby with open lacing and a softly rounded toe.",
      image: "suits/shoes/derby-heritage.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 1867,
      alt: "Brown leather Derby shoes on a green surface.",
    },
    {
      id: "groom-loafer-cognac",
      name: "The Cognac Loafer",
      category: "Suit Accessories",
      subcategory: "groom-shoes",
      description: "A clean, laceless profile in warm cognac leather.",
      image: "suits/shoes/loafer-cognac.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 1750,
      alt: "Brown leather loafers against a dark backdrop.",
    },
    {
      id: "groom-monk-signature",
      name: "The Signature Monk Strap",
      category: "Suit Accessories",
      subcategory: "groom-shoes",
      description: "A single-buckle monk strap with a hand-finished patina.",
      image: "suits/shoes/monk-strap-signature.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 1994,
      alt: "Brown monk strap shoe worn with a green sock.",
    },
    {
      id: "groom-oxford-black",
      name: "The Black Oxford",
      category: "Suit Accessories",
      subcategory: "groom-shoes",
      description: "The formal standard: closed lacing, a sleek toe, and a mirror polish.",
      image: "suits/shoes/oxford-black.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A pair of black leather Oxford shoes on a white surface.",
    },

    /* Suit accessories — bow ties */
    {
      id: "bow-classic-black",
      name: "The Classic Black Bow Tie",
      category: "Suit Accessories",
      subcategory: "bow-ties",
      description: "A timeless black-tie finish with a crisp, symmetrical knot.",
      image: "suits/bow-ties/classic-black.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A dark bow tie on a wooden surface, shown in close-up.",
    },
    {
      id: "bow-ivory-ceremony",
      name: "The Ivory Ceremony Bow Tie",
      category: "Suit Accessories",
      subcategory: "bow-ties",
      description: "A soft ivory bow tie for daytime ceremonies and garden celebrations.",
      image: "suits/bow-ties/ivory-ceremony.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2097,
      alt: "A white bow tie and boutonniere on textured green fabric.",
    },
    {
      id: "bow-midnight",
      name: "The Midnight Bow Tie",
      category: "Suit Accessories",
      subcategory: "bow-ties",
      description: "Deep midnight tone, tied by hand for a considered finish.",
      image: "suits/bow-ties/midnight.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "Hands adjusting a black bow tie on a white dress shirt.",
    },
    {
      id: "bow-crimson",
      name: "The Crimson Bow Tie",
      category: "Suit Accessories",
      subcategory: "bow-ties",
      description: "A rich red accent for grooms who prefer a little colour.",
      image: "suits/bow-ties/crimson.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2097,
      alt: "A red bow tie and floral boutonniere on a blue suit.",
    },

    /* Suit accessories — neck ties */
    {
      id: "tie-polka-dot",
      name: "The Polka Dot Silk Tie",
      category: "Suit Accessories",
      subcategory: "neck-ties",
      description: "A classic spotted pattern that adds character without noise.",
      image: "suits/neck-ties/polka-dot.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 1867,
      alt: "A blue polka-dot tie worn with a striped shirt.",
    },
    {
      id: "tie-crimson",
      name: "The Crimson Silk Tie",
      category: "Suit Accessories",
      subcategory: "neck-ties",
      description: "A confident crimson tie with a clean, modern drape.",
      image: "suits/neck-ties/crimson.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A red neck tie worn with a crisp white shirt.",
    },
    {
      id: "tie-striped-silk",
      name: "The Striped Silk Tie",
      category: "Suit Accessories",
      subcategory: "neck-ties",
      description: "Fine diagonal stripes for a quietly traditional wedding look.",
      image: "suits/neck-ties/striped-silk.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 1568,
      alt: "A striped tie worn with a blue suit.",
    },
    {
      id: "tie-heirloom-collection",
      name: "The Heirloom Tie Collection",
      category: "Suit Accessories",
      subcategory: "neck-ties",
      description: "A curated set of silk ties, presented in a vintage keepsake box.",
      image: "suits/neck-ties/heirloom-collection.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2101,
      alt: "A collection of silk ties arranged in a vintage box.",
    },

    /* Suit accessories — pocket squares */
    {
      id: "square-ivory-silk",
      name: "The Ivory Silk Square",
      category: "Suit Accessories",
      subcategory: "pocket-squares",
      description: "A clean ivory fold that lets the tailoring lead.",
      image: "suits/pocket-squares/ivory-silk.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A white pocket square folded into a grey check suit.",
    },
    {
      id: "square-folded-silk",
      name: "The Folded Silk Square",
      category: "Suit Accessories",
      subcategory: "pocket-squares",
      description: "A soft neutral square, folded for an understated finish.",
      image: "suits/pocket-squares/folded-silk.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A pocket square in a beige suit, photographed in close-up.",
    },
    {
      id: "square-floral-signature",
      name: "The Floral Signature Square",
      category: "Suit Accessories",
      subcategory: "pocket-squares",
      description: "A printed floral square for celebrations with a softer edge.",
      image: "suits/pocket-squares/floral-signature.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2491,
      alt: "A floral pocket square and blue tie on a blue jacket.",
    },
    {
      id: "square-crimson-accent",
      name: "The Crimson Accent",
      category: "Suit Accessories",
      subcategory: "pocket-squares",
      description: "A single note of red against dark tailoring.",
      image: "suits/pocket-squares/crimson-accent.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A red pocket square and flower pin on a black suit.",
    },

    /* Dress accessories — bridal shoes */
    {
      id: "bridal-block-heel-marble",
      name: "The Marble Block Heel",
      category: "Dress Accessories",
      subcategory: "bridal-shoes",
      description: "A white block heel with a stable stride and a sculptural finish.",
      image: "dresses/shoes/block-heel-marble.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "White block-heel sandals on a marble surface.",
    },
    {
      id: "bridal-silver-sandal",
      name: "The Silver Bridal Sandal",
      category: "Dress Accessories",
      subcategory: "bridal-shoes",
      description: "Fine silver straps that catch the light with every step.",
      image: "dresses/shoes/silver-sandal.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "Silver bridal sandals displayed with jewellery on stone.",
    },
    {
      id: "bridal-beaded-mule",
      name: "The Beaded Bridal Mule",
      category: "Dress Accessories",
      subcategory: "bridal-shoes",
      description: "A beaded mule with an easy slip-on shape for the reception.",
      image: "dresses/shoes/beaded-mule.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "White beaded mule shoes on a wooden surface.",
    },
    {
      id: "bridal-ribbon-bow-heel",
      name: "The Ribbon Bow Heel",
      category: "Dress Accessories",
      subcategory: "bridal-shoes",
      description: "Romantic ribbon ties and a refined bridal heel.",
      image: "dresses/shoes/ribbon-bow-heel.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "White bridal shoes with ribbon bows.",
    },

    /* Dress accessories — jewelry */
    {
      id: "jewel-gold-necklace",
      name: "The Fine Gold Necklace",
      category: "Dress Accessories",
      subcategory: "bridal-jewelry",
      description: "A delicate gold chain made for a low, elegant neckline.",
      image: "dresses/jewelry/gold-necklace.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A fine gold necklace on white silk with pale flowers.",
    },
    {
      id: "jewel-pearl-earrings",
      name: "The Pearl Drop Earrings",
      category: "Dress Accessories",
      subcategory: "bridal-jewelry",
      description: "Pearl drops with a soft lustre and a timeless line.",
      image: "dresses/jewelry/pearl-earrings.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 1866,
      alt: "Silver and pearl earrings on soft fabric.",
    },
    {
      id: "jewel-gold-bangle",
      name: "The Gold Bangle",
      category: "Dress Accessories",
      subcategory: "bridal-jewelry",
      description: "A polished bangle to layer with heirloom pieces.",
      image: "dresses/jewelry/gold-bangle.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2333,
      alt: "Gold and silver bangles on a plain white background.",
    },
    {
      id: "jewel-diamond-duo",
      name: "The Diamond Duo",
      category: "Dress Accessories",
      subcategory: "bridal-jewelry",
      description: "A matched necklace and earring pair for the ceremony.",
      image: "dresses/jewelry/diamond-set.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A diamond necklace and earrings presented in a gift box.",
    },

    /* Dress accessories — gloves */
    {
      id: "glove-classic-satin",
      name: "The Classic Satin Glove",
      category: "Dress Accessories",
      subcategory: "bridal-gloves",
      description: "A short satin glove with a smooth, ceremonial finish.",
      image: "dresses/gloves/classic-satin.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A hand wearing a pale satin glove against a neutral grey background.",
    },
    {
      id: "glove-evening-satin",
      name: "The Evening Satin Glove",
      category: "Dress Accessories",
      subcategory: "bridal-gloves",
      description: "An evening-length glove for a formal, romantic gesture.",
      image: "dresses/gloves/evening-satin.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A satin-gloved hand resting beside floral decoration.",
    },
    {
      id: "glove-sheer-lace",
      name: "The Sheer Lace Glove",
      category: "Dress Accessories",
      subcategory: "bridal-gloves",
      description: "Sheer lace for a bride who loves vintage detail.",
      image: "dresses/gloves/sheer-lace.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A lace-gloved bridal hand holding a pearl necklace.",
    },
    {
      id: "glove-opera-satin",
      name: "The Opera Satin Glove",
      category: "Dress Accessories",
      subcategory: "bridal-gloves",
      description: "A dramatic opera-length glove, drawn on with care.",
      image: "dresses/gloves/opera-satin.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A woman adjusting long satin gloves in an elegant period setting.",
    },

    /* Dress accessories — tiaras & crowns */
    {
      id: "tiara-crystal",
      name: "The Crystal Tiara",
      category: "Dress Accessories",
      subcategory: "tiaras-crowns",
      description: "A fine crystal tiara with a light, soaring profile.",
      image: "dresses/tiaras-crowns/crystal-tiara.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 1867,
      alt: "A diamond tiara sparkling against a dark background.",
    },
    {
      id: "tiara-silver-pearl",
      name: "The Silver Pearl Tiara",
      category: "Dress Accessories",
      subcategory: "tiaras-crowns",
      description: "Silver filigree finished with pearls for a classical touch.",
      image: "dresses/tiaras-crowns/silver-pearl-tiara.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2101,
      alt: "A silver tiara displayed with pearls and perfume.",
    },
    {
      id: "tiara-gold-heirloom",
      name: "The Gold Heirloom Tiara",
      category: "Dress Accessories",
      subcategory: "tiaras-crowns",
      description: "A warm gold headpiece with an antique, treasured feel.",
      image: "dresses/tiaras-crowns/gold-tiara.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A golden tiara held by a woman in an ornate dress.",
    },
    {
      id: "tiara-celestial-crown",
      name: "The Celestial Crown",
      category: "Dress Accessories",
      subcategory: "tiaras-crowns",
      description: "A statement crown for the bride who wants a little drama.",
      image: "dresses/tiaras-crowns/celestial-crown.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 1750,
      alt: "A woman presenting a sparkling crown in her hands.",
    },

    /* Dress accessories — veils */
    {
      id: "veil-fingertip",
      name: "The Fingertip Veil",
      category: "Dress Accessories",
      subcategory: "bridal-veils",
      description: "A light fingertip veil that frames the face without hiding it.",
      image: "dresses/veils/fingertip-veil.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A bride in a white gown with a flowing veil outdoors.",
    },
    {
      id: "veil-chapel",
      name: "The Chapel-Length Veil",
      category: "Dress Accessories",
      subcategory: "bridal-veils",
      description: "A graceful chapel length made for a measured walk down the aisle.",
      image: "dresses/veils/chapel-veil.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A bride with a long veil walking down a church aisle.",
    },
    {
      id: "veil-cathedral",
      name: "The Cathedral Veil",
      category: "Dress Accessories",
      subcategory: "bridal-veils",
      description: "A dramatic sweep of tulle for the grandest entrances.",
      image: "dresses/veils/cathedral-veil.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "A bride in a white dress and veil standing by a window.",
    },
    {
      id: "veil-lace-trimmed",
      name: "The Lace-Trimmed Veil",
      category: "Dress Accessories",
      subcategory: "bridal-veils",
      description: "A soft veil edged in lace for a romantic, vintage finish.",
      image: "dresses/veils/lace-trimmed-veil.jpg",
      hoverImage: null,
      price: null,
      productUrl: null,
      width: 1400,
      height: 2100,
      alt: "Rear view of a bride wearing a lace veil.",
    },
  ];

  /* ----------------------------------------------------------------------
     Card renderers
     ---------------------------------------------------------------------- */
  const arrowIcon =
    '<svg class="icon" aria-hidden="true"><use href="#icon-arrow"></use></svg>';

  function categoryCardMarkup(card, categoryId) {
    const name = escapeHtml(card.name);

    return `
      <a class="acc-cat-card" href="#${categoryId}-products" data-reveal
         aria-label="Explore ${name} in the ${escapeHtml(
           categoryId === "groom-shoes" ? "groom" : "bridal"
         )} shoe collection">
        <span class="acc-cat-card__visual">
          <img class="acc-cat-card__image"
               src="${ASSET_ROOT}${card.image}"
               alt="${escapeHtml(card.alt)}"
               width="${card.width}" height="${card.height}"
               loading="lazy" decoding="async">
        </span>
        <span class="acc-cat-card__body">
          <span class="acc-cat-card__name">${name}</span>
          <span class="acc-cat-card__desc">${escapeHtml(card.desc)}</span>
          <span class="text-link text-link--sm acc-cat-card__link">
            Explore Collection ${arrowIcon}
          </span>
        </span>
      </a>`;
  }

  function productCardMarkup(product, index) {
    const name = escapeHtml(product.name);
    const subcategory = escapeHtml(product.subcategory);
    const dialog =
      `${product.name} is part of the Élan Bridal accessories preview. ` +
      "Product detail pages, pricing, and stock are not implemented in this " +
      "frontend demonstration.";
    const price =
      product.price === null || product.price === undefined
        ? ""
        : `<p class="acc-product__price">${escapeHtml(product.price)}</p>`;

    return `
      <article class="acc-product" data-reveal>
        <figure class="acc-product__visual">
          <img class="acc-product__image"
               src="${ASSET_ROOT}${product.image}"
               alt="${escapeHtml(product.alt)}"
               width="${product.width}" height="${product.height}"
               loading="lazy" decoding="async">
        </figure>
        <div class="acc-product__body">
          <div class="acc-product__heading">
            <h4 class="acc-product__name">${name}</h4>
            <span class="acc-product__index" aria-hidden="true">${String(
              index + 1
            ).padStart(2, "0")}</span>
          </div>
          <p class="acc-product__desc">${escapeHtml(product.description)}</p>
          ${price}
          <a class="text-link text-link--sm acc-product__link"
             href="#${subcategory}"
             data-placeholder="${name}"
             data-dialog="${escapeHtml(dialog)}"
             data-dialog-cta="Keep exploring"
             data-dialog-target="#${subcategory}">
            View details ${arrowIcon}
          </a>
        </div>
      </article>`;
  }

  function renderAccessoryCards() {
    const hosts = $$("[data-accessory-cards]");
    hosts.forEach((host) => {
      const cards = CATEGORY_CARDS[host.dataset.accessoryCards];
      if (!cards) return;
      host.innerHTML = cards
        .map((card) => categoryCardMarkup(card, host.dataset.accessoryCards))
        .join("");
    });
  }

  function renderAccessoryProducts() {
    const hosts = $$("[data-accessory-products]");
    hosts.forEach((host) => {
      const group = host.dataset.accessoryProducts;
      const products = PRODUCTS.filter((item) => item.subcategory === group);
      if (!products.length) return;
      host.innerHTML = products
        .map((product, index) => productCardMarkup(product, index))
        .join("");
    });
  }

  /* ----------------------------------------------------------------------
     Accessories category navigation
     ----------------------------------------------------------------------
     "All Accessories" sits above the suit block, so it stays active through
     the page intro and until the suit section reaches the top of the
     reading area.
     ---------------------------------------------------------------------- */
  function initAccessoryNav() {
    const nav = $(".acc-nav");
    if (!nav) return;

    const links = $$(".collection-nav__link", nav);
    const pairs = links
      .map((link) => ({
        link,
        id: (link.getAttribute("href") || "").replace(/^#/, ""),
      }))
      .filter((pair) => pair.id && doc.getElementById(pair.id));
    if (!pairs.length) return;

    const sections = pairs
      .filter((pair) => pair.id !== "accessories")
      .map((pair) => doc.getElementById(pair.id));

    let currentId = "accessories";

    function setActive(id) {
      if (id === currentId && nav.dataset.ready === "true") return;
      currentId = id;
      nav.dataset.ready = "true";
      pairs.forEach((pair) => {
        const active = pair.id === id;
        pair.link.classList.toggle("is-active", active);
        if (active) pair.link.setAttribute("aria-current", "location");
        else pair.link.removeAttribute("aria-current");
      });
    }

    function update() {
      const probe = window.scrollY + window.innerHeight * 0.32;
      let next = "accessories";

      sections.forEach((section) => {
        const top = section.getBoundingClientRect().top + window.scrollY;
        if (top <= probe) next = section.id;
      });

      const doc = document.documentElement;
      if (window.innerHeight + window.scrollY >= doc.scrollHeight - 4) {
        next = sections.length ? sections[sections.length - 1].id : next;
      }

      setActive(next);
    }

    let ticking = false;
    function onScroll() {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(() => {
        update();
        ticking = false;
      });
    }

    window.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("resize", onScroll);

    const hash = window.location.hash.replace(/^#/, "");
    if (hash && pairs.some((pair) => pair.id === hash)) {
      setActive(hash);
    } else {
      update();
    }

    update();
  }

  /* ----------------------------------------------------------------------
     Boot
     ---------------------------------------------------------------------- */
  function init() {
    renderAccessoryCards();
    renderAccessoryProducts();
    initAccessoryNav();
  }

  if (doc.readyState === "loading") {
    doc.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
