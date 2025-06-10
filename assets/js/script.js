document.addEventListener("DOMContentLoaded", function () {
  const loginTabBtn = document.getElementById("login-tab-btn");
  const registerTabBtn = document.getElementById("register-tab-btn");
  const loginForm = document.getElementById("login-form-container");
  const registerForm = document.getElementById("register-form-container");

  if (loginTabBtn && registerTabBtn && loginForm && registerForm) {
    loginTabBtn.addEventListener("click", function () {
      loginTabBtn.classList.add("active");
      registerTabBtn.classList.remove("active");

      loginForm.classList.add("active");
      registerForm.classList.remove("active");
    });

    registerTabBtn.addEventListener("click", function () {
      registerTabBtn.classList.add("active");
      loginTabBtn.classList.remove("active");

      registerForm.classList.add("active");
      loginForm.classList.remove("active");
    });
  }

   const miniCartIcon = document.querySelector("#panier-icon");
  const miniPanier = document.getElementById("mini-panier");

  if (miniCartIcon && miniPanier) {
    console.log("Mini panier initialisé");

    miniCartIcon.addEventListener("click", (e) => {
      e.preventDefault();
      console.log("Clic sur panier");
      miniPanier.style.display =
        miniPanier.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", (e) => {
      if (!miniCartIcon.contains(e.target) && !miniPanier.contains(e.target)) {
        miniPanier.style.display = "none";
      }
    });

    if (window.location.search.includes("ajout=1")) {
      afficherMiniPanier();
    }
  }

  function afficherMiniPanier() {
    if (miniPanier) {
      miniPanier.style.display = "block";
      setTimeout(() => {
        miniPanier.style.display = "none";
      }, 5000);
    }
  }

    const contactForm = document.querySelector(".contact-form form");

  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      const fields = [
        {
          input: document.querySelector("#name"),
          rule: (val) => val !== "",
          message: "Veuillez entrer votre nom",
        },
        {
          input: document.querySelector("#email"),
          rule: (val) => val !== "" && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val),
          message: (val) =>
            val === ""
              ? "Veuillez entrer votre email"
              : "Veuillez entrer un email valide",
        },
        {
          input: document.querySelector("#subject"),
          rule: (val) => val !== "",
          message: "Veuillez entrer un sujet",
        },
        {
          input: document.querySelector("#message"),
          rule: (val) => val !== "",
          message: "Veuillez entrer votre message",
        },
      ];

      let isValid = true;

      fields.forEach((field) => {
        if (!field.input) return;

        const value = field.input.value.trim();
        const isFieldValid = field.rule(value);

        if (!isFieldValid) {
          const msg =
            typeof field.message === "function"
              ? field.message(value)
              : field.message;
          highlightField(field.input, msg);
          isValid = false;
        } else {
          resetField(field.input);
        }
      });

      if (!isValid) {
        e.preventDefault();
      }
    });
  }

  function highlightField(field, message) {
    field.classList.add("field-error");

    let errorMessage = field.nextElementSibling;
    if (!errorMessage || !errorMessage.classList.contains("error-message")) {
      errorMessage = document.createElement("div");
      errorMessage.classList.add("error-message");
      field.parentNode.appendChild(errorMessage);
    }
    errorMessage.textContent = message;
  }

  function resetField(field) {
    field.classList.remove("field-error");

    const errorMessage = field.nextElementSibling;
    if (errorMessage && errorMessage.classList.contains("error-message")) {
      errorMessage.remove();
    }
  }

   function setupPasswordToggle(toggleId, passwordId) {
    const toggleBtn = document.getElementById(toggleId);
    const passwordField = document.getElementById(passwordId);

    if (toggleBtn && passwordField) {
      toggleBtn.addEventListener("click", function () {
        const type =
          passwordField.getAttribute("type") === "password"
            ? "text"
            : "password";
        passwordField.setAttribute("type", type);

        this.textContent = type === "password" ? "👁️" : "🙈";
      });
    }
  }

  setupPasswordToggle("toggle-password", "password");
  setupPasswordToggle("toggle-register-password", "register-password");
  setupPasswordToggle("toggle-confirm-password", "register-confirm");
});
