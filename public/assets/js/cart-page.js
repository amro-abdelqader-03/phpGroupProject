/* Renders the local, frontend-only shopping bag. */
(() => {
  "use strict";
  const host = document.getElementById("cart-content");
  if (!host || !window.ElanCart) return;

  function element(tag, className, text) {
    const node = document.createElement(tag);
    if (className) node.className = className;
    if (text !== undefined) node.textContent = text;
    return node;
  }

  function imageUrl(path) {
    if (!path) return "";
    if (/^(https?:)?\/\//i.test(path)) return path;

    const projectRoot = new URL("../", window.location.href);
    return new URL(path.replace(/^\/+/, ""), projectRoot).href;
  }

  function render() {
    const cart = window.ElanCart.read();
    host.replaceChildren();
    if (!cart.length) {
      const empty = element("div", "cart-empty");
      empty.append(element("h2", "cart-empty__title", "Your bag is waiting."));
      empty.append(element("p", "cart-empty__text", "Explore the collections and save a look that feels like yours."));
      const links = element("div", "cart-empty__links");
      const suitLink = element("a", "btn cart-collection-link", "Explore Suits");
      suitLink.href = "suits.php";
      const dressLink = element("a", "btn cart-collection-link cart-collection-link--outline", "Explore Dresses");
      dressLink.href = "dresses.php";
      links.append(suitLink, dressLink);
      empty.append(links);
      host.append(empty);
      return;
    }

    const list = element("div", "cart-list");
    cart.forEach((item) => {
      const row = element("article", "cart-item");
      const image = element("img", "cart-item__image");
      image.src = imageUrl(item.image || item.main_image || item.image_url);
      image.alt = item.productName;
      image.width = 120;
      image.height = 160;
      image.loading = "lazy";
      image.decoding = "async";
      const info = element("div", "cart-item__info");
      info.append(element("p", "cart-item__category", item.category === "dresses" ? "Wedding Dresses" : "Suits"));
      info.append(element("h2", "cart-item__name", item.productName));
      const options = [];
      if (item.size) options.push(`Size ${item.size}`);
      if (item.color) options.push(item.color);
      if (options.length) {
        info.append(element("p", "cart-item__choice", options.join(" · ")));
      } else if (item.requiresSize) {
        const missingSize = element("p", "cart-item__choice", "Size not selected.");
        const productId = /^products:(\d+)$/.exec(String(item.productId));
        if (productId) {
          const chooseSize = element("a", "", "Choose a size");
          chooseSize.href = `product.php?id=${productId[1]}`;
          missingSize.append(" ", chooseSize);
        }
        info.append(missingSize);
      }
      const quantity = element("div", "cart-item__quantity-control");
      quantity.setAttribute("role", "group");
      quantity.setAttribute("aria-label", `Quantity for ${item.productName}`);
      const decrease = element("button", "cart-qty-button", "−");
      decrease.type = "button";
      decrease.disabled = Number(item.quantity) <= 1;
      decrease.setAttribute("aria-label", `Decrease ${item.productName} quantity`);
      decrease.addEventListener("click", async () => {
        const nextQuantity = Number(item.quantity) - 1;
        try {
          await window.ElanCart.syncQuantity(item, nextQuantity);
          window.ElanCart.setQuantity(item.productId, item.size, item.color, nextQuantity);
        }
        catch (error) { window.dispatchEvent(new CustomEvent("elan:cart-sync-error", { detail: error.message })); }
      });
      const count = element("output", "cart-qty-count", String(item.quantity));
      const increase = element("button", "cart-qty-button", "+");
      increase.type = "button";
      increase.setAttribute("aria-label", `Increase ${item.productName} quantity`);
      increase.addEventListener("click", async () => {
        const nextQuantity = Number(item.quantity) + 1;
        try {
          await window.ElanCart.syncQuantity(item, nextQuantity);
          window.ElanCart.setQuantity(item.productId, item.size, item.color, nextQuantity);
        }
        catch (error) { window.dispatchEvent(new CustomEvent("elan:cart-sync-error", { detail: error.message })); }
      });
      quantity.append(decrease, count, increase);
      info.append(quantity);
      const price = item.unitPrice == null ? "Price available on request" : new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(item.unitPrice);
      info.append(element("p", "cart-item__price", price));
      const remove = element("button", "cart-item__remove", "Remove");
      remove.type = "button";
      remove.addEventListener("click", async () => {
        try { await window.ElanCart.deleteSavedItem(item); }
        catch (error) { window.dispatchEvent(new CustomEvent("elan:cart-sync-error", { detail: error.message })); return; }
        window.ElanCart.remove(item.productId, item.size, item.color);
      });
      info.append(remove);
      const subtotal = item.subtotal == null ? "—" : new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(item.subtotal);
      const subtotalEl = element("p", "cart-item__subtotal", subtotal);
      subtotalEl.setAttribute("aria-label", `Subtotal ${subtotal}`);
      row.append(image, info, subtotalEl);
      list.append(row);
    });
    host.append(list);
    const actions = element("div", "cart-actions");
    const checkout = element("button", "btn cart-checkout-link", "Proceed to Checkout");
    checkout.type = "button";
    checkout.addEventListener("click", () => { window.location.href = "checkout.php"; });
    actions.append(checkout);
    host.append(actions);
  }

  render();
  window.addEventListener("elan:cart-sync-error", (event) => {
    const error = document.getElementById("cart-error");
    if (error) error.textContent = event.detail || "Your bag could not be synchronized. Please try again.";
  });
  window.addEventListener("elan:cart-updated", render);
})();
