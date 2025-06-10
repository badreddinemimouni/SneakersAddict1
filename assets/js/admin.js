document.addEventListener("DOMContentLoaded", function () {
    function setupModal(modalId, buttonId, closeSelector) {
        const modal = document.getElementById(modalId);
        const btn = document.getElementById(buttonId);
        const closeBtn = document.querySelector(closeSelector);

        if (btn && modal) {
            btn.addEventListener("click", () => (modal.style.display = "block"));

            if (closeBtn) {
                closeBtn.addEventListener("click", () => (modal.style.display = "none"));

                window.addEventListener("click", (event) => {
                    if (event.target == modal) modal.style.display = "none";
                });
            }
        }
    }

    setupModal("add-user-modal", "btn-add-user", "#add-user-modal .close");
    setupModal("add-product-modal", "btn-add-product", "#add-product-modal .close");

    document.querySelectorAll(".toggle-password").forEach((toggle) => {
        toggle.addEventListener("click", function () {
            const input = this.previousElementSibling;
            const isPassword = input.type === "password";

            input.type = isPassword ? "text" : "password";
            this.classList.toggle("fa-eye", !isPassword);
            this.classList.toggle("fa-eye-slash", isPassword);
        });
    });

    const tabs = document.querySelectorAll(".tab");
    const tabContents = document.querySelectorAll(".tab-content");

    if (tabs.length > 0 && tabContents.length > 0) {
        tabs.forEach((tab) => {
            tab.addEventListener("click", function () {
                tabs.forEach((t) => t.classList.remove("active"));
                this.classList.add("active");

                tabContents.forEach((content) => content.classList.remove("active"));

                const tabId = this.getAttribute("data-tab");
                document.getElementById(tabId + "-view").classList.add("active");
            });
        });
    }

    document.querySelectorAll('[id^="pointure-"]').forEach((selector) => {
        const produitId = selector.id.replace("pointure-", "");
        const inputQuantite = document.getElementById(`quantite-${produitId}`);

        if (selector && inputQuantite) {
            selector.addEventListener("change", function () {
                const pointureId = this.value;

                if (pointureId) {
                    fetch(`get_stock.php?produit_id=${produitId}&pointure_id=${pointureId}`)
                        .then((response) => response.json())
                        .then((data) => {
                            inputQuantite.value = data.success ? data.amount : 0;
                        })
                        .catch((error) => {
                            console.error("Erreur:", error);
                            inputQuantite.value = 0;
                        });
                }
            });
        }
    });
});
