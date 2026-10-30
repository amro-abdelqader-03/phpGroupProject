/* ==========================================================================
   ÉLAN BRIDAL — Accessories page
   The product cards are now printed by PHP from the database
   (see public/accessories.php). This file only highlights the right link
   in the "All / Suit / Dress Accessories" menu while the page scrolls.
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
     Boot
     ---------------------------------------------------------------------- */
  if (doc.readyState === "loading") {
    doc.addEventListener("DOMContentLoaded", initAccessoryNav);
  } else {
    initAccessoryNav();
  }
})();
