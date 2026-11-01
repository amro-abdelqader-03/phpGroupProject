(() => {
  "use strict";

  const form = document.getElementById("ai-stylist-form");
  const status = document.getElementById("ai-stylist-status");
  const results = document.getElementById("ai-stylist-results");
  if (!form || !status || !results) return;

  const projectRoot = new URL("../", window.location.href);
  const money = (amount) => new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(amount);

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    status.textContent = "Finding catalogue matches...";
    results.replaceChildren();
    const submit = form.querySelector('button[type="submit"]');
    submit.disabled = true;

    try {
      const payload = Object.fromEntries(new FormData(form));
      const response = await fetch("../app/controllers/ai-recommendations.php", {
        method: "POST",
        headers: { "Content-Type": "application/json", Accept: "application/json" },
        body: JSON.stringify(payload),
      });
      const data = await response.json();
      if (!response.ok || !data.success) throw new Error(data.message || "Recommendations are unavailable right now.");

      status.textContent = data.message;
      if (data.fit_note) {
        const note = document.createElement("p");
        note.className = "ai-stylist__fit-note";
        note.textContent = data.fit_note;
        results.append(note);
      }
      data.recommendations.forEach((item) => {
        const article = document.createElement("article");
        article.className = "ai-recommendation";
        const image = document.createElement("img");
        image.src = new URL(String(item.image_url || "" ).replace(/^\/+/, ""), projectRoot).href;
        image.alt = item.product_name;
        image.loading = "lazy";
        const content = document.createElement("div");
        const title = document.createElement("h3");
        title.textContent = item.product_name;
        const price = document.createElement("p");
        price.className = "ai-recommendation__price";
        price.textContent = money(Number(item.price));
        const reason = document.createElement("p");
        reason.textContent = item.reason;
        const link = document.createElement("a");
        link.href = `product.php?id=${encodeURIComponent(item.product_id)}`;
        link.className = "text-link";
        link.textContent = "View product";
        content.append(title, price, reason, link);
        article.append(image, content);
        results.append(article);
      });
    } catch (error) {
      status.textContent = error.message || "Recommendations are unavailable right now.";
    } finally {
      submit.disabled = false;
    }
  });
})();