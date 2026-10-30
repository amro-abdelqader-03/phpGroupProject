/* ==========================================================================
   ÉLAN BRIDAL — Collection pages
   Product data + shared card renderer for the suits and dresses lookbooks.
   Loaded before js/main.js so the shared scroll-reveal layer can see the
   rendered cards.
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

  /* ----------------------------------------------------------------------
     Product data
     ----------------------------------------------------------------------
     Each sample look has a front/complete photograph and a second asset.
     Until verified same-garment photography is supplied, the second asset is
     a clearly labelled detail-study placeholder generated from the first
     photograph. Replace the file at the documented path and set
     `secondaryReady: true` to switch the interaction labels to
     "View back" / "View pieces".

     Dresses: assets/images/dresses/<slug>-front.jpg + <slug>-back.jpg
     Suits:   assets/images/suits/<slug>-complete.jpg + <slug>-pieces.jpg
     ---------------------------------------------------------------------- */
  const COLLECTIONS = {
    dresses: {
      kind: "dress",
      base: "assets/images/dresses/",
      primarySuffix: "-front.jpg",
      secondarySuffix: "-back.jpg",
      discover: "Discover the Dress",
      dialogCta: "Explore the dress collection",
      categories: {
        princess: [
          {
            slug: "princess-aurora",
            name: "The Aurora Princess Gown",
            desc: "Romantic volume, a fitted bodice, and a softly sweeping skirt.",
          },
          {
            slug: "princess-celeste",
            name: "The Celeste Ball Gown",
            desc: "A grand ball-gown silhouette with timeless ceremonial poise.",
          },
          {
            slug: "princess-isabella",
            name: "The Isabella Bridal Dress",
            desc: "Delicate lace and a full skirt for a classic bridal entrance.",
          },
          {
            slug: "princess-ever-after",
            name: "The Ever After Gown",
            desc: "A studio-fresh portrait of romantic proportion and quiet detail.",
          },
        ],
        mermaid: [
          {
            slug: "mermaid-seraphina",
            name: "The Seraphina Mermaid Gown",
            desc: "A sculpted silhouette that follows every curve before flaring softly.",
          },
          {
            slug: "mermaid-pearl",
            name: "The Pearl Mermaid Dress",
            desc: "Lace detailing and a fluid train for a refined, dramatic line.",
          },
          {
            slug: "mermaid-elara",
            name: "The Elara Bridal Gown",
            desc: "An off-shoulder neckline and close-fitting shape with modern ease.",
          },
          {
            slug: "mermaid-grace",
            name: "The Grace Signature Dress",
            desc: "A poised mermaid silhouette with a graceful, floor-sweeping finish.",
          },
        ],
        royal: [
          {
            slug: "royal-heritage",
            name: "The Royal Heritage Gown",
            desc: "A regal silhouette framed by historic architecture and timeless detail.",
          },
          {
            slug: "royal-imperial",
            name: "The Imperial Lace Dress",
            desc: "Intricate lace and a ceremonial column for a stately presence.",
          },
          {
            slug: "royal-majestic",
            name: "The Majestic Bridal Gown",
            desc: "Full-length elegance with an architectural setting and refined drape.",
          },
          {
            slug: "royal-crown",
            name: "The Crown Signature Dress",
            desc: "A classic bridal column with a quietly majestic finish.",
          },
        ],
        "a-line": [
          {
            slug: "aline-amara",
            name: "The Amara A-Line Gown",
            desc: "A balanced A-line with an effortless, sunlit sense of movement.",
          },
          {
            slug: "aline-florence",
            name: "The Florence Bridal Dress",
            desc: "A graceful skirt and natural waist, made for an easy bridal stride.",
          },
          {
            slug: "aline-lily",
            name: "The Lily Signature Gown",
            desc: "Soft volume and a fluid hem for timeless, romantic proportion.",
          },
          {
            slug: "aline-timeless",
            name: "The Timeless A-Line Dress",
            desc: "A clean A-line silhouette with a light, contemporary finish.",
          },
        ],
        sheath: [
          {
            slug: "sheath-sophia",
            name: "The Sophia Sheath Gown",
            desc: "A sleek column with a refined neckline and minimal detailing.",
          },
          {
            slug: "sheath-minimalist",
            name: "The Minimalist Bride",
            desc: "Understated satin, clean lines, and a quiet contemporary elegance.",
          },
          {
            slug: "sheath-elodie",
            name: "The Elodie Signature Dress",
            desc: "A fluid sheath silhouette with a gentle drape and polished finish.",
          },
          {
            slug: "sheath-pure-elegance",
            name: "The Pure Elegance Gown",
            desc: "A pure column shape for a confident, modern bridal statement.",
          },
        ],
      },
    },

    suits: {
      kind: "suit",
      base: "assets/images/suits/",
      primarySuffix: "-complete.jpg",
      secondarySuffix: "-pieces.jpg",
      discover: "Discover the Suit",
      dialogCta: "Explore the suit collection",
      categories: {
        tuxedo: [
          {
            slug: "tuxedo-midnight",
            name: "The Midnight Tuxedo",
            desc: "Black-tie sophistication with a clean, ceremonial line.",
          },
          {
            slug: "tuxedo-classic-black",
            name: "The Classic Black Tuxedo",
            desc: "A traditional black tuxedo with timeless evening presence.",
          },
          {
            slug: "tuxedo-ivory",
            name: "The Ivory Wedding Tuxedo",
            desc: "Warm ivory tailoring for a refined, celebratory statement.",
          },
          {
            slug: "tuxedo-shawl-lapel",
            name: "The Modern Shawl-Lapel Tuxedo",
            desc: "A softer lapel and a modern silhouette for formal celebrations.",
          },
        ],
        "three-piece": [
          {
            slug: "threepiece-heritage",
            name: "The Heritage Three-Piece",
            desc: "Jacket, waistcoat, and trousers with old-world polish.",
          },
          {
            slug: "threepiece-champagne",
            name: "The Champagne Signature",
            desc: "Warm neutral tailoring with a coordinated waistcoat.",
          },
          {
            slug: "threepiece-charcoal",
            name: "The Modern Charcoal Suit",
            desc: "A grey three-piece with a clean, contemporary cut.",
          },
          {
            slug: "threepiece-navy",
            name: "The Classic Navy Three-Piece",
            desc: "Navy tailoring completed with a matching waistcoat.",
          },
        ],
        "regular-fit": [
          {
            slug: "regular-navy",
            name: "The Classic Navy Suit",
            desc: "A balanced navy suit with an easy, traditional drape.",
          },
          {
            slug: "regular-charcoal",
            name: "The Timeless Charcoal Suit",
            desc: "Soft grey tailoring with a comfortable, enduring line.",
          },
          {
            slug: "regular-sandstone",
            name: "The Sandstone Wedding Suit",
            desc: "A warm neutral suit for daylight ceremonies and garden vows.",
          },
          {
            slug: "regular-black",
            name: "The Traditional Black Suit",
            desc: "A formal black suit with a confident, classic finish.",
          },
        ],
        "slim-fit": [
          {
            slug: "slim-black",
            name: "The Modern Black Suit",
            desc: "A close, contemporary cut with a sharply tailored line.",
          },
          {
            slug: "slim-midnight-blue",
            name: "The Midnight Blue Slim Fit",
            desc: "Deep blue tailoring with a clean, modern silhouette.",
          },
          {
            slug: "slim-ivory",
            name: "The Ivory Signature Slim Fit",
            desc: "A light ivory suit with a refined, close-fitting profile.",
          },
          {
            slug: "slim-charcoal",
            name: "The Contemporary Charcoal Suit",
            desc: "Grey tailoring with a streamlined, modern edge.",
          },
        ],
        "double-breasted": [
          {
            slug: "double-heritage",
            name: "The Heritage Double-Breasted",
            desc: "Structured shoulders and a classic double-breasted stance.",
          },
          {
            slug: "double-midnight",
            name: "The Midnight Statement Suit",
            desc: "Deep, confident tailoring for an evening entrance.",
          },
          {
            slug: "double-ivory",
            name: "The Ivory Ceremony Suit",
            desc: "A ceremonial ivory suit with polished double-breasted detail.",
          },
          {
            slug: "double-navy",
            name: "The Modern Navy Double-Breasted",
            desc: "Navy tailoring with a clean, contemporary double-breasted line.",
          },
        ],
      },
    },
  };

  /* ----------------------------------------------------------------------
     Card renderer
     ---------------------------------------------------------------------- */
  function productAlt(product, config) {
    if (config.kind === "dress") {
      return `Model wearing ${product.name} in a full-length bridal portrait.`;
    }
    return `Model wearing ${product.name}, photographed in full length.`;
  }

  function secondaryAlt(product, config, ready) {
    if (config.kind === "dress") {
      return ready
        ? `Back view of ${product.name}, showing the same gown from behind.`
        : `Detail study of ${product.name}; verified back-view photography is still to be supplied.`;
    }
    return ready
      ? `Individual pieces of ${product.name}, shown as a coordinated set.`
      : `Tailoring detail study of ${product.name}; verified piece photography is still to be supplied.`;
  }

  function cardMarkup(product, index, categoryId, config) {
    const ready = product.secondaryReady === true;
    const primary = config.base + product.slug + config.primarySuffix;
    const secondary = config.base + product.slug + config.secondarySuffix;
    const categoryKey = config.kind === "dress" ? "dresses" : "suits";
    const detailsUrl = `product.php?category=${categoryKey}&slug=${encodeURIComponent(product.slug)}`;
    const name = escapeHtml(product.name);
    const onLabel = ready
      ? config.kind === "dress"
        ? "View back"
        : "View pieces"
      : "View detail";
    const offLabel = ready
      ? config.kind === "dress"
        ? "View front"
        : "View complete"
      : "View full look";
    const tag = ready ? "" : "Detail study";

    return `
      <article class="look-card look-card--${config.kind}" data-look-card data-reveal data-view="primary">
        <a class="look-card__visual look-card__visual-link" href="${detailsUrl}" aria-label="View details for ${name}">
          <img class="look-card__image look-card__image--primary"
               src="${primary}"
               alt="${escapeHtml(productAlt(product, config))}"
               width="900" height="1200" loading="lazy" decoding="async">
          <img class="look-card__image look-card__image--secondary"
               src="${secondary}"
               alt="${escapeHtml(secondaryAlt(product, config, ready))}"
               width="900" height="1200" loading="lazy" decoding="async">
          ${
            tag
              ? `<span class="look-card__tag">${escapeHtml(tag)}</span>`
              : ""
          }
          <span class="look-card__veil" aria-hidden="true"></span>
        </a>
        <div class="look-card__body">
          <div class="look-card__heading">
            <h3 class="look-card__name"><a class="look-card__product-link" href="${detailsUrl}">${name}</a></h3>
            <span class="look-card__index" aria-hidden="true">${String(index + 1).padStart(2, "0")}</span>
          </div>
          <p class="look-card__desc">${escapeHtml(product.desc)}</p>
          ${
            ready
              ? ""
              : `<p class="look-card__note"><span aria-hidden="true"></span>${
                  config.kind === "dress" ? "Back-view" : "Piece"
                } photography to follow.</p>`
          }
          <div class="look-card__actions">
            <a class="text-link text-link--sm look-card__link" href="${detailsUrl}">
              View details
              <svg class="icon" aria-hidden="true"><use href="#icon-arrow"></use></svg>
            </a>
            <button class="look-card__toggle" type="button"
                    aria-pressed="false"
                    aria-label="${escapeHtml(`${onLabel} for ${product.name}`)}"
                    data-look-toggle
                    data-label-on="${escapeHtml(onLabel)}"
                    data-label-off="${escapeHtml(offLabel)}"
                    data-product="${name}">
              <svg class="icon" aria-hidden="true"><use href="#icon-plus"></use></svg>
              <span data-toggle-label>${escapeHtml(onLabel)}</span>
            </button>
          </div>
        </div>
      </article>`;
  }

  // Shared catalog used by the collection cards and the reusable detail page.
  window.ELAN_COLLECTIONS = COLLECTIONS;

  function renderCollections() {
    const grids = $$("[data-collection-grid]");
    if (!grids.length) return;

    grids.forEach((grid) => {
      const collectionKey = grid.dataset.collection;
      const categoryId = grid.dataset.category;
      const config = COLLECTIONS[collectionKey];
      if (!config || !categoryId) return;
      const products = config.categories[categoryId];
      if (!products) return;

      grid.innerHTML = products
        .map((product, index) => cardMarkup(product, index, categoryId, config))
        .join("");
    });
  }

  /* ----------------------------------------------------------------------
     Front / alternate view toggle
     ---------------------------------------------------------------------- */
  function setView(card, button, alternate) {
    card.dataset.touched = "true";
    card.dataset.view = alternate ? "secondary" : "primary";
    button.setAttribute("aria-pressed", String(alternate));

    const label = button.querySelector("[data-toggle-label]");
    const nextLabel = alternate
      ? button.dataset.labelOff
      : button.dataset.labelOn;
    if (label) label.textContent = nextLabel;
    button.setAttribute(
      "aria-label",
      `${nextLabel} for ${button.dataset.product}`
    );
  }

  function initViewToggles() {
    doc.addEventListener("click", (event) => {
      if (!(event.target instanceof Element)) return;
      const button = event.target.closest("[data-look-toggle]");
      if (!button) return;

      const card = button.closest("[data-look-card]");
      if (!card) return;

      const alternate = card.dataset.view === "secondary";
      setView(card, button, !alternate);
    });
  }

  /* ----------------------------------------------------------------------
     Category navigation active state
     ---------------------------------------------------------------------- */
  function initCategoryNav() {
    const links = $$(".collection-nav__link");
    if (!links.length) return;

    const sections = links
      .map((link) => {
        const href = link.getAttribute("href");
        return href && href.startsWith("#") ? doc.getElementById(href.slice(1)) : null;
      })
      .filter(Boolean);

    if (!sections.length) return;

    const setActive = (id) => {
      links.forEach((link) => {
        const active = link.getAttribute("href") === `#${id}`;
        link.classList.toggle("is-active", active);
        if (active) link.setAttribute("aria-current", "location");
        else link.removeAttribute("aria-current");
      });
    };

    links.forEach((link) => {
      link.addEventListener("click", () => {
        const href = link.getAttribute("href");
        if (href && href.startsWith("#")) setActive(href.slice(1));
      });
    });

    if (!("IntersectionObserver" in window)) {
      const update = () => {
        const probe = window.scrollY + window.innerHeight * 0.35;
        let current = sections[0].id;
        sections.forEach((section) => {
          if (section.offsetTop <= probe) current = section.id;
        });
        setActive(current);
      };
      window.addEventListener("scroll", update, { passive: true });
      update();
      return;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) setActive(entry.target.id);
        });
      },
      { rootMargin: "-38% 0px -52% 0px", threshold: 0 }
    );

    sections.forEach((section) => observer.observe(section));

    const hash = window.location.hash.slice(1);
    setActive(hash && doc.getElementById(hash) ? hash : sections[0].id);
  }

  /* ----------------------------------------------------------------------
     Boot
     ---------------------------------------------------------------------- */
  function init() {
    renderCollections();
    initViewToggles();
    initCategoryNav();
  }

  if (doc.readyState === "loading") {
    doc.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
