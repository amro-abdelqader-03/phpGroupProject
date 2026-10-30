/* ÉLAN BRIDAL account form demonstration. No credentials are stored or sent. */
(() => {
  "use strict";

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

  document.querySelectorAll("[data-toggle-password]").forEach((button) => {
    button.addEventListener("click", () => {
      const input = document.getElementById(button.dataset.togglePassword);
      if (!input) return;
      const visible = input.type === "password";
      input.type = visible ? "text" : "password";
      button.textContent = visible ? "Hide" : "Show";
      button.setAttribute("aria-label", `${visible ? "Hide" : "Show"} ${input.labels[0].textContent.replace("*", "").trim().toLowerCase()}`);
    });
  });

  function setError(input, message) {
    input.setAttribute("aria-invalid", "true");
    const error = document.getElementById(`${input.id}-error`);
    if (error) error.textContent = message;
  }

  function clearError(input) {
    input.removeAttribute("aria-invalid");
    const error = document.getElementById(`${input.id}-error`);
    if (error) error.textContent = "";
  }

  function validateEmail(input) {
    const email = input.value.trim();
    if (!email) {
      setError(input, "Please enter your email address.");
      return false;
    }
    if (!emailPattern.test(email)) {
      setError(input, "Please enter a valid email address.");
      return false;
    }
    clearError(input);
    return true;
  }

  function processDemo(form, button, status, message) {
    button.disabled = true;
    button.dataset.originalLabel = button.textContent;
    button.textContent = "Please wait…";
    status.className = "auth-status";
    status.textContent = "";
    window.setTimeout(() => {
      button.disabled = false;
      button.textContent = button.dataset.originalLabel;
      status.textContent = message;
      form.reset();
    }, 450);
  }

  const register = document.getElementById("register-form");
  if (register) {
    const name = document.getElementById("full-name");
    const email = document.getElementById("register-email");
    const password = document.getElementById("register-password");
    const confirm = document.getElementById("confirm-password");
    const button = register.querySelector('[type="submit"]');
    const status = document.getElementById("register-status");

    [name, email, password, confirm].forEach((input) => {
      input.addEventListener("input", () => {
        clearError(input);
        status.textContent = "";
        if (input === password || input === confirm) clearError(confirm);
      });
    });

    register.addEventListener("submit", (event) => {
      event.preventDefault();
      if (button.disabled) return;
      let valid = true;
      if (!name.value.trim()) { setError(name, "Please enter your full name."); valid = false; }
      else clearError(name);
      if (!validateEmail(email)) valid = false;
      if (!password.value) { setError(password, "Please create a password."); valid = false; }
      else clearError(password);
      if (!confirm.value) { setError(confirm, "Please confirm your password."); valid = false; }
      else if (password.value !== confirm.value) { setError(confirm, "Your passwords do not match."); valid = false; }
      else clearError(confirm);
      if (!valid) {
        const firstInvalid = register.querySelector('[aria-invalid="true"]');
        if (firstInvalid) firstInvalid.focus();
        return;
      }
      processDemo(register, button, status, "Your details passed validation, but no account was created. Registration needs a connected authentication service.");
    });
  }

  const login = document.getElementById("login-form");
  if (login) {
    const email = document.getElementById("login-email");
    const password = document.getElementById("login-password");
    const button = login.querySelector('[type="submit"]');
    const status = document.getElementById("login-status");
    [email, password].forEach((input) => input.addEventListener("input", () => {
      clearError(input);
      status.textContent = "";
    }));
    login.addEventListener("submit", (event) => {
      event.preventDefault();
      if (button.disabled) return;
      const emailValid = validateEmail(email);
      if (!password.value) setError(password, "Please enter your password.");
      else clearError(password);
      if (!emailValid || !password.value) {
        const firstInvalid = login.querySelector('[aria-invalid="true"]');
        if (firstInvalid) firstInvalid.focus();
        return;
      }
      processDemo(login, button, status, "We could not sign you in because authentication is not connected in this demonstration.");
    });
    document.getElementById("forgot-password").addEventListener("click", () => {
      status.textContent = "Password reset is not available until an authentication service is connected.";
    });
  }
})();
