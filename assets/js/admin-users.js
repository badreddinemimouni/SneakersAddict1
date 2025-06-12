document.addEventListener("DOMContentLoaded", function () {
    function initUserFormValidation() {
        const userForm = document.querySelector("#add-user-modal form");

        if (userForm) {
            userForm.addEventListener("submit", function (e) {
                let isValid = true;
                const errors = [];

                const requiredFields = userForm.querySelectorAll("[required]");
                requiredFields.forEach((field) => {
                    if (!field.value.trim()) {
                        isValid = false;
                        errors.push(`Le champ "${field.name}" est obligatoire`);
                        field.classList.add("error");
                    } else {
                        field.classList.remove("error");
                    }
                });

                const emailField = userForm.querySelector('[type="email"]');
                if (emailField && emailField.value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(emailField.value)) {
                        isValid = false;
                        errors.push("Adresse email invalide");
                        emailField.classList.add("error");
                    }
                }

                const passwordField = userForm.querySelector('[name="mdp"]');
                const confirmPasswordField = userForm.querySelector('[name="confirm_mdp"]');

                if (passwordField && passwordField.value) {
                    if (passwordField.value.length < 6) {
                        isValid = false;
                        errors.push("Le mot de passe doit contenir au moins 6 caractères");
                        passwordField.classList.add("error");
                    }

                    if (
                        confirmPasswordField &&
                        passwordField.value !== confirmPasswordField.value
                    ) {
                        isValid = false;
                        errors.push("Les mots de passe ne correspondent pas");
                        confirmPasswordField.classList.add("error");
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    if (window.toast) {
                        window.toast.show("Veuillez corriger les erreurs du formulaire", "error");
                    } else {
                        alert("Erreurs dans le formulaire:\n" + errors.join("\n"));
                    }
                }
            });
        }
    }

    function initAdminConfirmations() {
        // pour parcourir les formulaires qui ont  data-confirm
        document.querySelectorAll("form[data-confirm]").forEach((form) => {
            const newForm = form.cloneNode(true);
            form.parentNode.replaceChild(newForm, form);

            newForm.addEventListener("submit", function (e) {
                e.preventDefault();
                e.stopPropagation();

                const message = this.getAttribute("data-confirm");
                if (message && confirm(message)) {
                    this.removeAttribute("data-confirm");
                    this.submit();
                }
            });
        });
    }
    // pour animation de chargement
    function initButtonAnimations() {
        document.querySelectorAll("form").forEach((form) => {
            form.addEventListener("submit", function (e) {
                const submitBtn = this.querySelector("button[type='submit']");
                if (
                    submitBtn &&
                    !submitBtn.closest(".modal-footer") &&
                    !this.hasAttribute("data-confirm")
                ) {
                    const originalContent = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    submitBtn.disabled = true;
                }
            });
        });
    }

    function initTooltips() {
        const tooltipElements = document.querySelectorAll("[title]");

        tooltipElements.forEach((element) => {
            element.addEventListener("mouseenter", function () {
                const tooltip = document.createElement("div");
                tooltip.className = "custom-tooltip";
                tooltip.textContent = this.title;
                tooltip.style.cssText = `
                    position: absolute;
                    background: #333;
                    color: white;
                    padding: 8px 12px;
                    border-radius: 4px;
                    font-size: 14px;
                    z-index: 1000;
                    pointer-events: none;
                    white-space: nowrap;
                `;
                document.body.appendChild(tooltip);
                this.removeAttribute("title");
                this._tooltip = tooltip;
                this._originalTitle = tooltip.textContent;
            });

            element.addEventListener("mouseleave", function () {
                if (this._tooltip) {
                    this._tooltip.remove();
                    this.title = this._originalTitle;
                    delete this._tooltip;
                    delete this._originalTitle;
                }
            });
        });
    }

    function init() {
        if (
            window.location.href.includes("admin_users") ||
            document.getElementById("add-user-modal")
        ) {
            initTooltips();
            initAdminConfirmations();
            initUserFormValidation();
            initButtonAnimations();

            console.log("Admin Users JS chargé avec succès");
        }
    }

    init();
});
