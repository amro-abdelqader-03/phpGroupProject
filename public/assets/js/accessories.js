/* ==========================================================================
   ÉLAN BRIDAL — Accessories page and one accessory page
   The product cards are printed by PHP from the database
   (see public/accessories.php and public/accessory.php). This file
   highlights the right link in the "All / Suit / Dress Accessories" menu
   while the page scrolls, and runs the Add to Bag buttons.
   ========================================================================== */
(() => {
  "use strict";

  const doc = document;
  const $ = (selector, context = doc) => context.querySelector(selector);
  const $$ = (selector, context = doc) => Array.from(context.querySelectorAll(selector));

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
     Add to Bag buttons
     ----------------------------------------------------------------------
     Each button has the product's details in data-... attributes (printed by
     PHP). Clicking it puts the product in the bag with ElanCart.add() from
     cart.js, the same bag the Bag page shows.
     ---------------------------------------------------------------------- */
  function initAddToBag() {
    const status = $("#acc-bag-status");

    // Hide the "Please choose a size" message as soon as a size is picked
    $$("[data-size-options]").forEach((sizeBox) => {
      sizeBox.addEventListener("change", () => {
        $("[data-size-error]", sizeBox).textContent = "";
      });
    });

    $$("[data-add-to-bag]").forEach((button) => {
      button.addEventListener("click", () => {
        if (!window.ElanCart) return;

        const name = button.dataset.name;

        // Shoes have size buttons on their page (accessory.php): a size must be chosen first
        const product = button.closest("[data-product]");
        const sizeBox = product ? $("[data-size-options]", product) : null;
        let size = "";

        if (sizeBox) {
          const checked = $("input[name='size']:checked", sizeBox);
          if (!checked) {
            $("[data-size-error]", sizeBox).textContent = "Please choose a size.";
            const firstSize = $("input[name='size']:not(:disabled)", sizeBox);
            if (firstSize) firstSize.focus();
            return;
          }
          size = checked.value;
        }
        const cart = window.ElanCart.add({
          productId: button.dataset.id,
          productName: name,
          category: "accessories",
          // a full link, so the photo also works on the Bag page
          image: button.dataset.image ? new URL(button.dataset.image, window.location.href).href : "",
          unitPrice: Number(button.dataset.price),
          size: size,
          color: "",
          quantity: 1,
        });

        // Show "Added" on the button for 2 seconds
        button.textContent = "Added ✓";
        button.classList.add("is-added");
        clearTimeout(button.resetTimer);
        button.resetTimer = setTimeout(() => {
          button.textContent = "Add to Bag";
          button.classList.remove("is-added");
        }, 2000);

        // Screen readers read this message out loud
        const count = cart.reduce((total, item) => total + Number(item.quantity), 0);
        const sizeText = size ? ` (size ${size})` : "";
        if (status) status.textContent = `${name}${sizeText} added to your bag. Your bag now has ${count} ${count === 1 ? "item" : "items"}.`;
      });
    });
  }

  /* ----------------------------------------------------------------------
     Boot
     ---------------------------------------------------------------------- */
  function init() {
    initAccessoryNav();
    initAddToBag();
  }

  if (doc.readyState === "loading") {
    doc.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
