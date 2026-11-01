/* Local checkout preview with backend integration via Fetch */
(() => {
  "use strict";
  const form = document.getElementById("checkout-form");
  const itemHost = document.getElementById("checkout-items");
  if (!form || !itemHost || !window.ElanCart) return;

  const money = (amount) => new Intl.NumberFormat("en-JO", {
    style: "currency",
    currency: "JOD",
    minimumFractionDigits: 3,
    maximumFractionDigits: 3,
  }).format(amount);

  const make = (tag, className, text) => {
    const node = document.createElement(tag);
    if (className) node.className = className;
    if (text !== undefined) node.textContent = text;
    return node;
  };

  function renderSummary() {
    const cart = window.ElanCart.read();
    itemHost.replaceChildren();
    const isEmpty = cart.length === 0;
    document.getElementById("checkout-empty").hidden = !isEmpty;
    document.getElementById("checkout-items").hidden = isEmpty;

    let subtotal = 0;
    let allPricesKnown = !isEmpty;
    cart.forEach((item) => {
      const hasPrice = item.unitPrice !== null && item.unitPrice !== undefined && Number.isFinite(Number(item.unitPrice));
      const unitPrice = hasPrice ? Number(item.unitPrice) : null;
      const itemSubtotal = hasPrice ? unitPrice * Number(item.quantity) : null;
      if (itemSubtotal === null) allPricesKnown = false;
      else subtotal += itemSubtotal;

      const row = make("article", "checkout-item");
      const image = make("img", "checkout-item__image");
      image.src = item.image;
      image.alt = item.productName;
      image.width = 76;
      image.height = 102;
      image.loading = "lazy";
      const info = make("div", "checkout-item__info");
      info.append(make("p", "checkout-item__category", item.category === "dresses" ? "Dress" : "Suit"));
      info.append(make("h3", "checkout-item__name", item.productName));
      info.append(make("p", "checkout-item__variant", `Size ${item.size} · ${item.color}`));
      info.append(make("p", "checkout-item__price", unitPrice === null ? "Unit price unavailable" : `Unit price: ${money(unitPrice)}`));

      const quantity = make("div", "checkout-item__quantity");
      quantity.setAttribute("role", "group");
      quantity.setAttribute("aria-label", `Quantity for ${item.productName}`);
      const decrease = make("button", "checkout-qty-button", "−");
      decrease.type = "button";
      decrease.setAttribute("aria-label", `Decrease ${item.productName} quantity`);
      decrease.disabled = Number(item.quantity) <= 1;
      decrease.addEventListener("click", async () => {
        const nextQuantity = Number(item.quantity) - 1;
        try {
          await window.ElanCart.syncQuantity(item, nextQuantity);
          window.ElanCart.setQuantity(item.productId, item.size, item.color, nextQuantity);
        } catch (error) {
          document.getElementById("checkout-result").textContent = error.message;
          document.getElementById("checkout-result").classList.add("is-error");
        }
      });
      const count = make("output", "checkout-item__count", String(item.quantity));
      count.setAttribute("aria-live", "polite");
      const increase = make("button", "checkout-qty-button", "+");
      increase.type = "button";
      increase.setAttribute("aria-label", `Increase ${item.productName} quantity`);
      increase.addEventListener("click", async () => {
        const nextQuantity = Number(item.quantity) + 1;
        try {
          await window.ElanCart.syncQuantity(item, nextQuantity);
          window.ElanCart.setQuantity(item.productId, item.size, item.color, nextQuantity);
        } catch (error) {
          document.getElementById("checkout-result").textContent = error.message;
          document.getElementById("checkout-result").classList.add("is-error");
        }
      });
      quantity.append(decrease, count, increase);
      info.append(quantity);

      const amount = make("p", "checkout-item__subtotal", itemSubtotal === null ? "Unavailable" : money(itemSubtotal));
      amount.setAttribute("aria-label", `Item subtotal ${itemSubtotal === null ? "unavailable" : money(itemSubtotal)}`);
      row.append(image, info, amount);
      itemHost.append(row);
    });

    const shippingCost = 5.00; // Fixed shipping cost matching backend
    const totalAmount = subtotal + shippingCost;

    document.getElementById("checkout-subtotal").textContent = allPricesKnown ? money(subtotal) : "Unavailable";
    document.getElementById("checkout-delivery").textContent = allPricesKnown ? money(shippingCost) : "Unavailable";
    document.getElementById("checkout-total").textContent = allPricesKnown ? money(totalAmount) : "Unavailable";
  }

  const requiredFields = [
    ["delivery-name", "Please enter your full name."],
    ["delivery-phone", "Please enter your phone number."],
    ["delivery-city", "Please enter your city."],
    ["delivery-area", "Please enter your area or district."],
    ["delivery-street", "Please enter your street address."],
  ];

  function validateAddress() {
    let valid = true;
    let firstInvalid = null;
    requiredFields.forEach(([id, emptyMessage]) => {
      const input = document.getElementById(id);
      const error = document.getElementById(`${id}-error`);
      let message = "";
      if (!input.value.trim()) message = emptyMessage;
      if (id === "delivery-phone" && input.value.trim()) {
        const digits = input.value.replace(/\D/g, "");
        if (digits.length < 7 || digits.length > 15) message = "Enter a phone number with 7 to 15 digits.";
      }
      error.textContent = message;
      input.setAttribute("aria-invalid", String(Boolean(message)));
      if (message) {
        valid = false;
        if (!firstInvalid) firstInvalid = input;
      }
    });
    if (firstInvalid) firstInvalid.focus();
    return valid;
  }

  requiredFields.forEach(([id]) => {
    const input = document.getElementById(id);
    input.addEventListener("input", () => {
      if (input.value.trim()) {
        input.removeAttribute("aria-invalid");
        document.getElementById(`${id}-error`).textContent = "";
      }
      document.getElementById("checkout-result").textContent = "";
    });
  });

  const paymentError = document.getElementById("payment-error");
  const visaPlaceholder = document.getElementById("visa-placeholder");
  if (new URLSearchParams(window.location.search).get("payment") === "cancelled") {
    const result = document.getElementById("checkout-result");
    result.textContent = "Payment was canceled. Your card was not charged; review your order and try again when ready.";
  }
  form.querySelectorAll('input[name="payment"]').forEach((radio) => {
    radio.addEventListener("change", () => {
      paymentError.textContent = "";
      visaPlaceholder.hidden = radio.value !== "visa" || !radio.checked;
      document.getElementById("checkout-result").textContent = "";
    });
  });

  let submitting = false;
  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (submitting) return;

    const result = document.getElementById("checkout-result");
    result.textContent = "";
    result.classList.remove("is-error");

    const addressValid = validateAddress();
    const cart = window.ElanCart.read();
    if (!cart.length) {
      result.textContent = "Your bag is empty. Add a look before continuing.";
      result.classList.add("is-error");
      const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
      document.getElementById("checkout-empty").scrollIntoView({ block: "center", behavior: reducedMotion ? "auto" : "smooth" });
      return;
    }

    const payment = form.querySelector('input[name="payment"]:checked');
    if (!payment) {
      paymentError.textContent = "Please choose a payment method.";
      return;
    }
    if (!addressValid) return;

    submitting = true;
    const submitBtn = document.getElementById("place-order");
    submitBtn.disabled = true;
    const originalText = submitBtn.textContent;
    submitBtn.textContent = "Processing order...";

    try {
      const formData = new FormData(form);

      const response = await fetch("../app/controllers/process-order.php", {
        method: "POST",
        body: formData,
        headers: {
          "Accept": "application/json"
        }
      });

      const data = await response.json();

      if (response.ok && data.success) {
        window.location.href = data.redirect_url;
      } else {
        result.classList.add("is-error");
        result.textContent = data.message || "An error occurred while processing your order.";
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
        submitting = false;
      }
    } catch (error) {
      console.error("Network Error:", error);
      result.classList.add("is-error");
      result.textContent = "A network error occurred. Please try again.";
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
      submitting = false;
    }
  });
  window.addEventListener("elan:cart-updated", renderSummary);
  window.addEventListener("storage", (event) => {
    if (event.key === window.ElanCart.key) renderSummary();
  });
  renderSummary();
})();