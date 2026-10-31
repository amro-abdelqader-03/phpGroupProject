/* ÉLAN BRIDAL account form demonstration. */
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

  const register = document.getElementById("register-form");
  if (register) {
    const name = document.getElementById("full-name");
    const email = document.getElementById("register-email");
    const password = document.getElementById("register-password");
    const confirm = document.getElementById("confirm-password");
    const phoneInput = document.getElementById("register-phone");
    const button = register.querySelector('[type="submit"]');
    const status = document.getElementById("register-status");

    [name, email, password, confirm].forEach((input) => {
      if (input) {
        input.addEventListener("input", () => {
          clearError(input);
          status.textContent = "";
          if (input === password || input === confirm) clearError(confirm);
        });
      }
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

      button.disabled = true;
      button.dataset.originalLabel = button.textContent;
      button.textContent = "Please wait…";
      status.className = "auth-status";
      status.textContent = "";

      const phoneValue = phoneInput ? phoneInput.value.trim() : "";

      fetch('/phpGroupProject/app/controllers/auth.php', {
        method: 'POST',
        cache: 'no-store',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'register',
          full_name: name.value.trim(),
          email: email.value.trim(),
          password: password.value,
          phone: phoneValue
        })
      })
      .then(response => response.json().then(data => ({ status: response.status, body: data })))
      .then(result => {
        button.disabled = false;
        button.textContent = button.dataset.originalLabel;

        if (result.body.success) {
          status.style.color = "green";
          status.textContent = result.body.message;
          register.reset();
          window.setTimeout(() => {
            window.location.href = "login.php";
          }, 1000);
        } else {
          status.style.color = "red";
          status.textContent = result.body.message || "Registration failed.";
        }
      })
      .catch(error => {
        button.disabled = false;
        button.textContent = button.dataset.originalLabel;
        status.style.color = "red";
        status.textContent = "A network error occurred.";
        console.error('Error:', error);
      });
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

      button.disabled = true;
      button.dataset.originalLabel = button.textContent;
      button.textContent = "Please wait…";
      status.className = "auth-status";
      status.textContent = "";

      fetch('/phpGroupProject/app/controllers/auth.php', {
        method: 'POST',
        cache: 'no-store',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'login',
          email: email.value.trim(),
          password: password.value
        })
      })
      .then(response => response.json().then(data => ({ status: response.status, body: data })))
      .then(result => {
        button.disabled = false;
        button.textContent = button.dataset.originalLabel;

        if (result.body.success) {
          status.style.color = "green";
          status.textContent = result.body.message;
          login.reset();
          window.setTimeout(() => {
            window.location.href = "index.php";
          }, 1000);
        } else {
          status.style.color = "red";
          status.textContent = result.body.message || "Login failed.";
        }
      })
      .catch(error => {
        button.disabled = false;
        button.textContent = button.dataset.originalLabel;
        status.style.color = "red";
        status.textContent = "A network error occurred.";
        console.error('Error:', error);
      });
    });

    document.getElementById("forgot-password").addEventListener("click", () => {
      status.textContent = "Password reset is not available until an authentication service is connected.";
    });
  }
})();