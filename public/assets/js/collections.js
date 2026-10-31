
/* ==========================================================================
   ÉLAN BRIDAL — Collection pages
   Dynamic suits data from API + static dresses data.
   ========================================================================== */

(() => {
  "use strict";

  const doc = document;
  const $ = (selector, context = doc) => context.querySelector(selector);
  const $$ = (selector, context = doc) => Array.from(context.querySelectorAll(selector));

  const SUITS_API = "/phpGroupProject/app/controllers/suits.php";

  // وظيفتها تحويل الرموز الخاصة إلى HTML entities قبل إدخال البيانات إلى HTML.
  const escapeHtml = (value) =>
    String(value ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");

  /* ----------------------------------------------------------------------
     Static dresses data
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
            desc: "Romantic volume, a fitted bodice, and a softly sweeping skirt."
          },
          {
            slug: "princess-celeste",
            name: "The Celeste Ball Gown",
            desc: "A grand ball-gown silhouette with timeless ceremonial poise."
          },
          {
            slug: "princess-isabella",
            name: "The Isabella Bridal Dress",
            desc: "Delicate lace and a full skirt for a classic bridal entrance."
          },
          {
            slug: "princess-ever-after",
            name: "The Ever After Gown",
            desc: "A studio-fresh portrait of romantic proportion and quiet detail."
          }
        ],

        mermaid: [
          {
            slug: "mermaid-seraphina",
            name: "The Seraphina Mermaid Gown",
            desc: "A sculpted silhouette that follows every curve before flaring softly."
          },
          {
            slug: "mermaid-pearl",
            name: "The Pearl Mermaid Dress",
            desc: "Lace detailing and a fluid train for a refined, dramatic line."
          },
          {
            slug: "mermaid-elara",
            name: "The Elara Bridal Gown",
            desc: "An off-shoulder neckline and close-fitting shape with modern ease."
          },
          {
            slug: "mermaid-grace",
            name: "The Grace Signature Dress",
            desc: "A poised mermaid silhouette with a graceful, floor-sweeping finish."
          }
        ],

        royal: [
          {
            slug: "royal-heritage",
            name: "The Royal Heritage Gown",
            desc: "A regal silhouette framed by historic architecture and timeless detail."
          },
          {
            slug: "royal-imperial",
            name: "The Imperial Lace Dress",
            desc: "Intricate lace and a ceremonial column for a stately presence."
          },
          {
            slug: "royal-majestic",
            name: "The Majestic Bridal Gown",
            desc: "Full-length elegance with an architectural setting and refined drape."
          },
          {
            slug: "royal-crown",
            name: "The Crown Signature Dress",
            desc: "A classic bridal column with a quietly majestic finish."
          }
        ],

        "a-line": [
          {
            slug: "aline-amara",
            name: "The Amara A-Line Gown",
            desc: "A balanced A-line with an effortless, sunlit sense of movement."
          },
          {
            slug: "aline-florence",
            name: "The Florence Bridal Dress",
            desc: "A graceful skirt and natural waist, made for an easy bridal stride."
          },
          {
            slug: "aline-lily",
            name: "The Lily Signature Gown",
            desc: "Soft volume and a fluid hem for timeless, romantic proportion."
          },
          {
            slug: "aline-timeless",
            name: "The Timeless A-Line Dress",
            desc: "A clean A-line silhouette with a light, contemporary finish."
          }
        ],

        sheath: [
          {
            slug: "sheath-sophia",
            name: "The Sophia Sheath Gown",
            desc: "A sleek column with a refined neckline and minimal detailing."
          },
          {
            slug: "sheath-minimalist",
            name: "The Minimalist Bride",
            desc: "Understated satin, clean lines, and a quiet contemporary elegance."
          },
          {
            slug: "sheath-elodie",
            name: "The Elodie Signature Dress",
            desc: "A fluid sheath silhouette with a gentle drape and polished finish."
          },
          {
            slug: "sheath-pure-elegance",
            name: "The Pure Elegance Gown",
            desc: "A pure column shape for a confident, modern bridal statement."
          }
        ]
      }
    }
  };

  // عندنا اختلاف بين أسماء الأقسام في قاعدة البيانات وبين أسماء الأقسام في HTML.

  const SUIT_CATEGORY_MAP = {
    "Tuxedo": "tuxedo",
    "3 Piece Suit": "three-piece",
    "Regular Fit": "regular-fit",
    "Slim Fit": "slim-fit",
    "Double Breasted Suit": "double-breasted"
  };

  // وظيفتها تجهيز مسار الصورة حتى نستخدمه داخل خاصية src.

  function getImageUrl(path) {
    if (!path) return "";

    // Keep absolute URLs and root-relative paths unchanged.
    if (/^(https?:)?\/\//i.test(path) || path.startsWith("/")) {
      return path;
    }

    // Database paths are relative to the project root.
    return "/" + path.replace(/^\/+/, "");
  }

  /* ----------------------------------------------------------------------
     Product accessibility text
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
      ? `Additional view of ${product.name}.`
      : `Additional photograph of ${product.name}.`;
  }

// مسؤولة عن بناء كرت المنتج

  function cardMarkup(product, index, categoryId, config) {

    const ready = product.secondaryReady === true;
    
    const primary = "../" + getImageUrl(product.image_url)
    
    // const primary = config.kind === "suit"
    //   ? getImageUrl(product.image_url)
    //   : config.base + product.slug + config.primarySuffix;

    const secondary = config.kind === "suit"
      ? (
          product.secondary_image
            ? getImageUrl(product.secondary_image)
            : primary
        )
      : config.base + product.slug + config.secondarySuffix;

    const detailsUrl = config.kind === "suit"
      ? `product.php?id=${encodeURIComponent(product.product_id)}`
      : `product.php?category=dresses&slug=${encodeURIComponent(product.slug)}`;

    const name = escapeHtml(
      config.kind === "suit" ? product.product_name : product.name
    );

    const description = escapeHtml(
      config.kind === "suit" ? product.product_description : product.desc
    );

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

    const tag = config.kind === "suit" ? "" : (ready ? "" : "Detail study");

    return `
      <article class="look-card look-card--${config.kind}"
               data-look-card
               data-reveal
               data-view="primary">

        <a class="look-card__visual look-card__visual-link"
           href="${detailsUrl}"
           aria-label="View details for ${name}">

          <img class="look-card__image look-card__image--primary"
               src="${escapeHtml(primary)}"
               alt="${escapeHtml(productAlt(
                 config.kind === "suit"
                   ? { name: product.product_name }
                   : product,
                 config
               ))}"
               width="900"
               height="1200"
               loading="lazy"
               decoding="async">

          <img class="look-card__image look-card__image--secondary"
               src="${escapeHtml(secondary)}"
               alt="${escapeHtml(secondaryAlt(
                 config.kind === "suit"
                   ? { name: product.product_name }
                   : product,
                 config,
                 ready
               ))}"
               width="900"
               height="1200"
               loading="lazy"
               decoding="async">

          ${
            tag
              ? `<span class="look-card__tag">${escapeHtml(tag)}</span>`
              : ""
          }

          <span class="look-card__veil" aria-hidden="true"></span>
        </a>

        <div class="look-card__body">

          <div class="look-card__heading">
            <h3 class="look-card__name">
              <a class="look-card__product-link" href="${detailsUrl}">
                ${name}
              </a>
            </h3>

            <span class="look-card__index" aria-hidden="true">
              ${String(index + 1).padStart(2, "0")}
            </span>
          </div>

          <p class="look-card__desc">${description}</p>

          ${
            config.kind === "suit"
              ? `<p class="look-card__price">${escapeHtml(product.price)} JOD</p>`
              : (
                  ready
                    ? ""
                    : `<p class="look-card__note">
                         <span aria-hidden="true"></span>
                         ${config.kind === "dress" ? "Back-view" : "Piece"} photography to follow.
                       </p>`
                )
          }

          <div class="look-card__actions">

            <a class="text-link text-link--sm look-card__link"
               href="${detailsUrl}">
              View details
              <svg class="icon" aria-hidden="true">
                <use href="#icon-arrow"></use>
              </svg>
            </a>

            <button class="look-card__toggle"
                    type="button"
                    aria-pressed="false"
                    aria-label="${escapeHtml(`${onLabel} for ${name}`)}"
                    data-look-toggle
                    data-label-on="${escapeHtml(onLabel)}"
                    data-label-off="${escapeHtml(offLabel)}"
                    data-product="${name}">

              <svg class="icon" aria-hidden="true">
                <use href="#icon-plus"></use>
              </svg>

              <span data-toggle-label>${escapeHtml(onLabel)}</span>
            </button>

          </div>
        </div>
      </article>`;
  }

  /* ----------------------------------------------------------------------
     Render static dresses
     ---------------------------------------------------------------------- */

  function renderDresses() {
    const grids = $$('[data-collection-grid][data-collection="dresses"]');

    grids.forEach((grid) => {
      const categoryId = grid.dataset.category;
      const config = COLLECTIONS.dresses;

      const products = config.categories[categoryId];

      if (!products) return;

      grid.innerHTML = products
        .map((product, index) => cardMarkup(product, index, categoryId, config))
        .join("");
    });
  }

  
    //Render suits from API : دالة ربط مع الـ API
    
  async function renderSuits() {

    const grids = $$('[data-collection-grid][data-collection="suits"]');

    if (!grids.length) return;

    try {

      const response = await fetch(SUITS_API, {
        method: "GET",
        headers: {
          "Accept": "application/json"
        }
      });

      if (!response.ok) {
        throw new Error(`HTTP Error: ${response.status}`);
      }

      const result = await response.json();

      if (!result.success || !Array.isArray(result.data)) {
        throw new Error("Invalid API response");
      }

      const products = result.data;

      // توزيع المنتجات على الأقسام
      grids.forEach((grid) => {

        const categoryId = grid.dataset.category;

        const matchingProducts = products.filter((product) => {
          return SUIT_CATEGORY_MAP[product.subcategory_name] === categoryId;
        });

        if (matchingProducts.length === 0) {
          grid.innerHTML = "<p>No products available in this category.</p>";
          return;
        }

        // عرض المنتجات

        grid.innerHTML = matchingProducts
          .map((product, index) => {
            return cardMarkup(
              product,
              index,
              categoryId,
              { kind: "suit" }
            );
          })
          .join("");

      });

    } catch (error) {

      console.error("Error loading suits:", error);

      grids.forEach((grid) => {
        grid.innerHTML = "<p>Unable to load products. Please try again later.</p>";
      });

    }
  }

//  هدول مسؤولين عن زر تبديل الصورة.
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

  // هاي مسؤولة عن التنقل بين الأقسام وتحديد القسم النشط في القائمة.

  function initCategoryNav() {

    const links = $$(".collection-nav__link");

    if (!links.length) return;

    const sections = links
      .map((link) => {
        const href = link.getAttribute("href");

        return href && href.startsWith("#")
          ? doc.getElementById(href.slice(1))
          : null;
      })
      .filter(Boolean);

    if (!sections.length) return;

    const setActive = (id) => {

      links.forEach((link) => {

        const active = link.getAttribute("href") === `#${id}`;

        link.classList.toggle("is-active", active);

        if (active) {
          link.setAttribute("aria-current", "location");
        } else {
          link.removeAttribute("aria-current");
        }

      });
    };

    links.forEach((link) => {

      link.addEventListener("click", () => {

        const href = link.getAttribute("href");

        if (href && href.startsWith("#")) {
          setActive(href.slice(1));
        }

      });
    });

    if (!("IntersectionObserver" in window)) {

      const update = () => {

        const probe = window.scrollY + window.innerHeight * 0.35;

        let current = sections[0].id;

        sections.forEach((section) => {
          if (section.offsetTop <= probe) {
            current = section.id;
          }
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

          if (entry.isIntersecting) {
            setActive(entry.target.id);
          }

        });

      },
      {
        rootMargin: "-38% 0px -52% 0px",
        threshold: 0
      }
    );

    sections.forEach((section) => observer.observe(section));

    const hash = window.location.hash.slice(1);

    setActive(
      hash && doc.getElementById(hash)
        ? hash
        : sections[0].id
    );
  }

  /* ----------------------------------------------------------------------
     Boot
     ---------------------------------------------------------------------- */

  async function init() {

    renderDresses();

    initViewToggles();

    initCategoryNav();

    await renderSuits();

  }

  if (doc.readyState === "loading") {

    doc.addEventListener("DOMContentLoaded", init);

  } else {

    init();

  }

})();
