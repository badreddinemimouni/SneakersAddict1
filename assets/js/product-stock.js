document.addEventListener("DOMContentLoaded", function () {
    function initStockCheck() {
        const pointureSelect = document.getElementById("pointure");
        const selectedSizeInput = document.getElementById("selected_size");
        // const addCartForm = document.getElementById("add-to-cart-form");
        const addCartBtn = document.getElementById("add-to-cart-btn");
        const verifBtn = document.getElementById("verif_pointure");
        const resultat = document.getElementById("resultat_pointure");

        if (!pointureSelect || !verifBtn || !resultat) return;

        pointureSelect.addEventListener("change", function () {
            if (selectedSizeInput) {
                selectedSizeInput.value = this.value;
            }
            // resultat.innerHTML = "";
        });
        try {
            verifBtn.addEventListener("click", async function () {
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
                // console.log("URL appelée:", url);

                const response = await fetch(url);
                // console.log("Response status:", response.status);
                // console.log("Response OK:", response.ok);

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                const data = await response.json();
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
            });
        } catch (error) {
            console.log("Erreur fetch:", error);
            // console.error("Erreur fetch:", error);
            resultat.innerHTML = " ❌ Erreur de vérification: " + error.message;
            resultat.className = "stock-error";
        }
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
                addCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ajout...';
                addCartBtn.disabled = true;
            });
        }
    }

    function init() {
        if (
            document.getElementById("verif_pointure") ||
            document.getElementById("add-to-cart-form")
        ) {
            initStockCheck();
            initCartValidation();
            initAddToCartAnimation();
        }
    }

    init();
});
