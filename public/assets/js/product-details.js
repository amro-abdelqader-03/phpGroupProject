/* Shared detail renderer for the suits and dress collections. */
(() => {
  "use strict";
  const params = new URLSearchParams(window.location.search);
  const collectionKey = params.get("category");
  const slug = params.get("slug");
 
const isDatabaseDress = collectionKey === "dresses" && Boolean(window.ELAN_DB_DRESS);

const collection = window.ELAN_COLLECTIONS && window.ELAN_COLLECTIONS[collectionKey];

const match = isDatabaseDress
  ? {
      slug: String(window.ELAN_DB_DRESS.id),
      name: window.ELAN_DB_DRESS.name,
      desc: window.ELAN_DB_DRESS.description,
      price: window.ELAN_DB_DRESS.price
    }
  : collection && slug
    ? Object.values(collection.categories).flat()
        .find((product) => product.slug === slug)
    : null;


  const detail = document.getElementById("product-details");
  const missing = document.getElementById("product-missing");
  if (!match || !detail) {
    if (missing) missing.hidden = false;
    return;
  }

  const isDress = collectionKey === "dresses";
  const categoryName = isDress ? "Wedding Dresses" : "Suits";
  const collectionUrl = isDress ? "dresses.php" : "suits.php";


const image = isDatabaseDress
  ? "/project_3" + window.ELAN_DB_DRESS.image
  : `${collection.base}${match.slug}${collection.primarySuffix}`;
  const fullAlt = isDress
    ? `Model wearing ${match.name} in a full-length bridal portrait.`
    : `Model wearing ${match.name}, photographed in full length.`;
  const label = (id) => document.getElementById(id);
  const sizeOptions = isDress ? ["UK 4", "UK 6", "UK 8", "UK 10", "UK 12", "UK 14", "UK 16", "UK 18"] : ["36", "38", "40", "42", "44", "46", "48"];
  const colorOptions = isDress
    ? [{ name: "Ivory", value: "#F4EFE8" }, { name: "Champagne", value: "#D9C6AE" }, { name: "Soft White", value: "#FFFFFF" }, { name: "Blush", value: "#D6B6AC" }]
    : [{ name: "Midnight", value: "#24282F" }, { name: "Charcoal", value: "#55514F" }, { name: "Ivory", value: "#EDE7DD" }, { name: "Navy", value: "#26354A" }];

  detail.hidden = false;
  label("detail-name").textContent = match.name;
  
  if (isDatabaseDress) {
  label("detail-price").textContent =
    Number(match.price).toFixed(2) + " JOD";
}
  label("detail-category").textContent = isDress ? "The Bridal Collection" : "The Groom's Collection";
  label("detail-collection-link").textContent = categoryName;
  label("detail-collection-link").href = collectionUrl;
  label("detail-back-link").href = collectionUrl;
  label("detail-breadcrumb-name").textContent = match.name;
  document.title = `${match.name} | ÉLAN BRIDAL`;

 
const mainImage = label("detail-primary-image");

const dressImages = isDatabaseDress
  ? [
      window.ELAN_DB_DRESS.image,
      ...window.ELAN_DB_DRESS.images.map((item) => item.image_url)
    ]
      .filter(Boolean)
      .filter((path, index, all) => all.indexOf(path) === index)
      .map((path) =>
        path.startsWith("/Data/") ? "/project_3" + path : path
      )
  : [];

mainImage.src = image;
mainImage.alt = fullAlt;

document.querySelectorAll("[data-thumb-image]").forEach((thumb, index) => {
  if (isDatabaseDress) {
    const imagePath = dressImages[index];

    if (imagePath) {
      thumb.src = imagePath;
      thumb.alt = `${match.name} - Image ${index + 1}`;
    } else {
      thumb.closest("[data-gallery-view]").hidden = true;
    }
  } else {
    thumb.src = image;
    thumb.alt = "";
  }
});


  const sizeContainer = label("size-options");
  const colorContainer = label("color-options");
  let selectedSize = "";
  let selectedColor = "";
  let quantity = 1;

  sizeOptions.forEach((size) => {
    const button = document.createElement("button");
    button.type = "button";
    button.className = "detail-size-button";
    button.textContent = size;
    button.setAttribute("aria-pressed", "false");
    button.addEventListener("click", () => {
      selectedSize = size;
      sizeContainer.querySelectorAll("button").forEach((option) => option.setAttribute("aria-pressed", String(option === button)));
      label("size-error").textContent = "";
      label("detail-form-error").textContent = "";
    });
    sizeContainer.append(button);
  });

  colorOptions.forEach((color) => {
    const button = document.createElement("button");
    button.type = "button";
    button.className = "detail-color-button";
    button.setAttribute("aria-label", color.name);
    button.setAttribute("aria-pressed", "false");
    button.style.setProperty("--swatch", color.value);
    button.innerHTML = `<span class="detail-color-button__swatch" aria-hidden="true"></span><span class="detail-color-button__name">${color.name}</span>`;
    button.addEventListener("click", () => {
      selectedColor = color.name;
      colorContainer.querySelectorAll("button").forEach((option) => option.setAttribute("aria-pressed", String(option === button)));
      label("selected-color-label").textContent = color.name;
      label("color-error").textContent = "";
      label("detail-form-error").textContent = "";
    });
    colorContainer.append(button);
  });

  const galleryLabels = ["Full look", "Upper silhouette crop", isDress ? "Bodice detail crop" : "Jacket detail crop", "Lower silhouette crop"];
  const galleryAlts = [fullAlt, `Upper silhouette crop from the campaign photograph of ${match.name}.`, `${isDress ? "Bodice" : "Jacket"} detail crop from the campaign photograph of ${match.name}.`, `Lower silhouette crop from the campaign photograph of ${match.name}.`];
  
document.querySelectorAll("[data-gallery-view]").forEach((button) => {
  button.addEventListener("click", () => {
    const view = Number(button.dataset.galleryView);

    if (isDatabaseDress) {
      if (!dressImages[view]) return;

      mainImage.src = dressImages[view];

      // Remove the old crop effect
      mainImage.removeAttribute("data-crop");

      mainImage.alt = `${match.name} - Image ${view + 1}`;

      label("detail-image-label").textContent =
        `Image ${view + 1}`;

    } else {
      // Keep the original suits behavior
      mainImage.dataset.crop = String(view);
      mainImage.alt = galleryAlts[view];

      label("detail-image-label").textContent =
        galleryLabels[view];
    }

    document.querySelectorAll("[data-gallery-view]").forEach((thumb) => {
      const active = thumb === button;

      thumb.classList.toggle("is-selected", active);
      thumb.setAttribute("aria-pressed", String(active));
    });
  });
});


  label("quantity-minus").addEventListener("click", () => {
    quantity = Math.max(1, quantity - 1);
    label("quantity-value").textContent = String(quantity);
  });
  label("quantity-plus").addEventListener("click", () => {
    quantity += 1;
    label("quantity-value").textContent = String(quantity);
  });

  label("detail-form").addEventListener("submit", (event) => {
    event.preventDefault();
    const hasSize = Boolean(selectedSize);
    const hasColor = Boolean(selectedColor);
    label("size-error").textContent = hasSize ? "" : "Please select a size.";
    label("color-error").textContent = hasColor ? "" : "Please select a colour.";
    if (!hasSize || !hasColor) {
      label("detail-form-error").textContent = "Choose a size and colour before adding this look to your bag.";
      return;
    }
    label("detail-form-error").textContent = "";
    try {
      const cart = window.ElanCart.add({
        productId: match.slug,
        productName: match.name,
        category: collectionKey,
        image,
        unitPrice: null,
        size: selectedSize,
        color: selectedColor,
        quantity,
      });
      label("detail-cart-status").textContent = `${match.name} (${selectedSize}, ${selectedColor}) added to your bag. Your bag now contains ${cart.reduce((total, item) => total + item.quantity, 0)} items. Pricing and inventory are not connected.`;
    } catch (error) {
      label("detail-cart-status").textContent = "Your browser could not save the bag. Please check local storage settings and try again.";
    }
  });
})();
