/* Frontend-only Élan cart. Items are non-sensitive product selections. */
(() => {
  "use strict";
  const STORAGE_KEY = "elan-bridal-cart";

  function read() {
    try {
      const parsed = JSON.parse(window.localStorage.getItem(STORAGE_KEY) || "[]");
      return Array.isArray(parsed) ? parsed.filter((item) => item && item.productId && Number(item.quantity) > 0) : [];
    } catch (error) {
      return [];
    }
  }

  function updateCount(cart = read()) {
    const count = cart.reduce((sum, item) => sum + Number(item.quantity || 0), 0);
    document.querySelectorAll("[data-cart-count]").forEach((node) => {
      node.textContent = String(count);
      const link = node.closest("a");
      if (link) link.setAttribute("aria-label", `Shopping bag, ${count} ${count === 1 ? "item" : "items"}`);
    });
  }

  function save(cart) {
    window.localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
    updateCount(cart);
    window.dispatchEvent(new CustomEvent("elan:cart-updated", { detail: { cart } }));
  }

  function add(selection) {
    const cart = read();
    const amount = Math.max(1, Math.floor(Number(selection.quantity) || 1));
    const productId = `${selection.category}:${selection.productId}`;
    const existing = cart.find((item) => item.productId === productId && item.size === selection.size && item.color === selection.color);
    if (existing) {
      existing.quantity = Number(existing.quantity) + amount;
      existing.subtotal = existing.unitPrice == null ? null : existing.unitPrice * existing.quantity;
    } else {
      cart.push({
        productId,
        productName: selection.productName,
        category: selection.category,
        image: selection.image,
        unitPrice: selection.unitPrice == null ? null : Number(selection.unitPrice),
        size: selection.size,
        color: selection.color,
        quantity: amount,
        subtotal: selection.unitPrice == null ? null : Number(selection.unitPrice) * amount,
      });
    }
    save(cart);
    return cart;
  }

  function remove(productId, size, color) {
    save(read().filter((item) => !(item.productId === productId && item.size === size && item.color === color)));
  }

  function setQuantity(productId, size, color, quantity) {
    const nextQuantity = Math.max(1, Math.floor(Number(quantity) || 1));
    const cart = read();
    const item = cart.find((entry) => entry.productId === productId && entry.size === size && entry.color === color);
    if (!item) return cart;
    item.quantity = nextQuantity;
    item.subtotal = item.unitPrice == null ? null : item.unitPrice * nextQuantity;
    save(cart);
    return cart;
  }

  window.ElanCart = { key: STORAGE_KEY, read, add, remove, setQuantity, updateCount };
  document.addEventListener("DOMContentLoaded", () => updateCount());
  window.addEventListener("storage", (event) => {
    if (event.key === STORAGE_KEY) updateCount();
  });
})();
