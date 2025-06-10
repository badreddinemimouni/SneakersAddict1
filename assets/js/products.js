document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search-input");
    const brandFilter = document.getElementById("brand-filter");
    const priceFilter = document.getElementById("price-filter");
    const sortFilter = document.getElementById("sort-filter");
    const productsGrid = document.getElementById("products-grid");
    const productCards = document.querySelectorAll(".product-card");

    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedBrand = brandFilter.value;
        const selectedPriceRange = priceFilter.value;
        const sortOption = sortFilter.value;

        let visibleProducts = [];

        productCards.forEach((card) => {
            const productName = card.dataset.name.toLowerCase();
            const productBrand = card.dataset.brand;
            const productPrice = parseFloat(card.dataset.price);

            let isVisible = true;

            if (searchTerm && !productName.includes(searchTerm)) {
                isVisible = false;
            }

            if (selectedBrand && productBrand !== selectedBrand) {
                isVisible = false;
            }

            if (selectedPriceRange) {
                const [min, max] = selectedPriceRange.split("-").map((p) => p.replace("+", ""));
                const minPrice = parseFloat(min);
                const maxPrice = max ? parseFloat(max) : Infinity;

                if (productPrice < minPrice || productPrice > maxPrice) {
                    isVisible = false;
                }
            }

            if (isVisible) {
                card.style.display = "block";
                visibleProducts.push(card);
            } else {
                card.style.display = "none";
            }
        });

        sortProducts(visibleProducts, sortOption);

        const noProductsMessage = document.querySelector(".no-products");
        if (visibleProducts.length === 0) {
            if (!noProductsMessage) {
                const message = document.createElement("div");
                message.className = "no-products";
                message.innerHTML = `
                    <i class="fas fa-search"></i>
                    <h3>Aucun produit trouvé</h3>
                    <p>Essayez de modifier vos critères de recherche.</p>
                `;
                productsGrid.appendChild(message);
            }
        } else {
            if (noProductsMessage) {
                noProductsMessage.remove();
            }
        }
    }

    function sortProducts(products, sortOption) {
        const [criteria, order] = sortOption.split("-");

        products.sort((a, b) => {
            let valueA, valueB;

            switch (criteria) {
                case "name":
                    valueA = a.dataset.name.toLowerCase();
                    valueB = b.dataset.name.toLowerCase();
                    break;
                case "price":
                    valueA = parseFloat(a.dataset.price);
                    valueB = parseFloat(b.dataset.price);
                    break;
                default:
                    return 0;
            }

            if (order === "asc") {
                return valueA > valueB ? 1 : -1;
            } else {
                return valueA < valueB ? 1 : -1;
            }
        });

        products.forEach((product) => {
            productsGrid.appendChild(product);
        });
    }

    searchInput.addEventListener("input", filterProducts);
    brandFilter.addEventListener("change", filterProducts);
    priceFilter.addEventListener("change", filterProducts);
    sortFilter.addEventListener("change", filterProducts);

    document.querySelectorAll(".add-to-cart-form").forEach((form) => {
        form.addEventListener("submit", function (e) {
            const button = this.querySelector(".btn-add-cart");
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ajout...';
            button.disabled = true;

            setTimeout(() => {
                button.innerHTML = '<i class="fas fa-check"></i> Ajouté !';

                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 1500);
            }, 500);
        });
    });

    function animateOnScroll() {
        const cards = document.querySelectorAll(".product-card");

        cards.forEach((card) => {
            const cardTop = card.getBoundingClientRect().top;
            const cardVisible = 150;

            if (cardTop < window.innerHeight - cardVisible) {
                card.classList.add("animate");
            }
        });
    }

    window.addEventListener("scroll", animateOnScroll);
    animateOnScroll();

    function resetFilters() {
        searchInput.value = "";
        brandFilter.value = "";
        priceFilter.value = "";
        sortFilter.value = "name-asc";
        filterProducts();
    }

    const resetButton = document.createElement("button");
    resetButton.textContent = "Réinitialiser les filtres";
    resetButton.className = "btn-reset-filters";
    resetButton.addEventListener("click", resetFilters);

    const filtersSection = document.querySelector(".filters");
    if (filtersSection) {
        filtersSection.appendChild(resetButton);
    }
});
