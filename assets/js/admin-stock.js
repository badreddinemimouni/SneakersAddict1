document.addEventListener("DOMContentLoaded", function () {
    function initTabsManagement() {
        document.querySelectorAll(".tab").forEach((btn) => {
            btn.addEventListener("click", function () {
                const view = this.dataset.view;

                document.querySelectorAll(".tab").forEach((b) => b.classList.remove("active"));
                this.classList.add("active");

                document
                    .querySelectorAll(".tab-content")
                    .forEach((v) => v.classList.remove("active"));
                const targetView = document.getElementById(view + "-view");
                if (targetView) {
                    targetView.classList.add("active");
                }
            });
        });
    }

    function initProductModal() {
        const modal = document.getElementById("add-product-modal");
        const openBtn = document.getElementById("btn-add-product");
        const closeBtn = modal ? modal.querySelector(".close") : null;
        const cancelBtn = modal ? modal.querySelector(".modal-cancel") : null;

        if (modal && openBtn) {
            openBtn.addEventListener("click", () => {
                modal.style.display = "flex";
            });

            if (closeBtn) {
                closeBtn.addEventListener("click", () => {
                    modal.style.display = "none";
                });
            }

            if (cancelBtn) {
                cancelBtn.addEventListener("click", () => {
                    modal.style.display = "none";
                });
            }

            window.addEventListener("click", (e) => {
                if (e.target === modal) {
                    modal.style.display = "none";
                }
            });

            document.addEventListener("keydown", (e) => {
                if (e.key === "Escape" && modal.style.display === "flex") {
                    modal.style.display = "none";
                }
            });
        }
    }

    function initProductFormValidation() {
        const productForm = document.querySelector("#add-product-modal form");

        if (productForm) {
            productForm.addEventListener("submit", function (e) {
                let isValid = true;
                const errors = [];

                const requiredFields = productForm.querySelectorAll("[required]");
                requiredFields.forEach((field) => {
                    if (!field.value.trim()) {
                        isValid = false;
                        errors.push(
                            `Le champ "${
                                field.previousElementSibling?.textContent || field.name
                            }" est obligatoire`
                        );
                        field.classList.add("error");
                    } else {
                        field.classList.remove("error");
                    }
                });

                const priceField = productForm.querySelector('[name="prix"]');
                if (priceField && priceField.value) {
                    const price = parseFloat(priceField.value);
                    if (isNaN(price) || price <= 0) {
                        isValid = false;
                        errors.push("Le prix doit être un nombre positif");
                        priceField.classList.add("error");
                    }
                }

                const stockField = productForm.querySelector('[name="stock"]');
                if (stockField && stockField.value) {
                    const stock = parseInt(stockField.value);
                    if (isNaN(stock) || stock < 0) {
                        isValid = false;
                        errors.push("Le stock doit être un nombre entier positif ou nul");
                        stockField.classList.add("error");
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

    function initActionButtons() {
        document
            .querySelectorAll(".stock-form, .delete-form, form[data-confirm]")
            .forEach((form) => {
                form.addEventListener("submit", function (e) {
                    if (this.hasAttribute("data-confirm")) {
                        if (!confirm(this.getAttribute("data-confirm"))) {
                            e.preventDefault();
                            return false;
                        }
                    }

                    const submitBtn = this.querySelector("button[type='submit']");
                    if (submitBtn) {
                        const originalContent = submitBtn.innerHTML;
                        submitBtn.innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i> Traitement...';
                        submitBtn.disabled = true;
                    }
                });
            });
    }

    function init() {
        if (
            window.location.href.includes("admin_stock") ||
            document.getElementById("add-product-modal")
        ) {
            initTabsManagement();
            initProductModal();
            initProductFormValidation();
            initActionButtons();

            console.log("Admin Stock JS chargé avec succès");
        }
    }

    init();
});
