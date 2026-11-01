/* Product detail page backed by the admin products GET endpoint. */
(() => {
  "use strict";

  const detail = document.getElementById("product-details");
  const missing = document.getElementById("product-missing");
  const params = new URLSearchParams(window.location.search);
  const productId = params.get("id");

  const byId = (id) => document.getElementById(id);

  function showMissing(message) {
    if (detail) detail.hidden = true;
    if (missing) {
      missing.hidden = Boolean(productId);
      if (!productId) {
        const paragraph = missing.querySelector("p:not(.eyebrow)");
        if (paragraph && message) paragraph.textContent = message;
      }
    }
  }

  // function imageUrl(path) {
  //   if (!path) return "";
  //   // The API returns paths rooted at the project, such as /Data/...
  //   return new URL(path.replace(/^\//, ""), `${window.location.origin}/phpGroupProject/`).href;
  // }






  function imageUrl(path) {
  if (!path) return "";

  if (/^https?:\/\//i.test(path)) {
    return path;
  }

  const projectRoot = new URL("../", window.location.href);

  return new URL(
    path.replace(/^\/+/, ""),
    projectRoot
  ).href;
}





  async function loadProduct() {
    if (!productId) {
      showMissing("Choose a product from one of our current collections to see its details.");
      return;
    }
    if (!detail || !/^\d+$/.test(productId)) {
      showMissing("The product ID is invalid.");
      return;
    }

    try {
      const endpoint = new URL("../app/controllers/admin-products.php", window.location.href);
      endpoint.searchParams.set("id", productId);
      const response = await fetch(endpoint, { headers: { Accept: "application/json" } });
      const result = await response.json();
      if (!response.ok || !result.success || !result.data) throw new Error(result.message || "Product not found.");
      renderProduct(result.data);
    } catch (error) {
      showMissing(error.message || "We could not load this product. Please try again later.");
    }
  }

  function renderProduct(product) {
    if (!product || !detail) return showMissing();
    const name = product.product_name || "Product";
    // const images = Array.isArray(product.images) ? product.images : [];
    // const primary = images.find((item) => Number(item.is_primary) === 1) || images[0];

const apiImages = Array.isArray(product.images) ? product.images : [];

const isDress = new URLSearchParams(window.location.search).get("category") === "dresses";

const dressImage = isDress ? window.ELAN_DB_DRESS?.image : null;

const images = [
  ...(dressImage ? [{ image_url: dressImage, is_primary: 1 }] : []),
  ...apiImages
].filter((item, index, all) => {
  return item.image_url &&
    all.findIndex(other => other.image_url === item.image_url) === index;
});

const primary = images[0] || null;

    const variants = Array.isArray(product.variants) ? product.variants : [];
    const availableVariants = variants.filter((variant) => Number(variant.stock_quantity) > 0);
    const hasColorVariants = availableVariants.some((variant) => variant.color);
    const currency = new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" });

    detail.hidden = false;
    byId("detail-name").textContent = name;
    byId("detail-description").textContent = product.description || "";
    byId("detail-price").textContent = product.price == null ? "Price available on request" : currency.format(Number(product.price));
    byId("detail-category").textContent = "Élan Bridal Collection";
    byId("detail-breadcrumb-name").textContent = name;
    document.title = `${name} | ÉLAN BRIDAL`;
    const mainImage = byId("detail-primary-image");
    mainImage.src = imageUrl(primary && primary.image_url);
    mainImage.alt = `${name} product photograph`;
    const galleryButtons = [...document.querySelectorAll("[data-gallery-view]")];
    const thumbs = [...document.querySelectorAll("[data-thumb-image]")];
    galleryButtons.forEach((button, index) => {
      // const item = images[index] || primary;
const item = images[index];


      const src = imageUrl(item && item.image_url);
      button.hidden = !item;
      if (thumbs[index]) {
        thumbs[index].src = src;
        thumbs[index].alt = "";
      }
      button.onclick = () => {
        if (!item) return;
        mainImage.src = src;
        mainImage.alt = `${name} product photograph ${index + 1}`;
        byId("detail-image-label").textContent = index === 0 ? "Primary image" : `Image ${index + 1}`;
        galleryButtons.forEach((other) => {
          const active = other === button;
          other.classList.toggle("is-selected", active);
          other.setAttribute("aria-pressed", String(active));
        });
      };
    });

    const sizeContainer = byId("size-options");
    const colorContainer = byId("color-options");
    sizeContainer.replaceChildren();
    colorContainer.replaceChildren();
    let selectedVariant = null;
    let selectedSize = "";
    let selectedColor = "";
    let quantity = 1;
    const sizes = [...new Set(availableVariants.map((variant) => variant.size).filter(Boolean))];
    sizes.forEach((size) => {
      const button = document.createElement("button");
      button.type = "button";
      button.className = "detail-size-button";
      button.textContent = size;
      button.setAttribute("aria-pressed", "false");
      button.addEventListener("click", () => {
        selectedSize = size;
        selectedVariant = availableVariants.find((variant) =>
          variant.size === selectedSize
          && (!hasColorVariants || variant.color === selectedColor)
        ) || null;
        if (hasColorVariants && selectedColor && !selectedVariant) {
          selectedColor = "";
          byId("selected-color-label").textContent = "Choose a colour";
          colorContainer.querySelectorAll("button").forEach((option) => option.setAttribute("aria-pressed", "false"));
        }
        sizeContainer.querySelectorAll("button").forEach((option) => option.setAttribute("aria-pressed", String(option === button)));
        syncQuantityLimit();
        byId("size-error").textContent = "";
        byId("detail-form-error").textContent = "";
      });
      sizeContainer.append(button);
    });

    const colors = [...new Set(availableVariants.map((variant) => variant.color).filter(Boolean))];
    colors.forEach((color) => {
      const button = document.createElement("button");
      button.type = "button";
      button.className = "detail-color-button";
      button.setAttribute("aria-label", color);
      button.setAttribute("aria-pressed", "false");
      button.style.setProperty("--swatch", color);
      const swatch = document.createElement("span");
      swatch.className = "detail-color-button__swatch";
      swatch.setAttribute("aria-hidden", "true");
      const label = document.createElement("span");
      label.className = "detail-color-button__name";
      label.textContent = color;
      button.append(swatch, label);
      button.addEventListener("click", () => {
        selectedColor = color;
        selectedVariant = availableVariants.find((variant) =>
          variant.color === selectedColor
          && (!selectedSize || variant.size === selectedSize)
        ) || null;
        colorContainer.querySelectorAll("button").forEach((option) => option.setAttribute("aria-pressed", String(option === button)));
        byId("selected-color-label").textContent = color;
        syncQuantityLimit();
        byId("color-error").textContent = "";
        byId("detail-form-error").textContent = "";
      });
      colorContainer.append(button);
    });

    const colorField = colorContainer.closest(".detail-option");
    colorField.hidden = !hasColorVariants;
    byId("selected-color-label").textContent = hasColorVariants ? "Choose a colour" : "";
    if (!sizes.length) {
      sizeContainer.innerHTML = "<span>Currently unavailable</span>";
    }

    const quantityValue = byId("quantity-value");
    function syncQuantityLimit() {
      const stock = selectedVariant ? Number(selectedVariant.stock_quantity) : Number(product.stock_quantity || 0);
      quantity = Math.min(quantity, Math.max(1, stock));
      quantityValue.textContent = String(quantity);
      byId("quantity-plus").disabled = quantity >= stock;
    }
    syncQuantityLimit();
    byId("quantity-minus").onclick = () => {
      quantity = Math.max(1, quantity - 1);
      syncQuantityLimit();
    };
    byId("quantity-plus").onclick = () => {
      const stock = selectedVariant ? Number(selectedVariant.stock_quantity) : Number(product.stock_quantity || 0);
      if (quantity < stock) quantity += 1;
      syncQuantityLimit();
    };

    byId("detail-form").onsubmit = async (event) => {
      event.preventDefault();
      const hasSize = Boolean(selectedSize);
      const hasColor = !hasColorVariants || Boolean(selectedColor && selectedVariant);
      byId("size-error").textContent = hasSize ? "" : "Please select an available size.";
      byId("color-error").textContent = hasColor ? "" : "Please select a colour.";
      if (!hasSize || !hasColor || !selectedVariant) {
        byId("detail-form-error").textContent = "Choose the available options before adding this product to your bag.";
        return;
      }
      try {
        await window.ElanCart.ready;
        const cartEndpoint = new URL("../app/controllers/cart.php", window.location.href);
        const cartResponse = await fetch(cartEndpoint, {
          method: "POST",
          credentials: "same-origin",
          headers: { "Content-Type": "application/json", Accept: "application/json" },
          body: JSON.stringify({}),
        });
        const cartResult = await cartResponse.json();
        if (!cartResponse.ok || !cartResult.success || !cartResult.data?.id) {
          throw new Error("Could not create or load your cart.");
        }

        const itemEndpoint = new URL("../app/controllers/cart-item.php", window.location.href);
        const itemResponse = await fetch(itemEndpoint, {
          method: "POST",
          credentials: "same-origin",
          headers: { "Content-Type": "application/json", Accept: "application/json" },
          body: JSON.stringify({
            cart_id: Number(cartResult.data.id),
            product_id: Number(product.product_id),
            variant_id: Number(selectedVariant.variant_id),
            quantity,
          }),
        });
        const itemResult = await itemResponse.json();
        if (!itemResponse.ok || !itemResult.success) {
          throw new Error(itemResult.message || "Could not add this product to your cart.");
        }

        const cart = window.ElanCart.add({
          productId: String(product.product_id),
          productName: name,
          category: "products",
          image: mainImage.src,
          unitPrice: product.price,
          size: selectedSize,
          color: selectedVariant.color || "",
          requiresSize: true,
          variantId: Number(selectedVariant.variant_id),
          cartItemId: Number(itemResult.data.id),
          quantity,
        });
        byId("detail-form-error").textContent = "";
        byId("detail-cart-status").textContent = `${name} (${selectedSize}) added to your bag. Your bag now contains ${cart.reduce((total, item) => total + item.quantity, 0)} items.`;
      } catch (error) {
        byId("detail-cart-status").textContent = error.message || "Could not add this product to your bag. Please try again.";
      }
    };

    renderReviews(Number(product.product_id));
  }

  async function renderReviews(productId) {
    const section = document.createElement("section");
    section.className = "product-reviews";
    section.setAttribute("aria-labelledby", "product-reviews-title");
    const heading = document.createElement("h2");
    heading.id = "product-reviews-title";
    heading.textContent = "Customer reviews";
    const summary = document.createElement("p");
    summary.className = "product-reviews__summary";
    const list = document.createElement("div");
    list.className = "product-reviews__list";
    list.setAttribute("aria-live", "polite");
    const status = document.createElement("p");
    status.className = "product-reviews__status";
    status.setAttribute("role", "status");
    const form = document.createElement("form");
    form.className = "product-reviews__form";
    form.innerHTML = '<label for="review-rating">Your rating</label><select id="review-rating" name="rating" required><option value="">Choose a rating</option><option value="5">5 - Excellent</option><option value="4">4 - Very good</option><option value="3">3 - Good</option><option value="2">2 - Fair</option><option value="1">1 - Poor</option></select><label for="review-text">Your review</label><textarea id="review-text" name="review_text" rows="4" maxlength="1200" required></textarea><button class="btn" type="submit">Submit review</button>';
    section.append(heading, summary, list, status, form);
    detail.append(section);

    const endpoint = new URL("../app/controllers/product-reviews.php", window.location.href);
    endpoint.searchParams.set("product_id", String(productId));
    const authEndpoint = new URL("auth-status.php", window.location.href);
    let csrfToken = "";
    try {
      const authResponse = await fetch(authEndpoint, { credentials: "same-origin", cache: "no-store" });
      const auth = await authResponse.json();
      csrfToken = auth.csrf_token || "";
      if (!auth.logged_in) {
        const login = document.createElement("a");
        login.href = "login.php";
        login.textContent = "Sign in to leave a review";
        form.replaceWith(login);
      }
    } catch (error) {
      status.textContent = "Review options are temporarily unavailable.";
    }

    async function loadReviews() {
      const response = await fetch(endpoint, { headers: { Accept: "application/json" } });
      const data = await response.json();
      if (!response.ok || !data.success) throw new Error(data.message || "Reviews could not be loaded.");
      summary.textContent = data.count ? `${Number(data.average).toFixed(1)} out of 5 · ${data.count} ${data.count === 1 ? "review" : "reviews"}` : "No reviews yet.";
      list.replaceChildren();
      data.reviews.forEach((review) => {
        const article = document.createElement("article");
        article.className = "product-review";
        const rating = document.createElement("p");
        rating.className = "product-review__rating";
        rating.textContent = `${"★".repeat(Number(review.rating))}${"☆".repeat(5 - Number(review.rating))} ${review.rating}/5`;
        const author = document.createElement("p");
        author.className = "product-review__author";
        author.textContent = `${review.full_name} · ${review.created_at}`;
        const copy = document.createElement("p");
        copy.textContent = review.review_text;
        article.append(rating, author, copy);
        list.append(article);
      });
    }

    try {
      await loadReviews();
    } catch (error) {
      status.textContent = error.message || "Reviews could not be loaded.";
    }

    if (form.isConnected) {
      form.addEventListener("submit", async (event) => {
        event.preventDefault();
        status.textContent = "";
        const submit = form.querySelector('button[type="submit"]');
        submit.disabled = true;
        try {
          const response = await fetch(endpoint, {
            method: "POST",
            credentials: "same-origin",
            headers: { "Content-Type": "application/json", Accept: "application/json" },
            body: JSON.stringify({
              csrf_token: csrfToken,
              product_id: productId,
              rating: form.elements.rating.value,
              review_text: form.elements.review_text.value,
            }),
          });
          const data = await response.json();
          if (!response.ok || !data.success) throw new Error(data.message || "Your review could not be saved.");
          form.reset();
          status.textContent = "Thank you. Your review has been published.";
          await loadReviews();
        } catch (error) {
          status.textContent = error.message || "Your review could not be saved.";
        } finally {
          submit.disabled = false;
        }
      });
    }
  }

  loadProduct();
})();
