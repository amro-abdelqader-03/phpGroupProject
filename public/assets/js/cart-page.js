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
      image.src = item.image;
      image.alt = item.productName;
      image.width = 120;
      image.height = 160;
      const info = element("div", "cart-item__info");
      info.append(element("p", "cart-item__category", item.category === "dresses" ? "Wedding Dresses" : "Suits"));
      info.append(element("h2", "cart-item__name", item.productName));
      info.append(element("p", "cart-item__choice", `Size ${item.size} · ${item.color}`));
      info.append(element("p", "cart-item__quantity", `Quantity: ${item.quantity}`));
      const price = item.unitPrice == null ? "Price available on request" : new Intl.NumberFormat("en-JO", { style: "currency", currency: "JOD" }).format(item.unitPrice);
      info.append(element("p", "cart-item__price", price));
      const remove = element("button", "cart-item__remove", "Remove");
      remove.type = "button";
      remove.addEventListener("click", () => {
        window.ElanCart.remove(item.productId, item.size, item.color);
      });
      info.append(remove);
      const subtotal = item.subtotal == null ? "—" : new Intl.NumberFormat("en-JO", { style: "currency", currency: "JOD" }).format(item.subtotal);
      const subtotalEl = element("p", "cart-item__subtotal", subtotal);
      subtotalEl.setAttribute("aria-label", `Subtotal ${subtotal}`);
      row.append(image, info, subtotalEl);
      list.append(row);
    });
    host.append(list);
    const actions = element("div", "cart-actions");
    const checkout = element("a", "btn cart-checkout-link", "Proceed to Checkout");
    checkout.href = "checkout.php";
    actions.append(checkout);
    host.append(actions);
  }

  render();
  window.addEventListener("elan:cart-updated", render);
})();
