document.addEventListener("DOMContentLoaded", function () {
    function initCacheManagement() {
        if (
            window.location.href.includes("admin_") ||
            window.location.href.includes("checkout") ||
            window.location.href.includes("payment")
        ) {
            window.addEventListener("pageshow", function (event) {
                if (event.persisted) {
                    window.location.reload();
                }
            });

            if (typeof Storage !== "undefined") {
                localStorage.setItem("sensitive_page", Date.now());
            }
        }

        window.addEventListener("load", function () {
            if (typeof Storage !== "undefined") {
                const sensitivePage = localStorage.getItem("sensitive_page");
                if (sensitivePage && Date.now() - parseInt(sensitivePage) < 10000) {
                    if (
                        !window.location.href.includes("admin_") &&
                        !window.location.href.includes("checkout") &&
                        !window.location.href.includes("payment")
                    ) {
                        localStorage.removeItem("sensitive_page");
                    }
                }
            }
        });
    }

    initCacheManagement();

    function getCsrfToken() {
        const csrfInput = document.querySelector('input[name="csrf_token"]');
        return csrfInput ? csrfInput.value : null;
    }

    function makeAjaxRequest(url, options = {}) {
        const defaultOptions = {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        };

        return fetch(url, { ...defaultOptions, ...options });
    }

    window.SneakersUtils = {
        getCsrfToken,
        makeAjaxRequest,
    };
});
