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

  function imageUrl(path) {
    if (!path) return "";
    // The API returns paths rooted at the project, such as /Data/...
    return new URL(path.replace(/^\//, ""), `${window.location.origin}/phpGroupProject/`).href;
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
    const images = Array.isArray(product.images) ? product.images : [];
    const primary = images.find((item) => Number(item.is_primary) === 1) || images[0];
    const variants = Array.isArray(product.variants) ? product.variants : [];
    const availableVariants = variants.filter((variant) => Number(variant.stock_quantity) > 0);
    const hasColorVariants = availableVariants.some((variant) => variant.color);
    const currency = new Intl.NumberFormat(undefined, { style: "currency", currency: "JOD" });

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
      const item = images[index] || primary;
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
    let quantity = 1;
    const sizes = [...new Set(availableVariants.map((variant) => variant.size).filter(Boolean))];
    sizes.forEach((size) => {
      const button = document.createElement("button");
      button.type = "button";
      button.className = "detail-size-button";
      button.textContent = size;
      button.setAttribute("aria-pressed", "false");
      button.addEventListener("click", () => {
        const candidates = availableVariants.filter((variant) => variant.size === size);
        selectedVariant = candidates.find((variant) => !hasColorVariants || variant.color === selectedVariant?.color) || candidates[0];
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
        const currentSize = selectedVariant && selectedVariant.size;
        selectedVariant = availableVariants.find((variant) => variant.color === color && (!currentSize || variant.size === currentSize)) || availableVariants.find((variant) => variant.color === color);
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
      const hasSize = Boolean(selectedVariant);
      const hasColor = !hasColorVariants || Boolean(selectedVariant && selectedVariant.color);
      byId("size-error").textContent = hasSize ? "" : "Please select an available size.";
      byId("color-error").textContent = hasColor ? "" : "Please select a colour.";
      if (!hasSize || !hasColor) {
        byId("detail-form-error").textContent = "Choose the available options before adding this product to your bag.";
        return;
      }
      try {
        const cartEndpoint = new URL("../app/controllers/cart.php", window.location.href);
        const cartResponse = await fetch(cartEndpoint, {
          method: "POST",
          credentials: "same-origin",
          headers: { "Content-Type": "application/json", Accept: "application/json" },
          body: JSON.stringify({ user_id: 2 }),
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
          size: selectedVariant.size,
          color: selectedVariant.color || "",
          quantity,
        });
        byId("detail-form-error").textContent = "";
        byId("detail-cart-status").textContent = `${name} (${selectedVariant.size}) added to your bag. Your bag now contains ${cart.reduce((total, item) => total + item.quantity, 0)} items.`;
      } catch (error) {
        byId("detail-cart-status").textContent = error.message || "Could not add this product to your bag. Please try again.";
      }
    };
  }

  loadProduct();
})();
