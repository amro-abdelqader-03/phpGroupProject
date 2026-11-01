/* Frontend-only Élan cart. Items are non-sensitive product selections. */
(() => {
  "use strict";
  let storageKey = "elan-bridal-cart:guest";

  function read() {
    try {
      const parsed = JSON.parse(window.localStorage.getItem(storageKey) || "[]");
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
    window.localStorage.setItem(storageKey, JSON.stringify(cart));
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
      existing.variantId = selection.variantId == null ? existing.variantId : Number(selection.variantId);
      existing.cartItemId = selection.cartItemId == null ? existing.cartItemId : Number(selection.cartItemId);
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
        variantId: selection.variantId == null ? null : Number(selection.variantId),
        cartItemId: selection.cartItemId == null ? null : Number(selection.cartItemId),
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

  async function syncQuantity(item, quantity) {
    if (!item.cartItemId) throw new Error("This item is not linked to your saved cart. Reload the page and try again.");
    const response = await fetch(`../app/controllers/cart-item.php?id=${encodeURIComponent(item.cartItemId)}`, {
      method: "PUT",
      credentials: "same-origin",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify({ variant_id: item.variantId, quantity }),
    });
    if (!response.ok) throw new Error("Could not update the saved bag quantity.");
  }

  async function deleteSavedItem(item) {
    if (!item.cartItemId) throw new Error("This item is not linked to your saved cart. Reload the page and try again.");
    const response = await fetch(`../app/controllers/cart-item.php?id=${encodeURIComponent(item.cartItemId)}`, {
      method: "DELETE",
      credentials: "same-origin",
      headers: { Accept: "application/json" },
    });
    if (!response.ok) throw new Error("Could not remove the saved bag item.");
  }

  async function initialize() {
    updateCount([]);
    try {
      const accountResponse = await fetch("auth-status.php", {
        credentials: "same-origin",
        headers: { Accept: "application/json" },
        cache: "no-store",
      });
      if (!accountResponse.ok) throw new Error("Could not identify the signed-in account.");
      const account = await accountResponse.json();

      if (!account.logged_in || !Number.isInteger(Number(account.user_id)) || Number(account.user_id) < 1) {
        storageKey = "elan-bridal-cart:guest";
        updateCount(read());
        return;
      }

      storageKey = `elan-bridal-cart:user:${Number(account.user_id)}`;
      const cartResponse = await fetch("../app/controllers/cart.php", {
        credentials: "same-origin",
        headers: { Accept: "application/json" },
        cache: "no-store",
      });
      if (cartResponse.status === 404) {
        save([]);
        return;
      }
      const cartResult = await cartResponse.json();
      if (!cartResponse.ok || !cartResult.success || !cartResult.data?.id) {
        throw new Error(cartResult.message || "Could not load your saved cart.");
      }

      const itemsResponse = await fetch(`../app/controllers/cart-item.php?cart_id=${encodeURIComponent(cartResult.data.id)}`, {
        credentials: "same-origin",
        headers: { Accept: "application/json" },
        cache: "no-store",
      });
      const itemsResult = await itemsResponse.json();
      if (!itemsResponse.ok || !itemsResult.success) {
        throw new Error(itemsResult.message || "Could not load your saved cart items.");
      }

      const cart = itemsResult.data.map((item) => ({
        productId: `products:${item.product_id}`,
        productName: item.product_name,
        category: "products",
        image: item.main_image || "",
        unitPrice: Number(item.price),
        size: item.size || "",
        color: item.color || "",
        variantId: item.variant_id == null ? null : Number(item.variant_id),
        cartItemId: Number(item.id),
        quantity: Number(item.quantity),
        subtotal: Number(item.price) * Number(item.quantity),
      }));
      save(cart);
    } catch (error) {
      updateCount([]);
      window.dispatchEvent(new CustomEvent("elan:cart-sync-error", { detail: error.message }));
    }
  }

  window.ElanCart = {
    get key() { return storageKey; },
    ready: null,
    read,
    add,
    remove,
    setQuantity,
    syncQuantity,
    deleteSavedItem,
    updateCount,
  };
  window.ElanCart.ready = initialize();
  window.ElanCart.ready.then(() => updateCount()).catch(() => updateCount([]));
  window.addEventListener("storage", (event) => {
    if (event.key === storageKey) updateCount();
  });
})();
