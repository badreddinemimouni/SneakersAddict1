document.addEventListener("DOMContentLoaded", function () {
    function initStockCheck() {
        const pointureSelect = document.getElementById("pointure");
        const selectedSizeInput = document.getElementById("selected_size");
        const addCartForm = document.getElementById("add-to-cart-form");
        const addCartBtn = document.getElementById("add-to-cart-btn");
        const verifBtn = document.getElementById("verif_pointure");
        const resultat = document.getElementById("resultat_pointure");

        if (!pointureSelect || !verifBtn || !resultat) return;

        pointureSelect.addEventListener("change", function () {
            if (selectedSizeInput) {
                selectedSizeInput.value = this.value;
            }
            resultat.innerHTML = "";
        });

        verifBtn.addEventListener("click", function () {
            const pointure = pointureSelect.value;

            const productIdElement = document.querySelector("[data-product-id]");
            const productId = productIdElement
                ? productIdElement.dataset.productId
                : getProductIdFromScript();

            if (!pointure) {
                resultat.innerHTML = " ❌ Veuillez choisir une taille";
                return;
            }

            if (!productId) {
                resultat.innerHTML = " ❌ Erreur: ID produit non trouvé";
                return;
            }

            resultat.innerHTML = " ⏳ Vérification...";

            const url = `?route=check_stock&product_id=${productId}&size=${pointure}`;
            console.log("URL appelée:", url);

            fetch(url)
                .then((response) => {
                    console.log("Response status:", response.status);
                    console.log("Response OK:", response.ok);

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }

                    return response.json();
                })
                .then((data) => {
                    console.log("Données reçues:", data);

                    if (data.error) {
                        resultat.innerHTML = ` ❌ ${data.error}`;
                        resultat.className = "stock-error";
                    } else if (data.available) {
                        resultat.innerHTML = " ✅ Disponible";
                        resultat.className = "stock-available";

                        if (addCartBtn) {
                            addCartBtn.disabled = false;
                            addCartBtn.classList.remove("disabled");
                        }
                    } else {
                        resultat.innerHTML = " ❌ Indisponible";
                        resultat.className = "stock-unavailable";

                        if (addCartBtn) {
                            addCartBtn.disabled = true;
                            addCartBtn.classList.add("disabled");
                        }
                    }
                })
                .catch((error) => {
                    console.error("Erreur fetch:", error);
                    resultat.innerHTML = " ❌ Erreur de vérification: " + error.message;
                    resultat.className = "stock-error";
                });
        });
    }

    function getProductIdFromScript() {
        const scripts = document.querySelectorAll("script");
        for (let script of scripts) {
            const content = script.textContent;
            const match = content.match(/productId\s*=\s*(\d+)/);
            if (match) {
                return match[1];
            }
        }
        return null;
    }

    function initCartValidation() {
        const addCartForm = document.getElementById("add-to-cart-form");

        if (addCartForm) {
            addCartForm.addEventListener("submit", function (e) {
                const pointureSelect = document.getElementById("pointure");
                const selectedSizeInput = document.getElementById("selected_size");

                if (!pointureSelect || !pointureSelect.value) {
                    e.preventDefault();

                    if (window.toast) {
                        window.toast.show("Veuillez sélectionner une pointure", "warning");
                    } else {
                        alert("Veuillez sélectionner une pointure avant d'ajouter au panier.");
                    }

                    if (pointureSelect) {
                        pointureSelect.style.borderColor = "#dc3545";
                        pointureSelect.focus();

                        setTimeout(() => {
                            pointureSelect.style.borderColor = "";
                        }, 3000);
                    }

                    return false;
                } else {
                    if (selectedSizeInput) {
                        selectedSizeInput.value = pointureSelect.value;
                    }
                }
            });
        }
    }

    function initAddToCartAnimation() {
        const addCartForm = document.getElementById("add-to-cart-form");
        const addCartBtn = document.getElementById("add-to-cart-btn");

        if (addCartForm && addCartBtn) {
            addCartForm.addEventListener("submit", function (e) {
                const originalText = addCartBtn.innerHTML;
                addCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ajout...';
                addCartBtn.disabled = true;
            });
        }
    }

    function initVisualFeedback() {
        const style = document.createElement("style");
        style.textContent = `
            .stock-available { color: #28a745; font-weight: bold; }
            .stock-unavailable { color: #dc3545; font-weight: bold; }
            .stock-error { color: #ffc107; font-weight: bold; }
            .disabled { opacity: 0.6; cursor: not-allowed; }
        `;
        document.head.appendChild(style);
    }

    function init() {
        if (
            document.getElementById("verif_pointure") ||
            document.getElementById("add-to-cart-form")
        ) {
            initStockCheck();
            initCartValidation();
            initAddToCartAnimation();
            initVisualFeedback();

            console.log("Product Stock JS chargé avec succès");
        }
    }

    init();
});
