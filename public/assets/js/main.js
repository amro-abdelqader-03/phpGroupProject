/* ==========================================================================
   ÉLAN BRIDAL — Interaction layer
   Vanilla JavaScript, no dependencies.
   ========================================================================== */
(() => {
  "use strict";

  const doc = document;
  const body = doc.body;
  const header = doc.getElementById("site-header");
  const toggle = doc.getElementById("menu-toggle");
  const mobileMenu = doc.getElementById("mobile-menu");
  const main = doc.getElementById("main");
  const footer = doc.getElementById("footer");
  const backToTop = doc.getElementById("back-to-top");
  const dialog = doc.getElementById("placeholder-dialog");
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
  const desktopQuery = window.matchMedia("(min-width: 1024px)");

  let menuOpen = false;
  let dialogTarget = null;

  const $ = (selector, context = doc) => context.querySelector(selector);
  const $$ = (selector, context = doc) => Array.from(context.querySelectorAll(selector));

  const scrollBehavior = () => (reduceMotion.matches ? "auto" : "smooth");

  function headerOffset() {
    const navbar = header ? $(".navbar", header) : null;
    const announcement =
      header && !header.classList.contains("is-scrolled")
        ? $(".announcement", header)
        : null;
    const categoryNav = doc.querySelector(".collection-nav");
    return (
      (navbar ? navbar.offsetHeight : 76) +
      (announcement ? announcement.offsetHeight : 0) +
      (categoryNav ? categoryNav.offsetHeight : 0) +
      18
    );
  }

  function scrollToTarget(target) {
    if (!target) return;
    const top = target.getBoundingClientRect().top + window.scrollY - headerOffset();
    window.scrollTo({ top: Math.max(0, top), behavior: scrollBehavior() });
  }

  function updateHash(hash) {
    try {
      history.replaceState(null, "", hash);
    } catch (error) {
      /* File:// previews and sandboxed contexts may disallow this. */
    }
  }

  /* ----------------------------------------------------------------------
     Header state
     ---------------------------------------------------------------------- */
  function updateHeader() {
    if (!header) return;
    header.classList.toggle("is-scrolled", window.scrollY > 28);
  }

  /* ----------------------------------------------------------------------
     Mobile menu
     ---------------------------------------------------------------------- */
  function getMenuFocusables() {
    if (!mobileMenu || !header) return [];
    const brand = $(".brand", header);
    const panelItems = $$('a[href], button:not([disabled])', mobileMenu);
    return [brand, toggle, ...panelItems].filter(
      (el) => el && el.getClientRects().length > 0
    );
  }

  function openMenu() {
    if (!mobileMenu || !toggle || menuOpen) return;

    menuOpen = true;
    mobileMenu.inert = false;
    mobileMenu.setAttribute("aria-hidden", "false");
    mobileMenu.classList.add("is-open");
    toggle.setAttribute("aria-expanded", "true");
    toggle.setAttribute("aria-label", "Close menu");
    if (header) header.classList.add("is-menu-open");
    body.classList.add("menu-open");
    if (main) main.inert = true;
    if (footer) footer.inert = true;
    if (backToTop) backToTop.inert = true;

    const firstLink = $(".mobile-menu__link", mobileMenu);
    window.requestAnimationFrame(() => {
      window.requestAnimationFrame(() => {
        if (firstLink && menuOpen) firstLink.focus({ preventScroll: true });
      });
    });
  }

  function closeMenu({ returnFocus = true } = {}) {
    if (!mobileMenu || !toggle || !menuOpen) return;

    const focusInside = mobileMenu.contains(doc.activeElement);
    menuOpen = false;

    if (returnFocus || focusInside) {
      toggle.focus({ preventScroll: true });
    }

    mobileMenu.classList.remove("is-open");
    mobileMenu.setAttribute("aria-hidden", "true");
    mobileMenu.inert = true;
    toggle.setAttribute("aria-expanded", "false");
    toggle.setAttribute("aria-label", "Open menu");
    if (header) header.classList.remove("is-menu-open");
    body.classList.remove("menu-open");
    if (main) main.inert = false;
    if (footer) footer.inert = false;
    if (backToTop) backToTop.inert = false;
  }

  function initMobileMenu() {
    if (!mobileMenu || !toggle) return;

    mobileMenu.inert = true;

    toggle.addEventListener("click", () => {
      if (menuOpen) closeMenu();
      else openMenu();
    });

    doc.addEventListener("keydown", (event) => {
      if (!menuOpen) return;

      if (event.key === "Escape") {
        event.preventDefault();
        closeMenu();
        return;
      }

      if (event.key !== "Tab") return;

      const focusables = getMenuFocusables();
      if (!focusables.length) return;

      const first = focusables[0];
      const last = focusables[focusables.length - 1];
      const active = doc.activeElement;

      if (!focusables.includes(active)) {
        event.preventDefault();
        first.focus();
      } else if (event.shiftKey && active === first) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && active === last) {
        event.preventDefault();
        first.focus();
      }
    });

    const handleDesktopResize = (event) => {
      if (event.matches && menuOpen) {
        closeMenu({ returnFocus: false });
      }
    };

    if (desktopQuery.addEventListener) {
      desktopQuery.addEventListener("change", handleDesktopResize);
    } else if (desktopQuery.addListener) {
      desktopQuery.addListener(handleDesktopResize);
    }
  }

  /* ----------------------------------------------------------------------
     Smooth scrolling for in-page navigation
     ---------------------------------------------------------------------- */
  function initSmoothScroll() {
    doc.addEventListener("click", (event) => {
      if (!(event.target instanceof Element)) return;

      const link = event.target.closest('a[href^="#"]');
      if (!link || link.hasAttribute("data-placeholder")) return;
      if (dialog && dialog.contains(link)) return;

      const href = link.getAttribute("href");
      if (!href || href === "#") return;

      const target = doc.getElementById(href.slice(1));
      if (!target) return;

      event.preventDefault();

      if (menuOpen) closeMenu();

      if (target === main) {
        scrollToTarget(target);
        main.focus({ preventScroll: true });
        return;
      }

      scrollToTarget(target);
      updateHash(href);
    });
  }

  /* ----------------------------------------------------------------------
     Hero campaign film
     ---------------------------------------------------------------------- */
  function initHeroVideo() {
    const hero = doc.getElementById("home");
    const video = doc.getElementById("hero-video");
    if (!hero || !video) return;

    const connection =
      navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    const saveData = Boolean(
      connection &&
        (connection.saveData || /(^|-)2g$/.test(connection.effectiveType || ""))
    );
    const mobileSource = window.matchMedia("(max-width: 767px)");

    let fallbackTimer = null;

    function showPoster() {
      hero.classList.add("is-video-poster");
      if (!video.paused) video.pause();
      clearFallbackCheck();
    }

    function clearFallbackCheck() {
      if (fallbackTimer === null) return;
      window.clearTimeout(fallbackTimer);
      fallbackTimer = null;
    }

    function checkVideoReadiness() {
      fallbackTimer = null;
      // An aborted or unsupported source can leave the element paused with no
      // error object, so rely on the media state instead of the error event.
      if (
        video.readyState === 0 &&
        video.networkState === HTMLMediaElement.NETWORK_NO_SOURCE
      ) {
        showPoster();
      }
    }

    function scheduleFallbackCheck() {
      clearFallbackCheck();
      fallbackTimer = window.setTimeout(checkVideoReadiness, 2500);
    }

    function playVideo() {
      if (reduceMotion.matches || saveData) {
        showPoster();
        return;
      }

      const playback = video.play();
      if (playback && typeof playback.catch === "function") {
        playback.catch(showPoster);
      }
      scheduleFallbackCheck();
    }

    function restartVideo() {
      if (reduceMotion.matches || saveData) return;
      clearFallbackCheck();
      video.load();
      video.addEventListener("loadeddata", playVideo, { once: true });
      scheduleFallbackCheck();
    }

    $$("source", video).forEach((source) => {
      source.addEventListener("error", showPoster);
    });
    video.addEventListener("error", showPoster);
    video.addEventListener("playing", () => {
      clearFallbackCheck();
      hero.classList.remove("is-video-poster");
    });

    if (reduceMotion.matches || saveData) {
      video.removeAttribute("autoplay");
      video.preload = "none";
      showPoster();
    } else {
      playVideo();
    }

    const handleMotionPreference = () => {
      if (reduceMotion.matches) {
        video.removeAttribute("autoplay");
        video.preload = "none";
        showPoster();
      } else if (!saveData) {
        video.preload = "auto";
        video.setAttribute("autoplay", "");
        restartVideo();
      }
    };

    const handleSourceChange = () => {
      restartVideo();
    };

    if (reduceMotion.addEventListener) {
      reduceMotion.addEventListener("change", handleMotionPreference);
    } else if (reduceMotion.addListener) {
      reduceMotion.addListener(handleMotionPreference);
    }

    if (mobileSource.addEventListener) {
      mobileSource.addEventListener("change", handleSourceChange);
    } else if (mobileSource.addListener) {
      mobileSource.addListener(handleSourceChange);
    }
  }

  /* ----------------------------------------------------------------------
     Scroll reveals
     ---------------------------------------------------------------------- */
  function initReveals() {
    const items = $$("[data-reveal]");
    if (!items.length) return;

    if (!("IntersectionObserver" in window) || reduceMotion.matches) {
      items.forEach((item) => item.classList.add("is-revealed"));
      return;
    }

    items.forEach((item) => item.classList.add("reveal"));

    const observer = new IntersectionObserver(
      (entries, io) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          entry.target.classList.add("is-revealed");
          io.unobserve(entry.target);
        });
      },
      { threshold: 0.08, rootMargin: "0px 0px -8% 0px" }
    );

    items.forEach((item) => observer.observe(item));

    const revealAll = () => {
      items.forEach((item) => item.classList.add("is-revealed"));
      observer.disconnect();
    };

    if (reduceMotion.addEventListener) {
      reduceMotion.addEventListener("change", (event) => {
        if (event.matches) revealAll();
      });
    }
  }

  /* ----------------------------------------------------------------------
     Active navigation state
     ---------------------------------------------------------------------- */
  function initActiveNav() {
    const sections = ["home", "dress", "suits", "accessories", "about"]
      .map((id) => doc.getElementById(id))
      .filter(Boolean);

    const links = $$(".primary-nav__link, .mobile-menu__link");
    if (sections.length < 2 || !links.length) return () => {};

    /* Two landing sections now live on their own collection pages, so their
       navigation links point at those pages rather than in-page anchors. */
    const pageLinks = {
      home: "index.php",
      dress: "dresses.php",
      suits: "suits.php",
      accessories: "accessories.php",
      about: "index.php#about",
    };

    return function updateActiveNav() {
      const probe = window.scrollY + headerOffset() + 130;
      let currentId = sections[0].id;

      sections.forEach((section) => {
        const top = section.getBoundingClientRect().top + window.scrollY;
        if (top <= probe) currentId = section.id;
      });

      links.forEach((link) => {
        const href = link.getAttribute("href");
        const pageLink = pageLinks[currentId];
        // Always true or false: toggle() with undefined would flip the link on and off
        const active = Boolean(
          href === `#${currentId}` ||
          (pageLink && (href === pageLink || href.endsWith(`/${pageLink}`)))
        );
        link.classList.toggle("is-active", active);
        if (active) link.setAttribute("aria-current", "location");
        else link.removeAttribute("aria-current");
      });
    };
  }

  /* ----------------------------------------------------------------------
     Back to top
     ---------------------------------------------------------------------- */
  function initBackToTop() {
    if (!backToTop) return () => {};

    backToTop.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: scrollBehavior() });
    });

    return function updateBackToTop() {
      backToTop.classList.toggle("is-visible", window.scrollY > 640);
    };
  }

  /* ----------------------------------------------------------------------
     Single scroll listener for header, nav and back-to-top
     ---------------------------------------------------------------------- */
  function initScrollEffects(updateActiveNav, updateBackToTop) {
    let ticking = false;

    function onScroll() {
      if (ticking) return;
      ticking = true;

      window.requestAnimationFrame(() => {
        updateHeader();
        updateActiveNav();
        updateBackToTop();
        ticking = false;
      });
    }

    window.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("resize", onScroll);

    updateHeader();
    updateActiveNav();
    updateBackToTop();
  }

  /* ----------------------------------------------------------------------
     Newsletter demonstration
     ---------------------------------------------------------------------- */
  function initNewsletter() {
    const form = doc.getElementById("newsletter-form");
    if (!form) return;

    const input = doc.getElementById("newsletter-email");
    const status = doc.getElementById("newsletter-status");
    if (!input || !status) return;

    function setError(message) {
      form.classList.add("is-invalid");
      input.setAttribute("aria-invalid", "true");
      status.className = "newsletter__status is-error";
      status.textContent = message;
    }

    function clearStatus() {
      form.classList.remove("is-invalid");
      input.removeAttribute("aria-invalid");
      status.className = "newsletter__status";
      status.textContent = "";
    }

    form.addEventListener("submit", (event) => {
      event.preventDefault();
      const email = input.value.trim();
      const valid = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email);

      if (!valid) {
        setError("Please enter a valid email address.");
        input.focus();
        return;
      }

      form.classList.remove("is-invalid");
      input.setAttribute("aria-invalid", "false");
      status.className = "newsletter__status is-success";
      status.innerHTML =
        '<svg class="icon" aria-hidden="true" focusable="false"><use href="#icon-check"></use></svg>' +
        "<span>Thank you. This is a frontend demonstration &mdash; no email has been stored or sent.</span>";

      form.reset();
    });

    input.addEventListener("input", clearStatus);
  }

  /* ----------------------------------------------------------------------
     Placeholder destinations & dialog
     ---------------------------------------------------------------------- */
  function initDialog() {
    if (!dialog) return;

    const titleEl = doc.getElementById("dialog-title");
    const bodyEl = doc.getElementById("dialog-body");
    const ctaEl = doc.getElementById("dialog-cta");
    const ctaLabel = doc.getElementById("dialog-cta-label");

    function openPlaceholder(trigger) {
      if (titleEl) titleEl.textContent = trigger.dataset.placeholder || "A note from the atelier";
      if (bodyEl) {
        bodyEl.textContent =
          trigger.dataset.dialog ||
          "This destination is not part of the frontend demonstration.";
      }
      if (ctaEl) {
        ctaEl.setAttribute("href", trigger.dataset.dialogTarget || "#dress");
        if (ctaLabel) {
          ctaLabel.textContent = trigger.dataset.dialogCta || "Continue exploring";
        }
      }

      if (menuOpen) closeMenu();
      body.classList.add("dialog-open");

      if (typeof dialog.showModal === "function") {
        if (!dialog.open) dialog.showModal();
      } else {
        dialog.setAttribute("open", "");
      }
    }

    doc.addEventListener("click", (event) => {
      if (!(event.target instanceof Element)) return;

      const trigger = event.target.closest("[data-placeholder]");
      if (!trigger) return;

      event.preventDefault();
      openPlaceholder(trigger);
    });

    $$("[data-dialog-close]", dialog).forEach((button) => {
      button.addEventListener("click", () => dialog.close());
    });

    dialog.addEventListener("click", (event) => {
      if (event.target === dialog) dialog.close();
    });

    dialog.addEventListener("close", () => {
      body.classList.remove("dialog-open");

      if (ctaEl && dialogTarget) {
        const target = doc.querySelector(dialogTarget);
        if (target) {
          window.requestAnimationFrame(() => scrollToTarget(target));
          updateHash(dialogTarget);
        }
      }
      dialogTarget = null;
    });

    if (ctaEl) {
      ctaEl.addEventListener("click", (event) => {
        const href = ctaEl.getAttribute("href");
        if (!href || !href.startsWith("#")) return;

        event.preventDefault();
        dialogTarget = href;
        dialog.close();
      });
    }

    if (!("showModal" in dialog)) {
      dialog.addEventListener("click", () => dialog.removeAttribute("open"));
    }
  }

  /* ----------------------------------------------------------------------
     Dynamic copyright year
     ---------------------------------------------------------------------- */
  function initYear() {
    const year = doc.getElementById("year");
    if (year) year.textContent = String(new Date().getFullYear());
  }

  async function initAccountNavigation() {
    const icon = (path) => {
      const svg = doc.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg.setAttribute("viewBox", "0 0 24 24");
      svg.setAttribute("class", "nav-action-icon");
      svg.setAttribute("aria-hidden", "true");
      const shape = doc.createElementNS("http://www.w3.org/2000/svg", "path");
      shape.setAttribute("d", path);
      shape.setAttribute("fill", "none");
      shape.setAttribute("stroke", "currentColor");
      shape.setAttribute("stroke-width", "1.6");
      shape.setAttribute("stroke-linecap", "round");
      shape.setAttribute("stroke-linejoin", "round");
      svg.append(shape);
      return svg;
    };

    $$('a[href="cart.php"]').forEach((link) => {
      if (!$(".nav-action-icon", link)) link.prepend(icon("M3 4h2l2.2 11.2a2 2 0 0 0 2 1.6h8.4a2 2 0 0 0 1.9-1.4L21 9H7M10 21h.01M18 21h.01"));
    });
    $$('a[href="login.php"]').forEach((link) => {
      if (!$(".nav-action-icon", link)) link.prepend(icon("M20 21a8 8 0 0 0-16 0M12 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"));
    });

    try {
      const response = await fetch("auth-status.php", { credentials: "same-origin", headers: { Accept: "application/json" }, cache: "no-store" });
      if (!response.ok) return;
      const account = await response.json();
      if (!account.logged_in) return;

      $$('a[href="register.php"], a[href="login.php"]').forEach((link) => {
        const listItem = link.closest("li");
        if (listItem) listItem.remove();
        else link.remove();
      });
      $$('ul.primary-nav__list, ul.mobile-menu__list').forEach((list) => {
        const item = doc.createElement("li");
        const form = doc.createElement("form");
        form.action = "logout.php";
        form.method = "post";
        const token = doc.createElement("input");
        token.type = "hidden";
        token.name = "csrf_token";
        token.value = account.csrf_token;
        const button = doc.createElement("button");
        button.type = "submit";
        button.className = list.classList.contains("mobile-menu__list") ? "mobile-menu__link" : "primary-nav__link";
        button.append(icon("M10 17l5-5-5-5M15 12H3M21 19V5a2 2 0 0 0-2-2h-6"));
        button.append(doc.createTextNode("Log Out"));
        form.append(token, button);
        item.append(form);
        list.append(item);
      });

      if (["login.php", "register.php"].includes(window.location.pathname.split("/").pop())) {
        window.location.replace("index.php");
      }
    } catch (error) {
      console.error("Account navigation could not be loaded.", error);
    }
  }

  /* ----------------------------------------------------------------------
     Boot
     ---------------------------------------------------------------------- */
  function init() {
    initAccountNavigation();
    initMobileMenu();
    initSmoothScroll();
    initHeroVideo();
    initReveals();
    initNewsletter();
    initDialog();
    initYear();

    const updateActiveNav = initActiveNav();
    const updateBackToTop = initBackToTop();
    initScrollEffects(updateActiveNav, updateBackToTop);
  }

  if (doc.readyState === "loading") {
    doc.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
