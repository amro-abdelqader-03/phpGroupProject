/* AI outfit recommendations on the shared product page. */
(() => {
  "use strict";

  const form = document.getElementById("ai-recommendation-form");
  if (!form) return;

  const submitButton = document.getElementById("ai-recommendation-submit");
  const errorBox = document.getElementById("ai-recommendation-error");
  const results = document.getElementById("ai-recommendation-results");
  const statusBox = document.getElementById("ai-recommendation-status");
  const grid = document.getElementById("ai-recommendation-grid");
  const fitNote = document.getElementById("ai-recommendation-fit-note");
  const endpoint = new URL("../app/controllers/ai-recommendations.php", window.location.href);
  const submitLabel = submitButton ? submitButton.textContent.trim() : "Get AI Recommendations";
  const currency = new Intl.NumberFormat(undefined, { style: "currency", currency: "JOD" });

  let submitting = false;

  function numberField(id, label, minimum, maximum) {
    const input = document.getElementById(id);
    const raw = input ? input.value.trim() : "";
    const value = Number(raw);

    if (raw === "" || !Number.isFinite(value)) {
      throw new Error(`${label} is required and must be a number.`);
    }

    if (value < minimum || value > maximum) {
      throw new Error(`${label} must be between ${minimum} and ${maximum}.`);
    }

    return value;
  }

  function readPreferences() {
    const selectedType = form.querySelector('input[name="product_type"]:checked');

    if (!selectedType) {
      throw new Error("Please choose whether you are looking for a suit or a dress.");
    }

    return {
      product_type: selectedType.value,
      height: numberField("ai-height", "Height", 100, 250),
      width: numberField("ai-width", "Chest / bust measurement", 30, 200),
      budget: numberField("ai-budget", "Maximum budget", 0.01, 1000000),
    };
  }

  function setBusy(busy) {
    submitting = busy;

    if (submitButton) {
      submitButton.disabled = busy;
      submitButton.classList.toggle("is-loading", busy);
      submitButton.textContent = busy ? "Finding your matches..." : submitLabel;
    }

    form.setAttribute("aria-busy", String(busy));
  }

  function clearFeedback() {
    if (errorBox) errorBox.textContent = "";
    if (grid) grid.replaceChildren();
    if (statusBox) statusBox.textContent = "";
    if (fitNote) fitNote.textContent = "";
  }

  function imageUrl(path) {
    if (typeof path !== "string" || path.trim() === "") return "";

    const trimmed = path.trim();

    if (/^https?:\/\//i.test(trimmed)) return trimmed;
    if (trimmed.startsWith("//")) return window.location.protocol + trimmed;

    const relative = trimmed.replace(/^\/+/, "");

    // Reject unexpected schemes; product images come from the catalogue.
    if (/^[a-z][a-z0-9+.-]*:/i.test(relative)) return "";

    return new URL(relative, `${window.location.origin}/phpGroupProject/`).href;
  }

  function productCard(product, index) {
    if (!product || typeof product !== "object") return null;

    const productId = Number(product.product_id);

    if (!Number.isInteger(productId) || productId < 1) return null;

    const article = document.createElement("article");
    article.className = "look-card ai-card";

    const detailsUrl = `product.php?id=${encodeURIComponent(String(productId))}`;
    const name = String(product.product_name || "Product");
    const primary = imageUrl(product.image_url);

    const visual = document.createElement("a");
    visual.className = "look-card__visual ai-card__visual";
    visual.href = detailsUrl;
    visual.setAttribute("aria-label", `View details for ${name}`);

    if (primary) {
      const image = document.createElement("img");
      image.className = "look-card__image look-card__image--primary";
      image.src = primary;
      image.alt = `${name} product photograph`;
      image.width = 900;
      image.height = 1200;
      image.loading = "lazy";
      image.decoding = "async";
      visual.append(image);
    } else {
      const placeholder = document.createElement("span");
      placeholder.className = "ai-card__image-placeholder";
      placeholder.textContent = "Image available on request";
      visual.append(placeholder);
    }

    const body = document.createElement("div");
    body.className = "look-card__body";

    const heading = document.createElement("div");
    heading.className = "look-card__heading";

    const title = document.createElement("h3");
    title.className = "look-card__name";
    const titleLink = document.createElement("a");
    titleLink.className = "look-card__product-link";
    titleLink.href = detailsUrl;
    titleLink.textContent = name;
    title.append(titleLink);

    const indexLabel = document.createElement("span");
    indexLabel.className = "look-card__index";
    indexLabel.setAttribute("aria-hidden", "true");
    indexLabel.textContent = String(index + 1).padStart(2, "0");

    heading.append(title, indexLabel);

    const price = document.createElement("p");
    price.className = "ai-card__price";
    price.textContent = product.price == null
      ? "Price available on request"
      : currency.format(Number(product.price));

    const reason = document.createElement("p");
    reason.className = "ai-card__reason";
    const reasonLabel = document.createElement("span");
    reasonLabel.className = "ai-card__reason-label";
    reasonLabel.textContent = "Why it suits you";
    const reasonText = document.createElement("span");
    reasonText.textContent = String(product.reason || "A considered match for your selected preferences.");
    reason.append(reasonLabel, reasonText);

    const meta = document.createElement("div");
    meta.className = "ai-card__meta";

    const sizes = Array.isArray(product.sizes) ? product.sizes.filter(Boolean) : [];
    const colors = Array.isArray(product.colors) ? product.colors.filter(Boolean) : [];

    if (sizes.length) {
      const row = document.createElement("p");
      const label = document.createElement("span");
      label.textContent = "Available sizes";
      row.append(label, document.createTextNode(sizes.join(" · ")));
      meta.append(row);
    }

    if (colors.length) {
      const row = document.createElement("p");
      const label = document.createElement("span");
      label.textContent = "Available colours";
      row.append(label, document.createTextNode(colors.join(" · ")));
      meta.append(row);
    }

    if (typeof product.availability === "string" && product.availability !== "") {
      const row = document.createElement("p");
      const label = document.createElement("span");
      label.textContent = "Availability";
      row.append(label, document.createTextNode(product.availability));
      meta.append(row);
    }

    const actions = document.createElement("div");
    actions.className = "look-card__actions";
    const detailsLink = document.createElement("a");
    detailsLink.className = "text-link text-link--sm look-card__link";
    detailsLink.href = detailsUrl;
    detailsLink.textContent = "View product details";
    actions.append(detailsLink);

    body.append(heading, price, reason);

    if (meta.childElementCount > 0) {
      body.append(meta);
    }

    body.append(actions);
    article.append(visual, body);

    return article;
  }

  function renderResult(result) {
    const recommendations = Array.isArray(result.recommendations)
      ? result.recommendations
      : [];

    if (statusBox) {
      statusBox.textContent = typeof result.message === "string" && result.message.trim() !== ""
        ? result.message
        : recommendations.length
          ? "Here are the products that best match your preferences."
          : "No matching products were found.";
    }

    if (fitNote) {
      fitNote.textContent = typeof result.fit_note === "string" ? result.fit_note : "";
    }

    if (!recommendations.length) return;

    const fragment = document.createDocumentFragment();

    recommendations.forEach((product, index) => {
      const card = productCard(product, index);
      if (card) fragment.append(card);
    });

    if (grid && fragment.childElementCount > 0) {
      grid.append(fragment);
    }
  }

  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    if (submitting) return;

    clearFeedback();

    let preferences;

    try {
      preferences = readPreferences();
    } catch (error) {
      if (errorBox) errorBox.textContent = error.message;
      return;
    }

    if (results) results.hidden = false;
    if (statusBox) statusBox.textContent = "Finding products that match your preferences...";
    setBusy(true);

    const controller = new AbortController();
    const timer = window.setTimeout(() => controller.abort(), 45000);

    try {
      const response = await fetch(endpoint, {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(preferences),
        signal: controller.signal,
      });

      let result;

      try {
        result = await response.json();
      } catch (error) {
        throw new Error("The recommendation service returned an unreadable response. Please try again.");
      }

      if (!response.ok || !result || result.success !== true) {
        throw new Error(
          result && typeof result.message === "string" && result.message.trim() !== ""
            ? result.message
            : "We could not get recommendations right now. Please try again."
        );
      }

      renderResult(result);
    } catch (error) {
      if (statusBox) statusBox.textContent = "";
      if (grid) grid.replaceChildren();
      if (fitNote) fitNote.textContent = "";

      if (errorBox) {
        errorBox.textContent = error.name === "AbortError"
          ? "The AI stylist took too long to respond. Please try again."
          : error.message || "We could not get recommendations right now. Please try again.";
      }
    } finally {
      window.clearTimeout(timer);
      setBusy(false);
    }
  });
})();
