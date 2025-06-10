document.addEventListener("DOMContentLoaded", function () {
    function initCardNumberFormatting() {
        const cardNumberField = document.getElementById("card_number");

        if (cardNumberField) {
            cardNumberField.addEventListener("input", function (e) {
                let value = e.target.value.replace(/\s+/g, "").replace(/[^0-9]/gi, "");
                let formattedValue = value.match(/.{1,4}/g)?.join(" ") || value;

                if (value.length > 16) {
                    value = value.substring(0, 16);
                    formattedValue = value.match(/.{1,4}/g)?.join(" ") || value;
                }

                e.target.value = formattedValue;
            });
        }
    }

    function initExpiryDateFormatting() {
        const cardExpiryField = document.getElementById("card_expiry");

        if (cardExpiryField) {
            cardExpiryField.addEventListener("input", function (e) {
                let value = e.target.value.replace(/\D/g, "");

                if (value.length >= 2) {
                    value = value.substring(0, 2) + "/" + value.substring(2, 4);
                }

                e.target.value = value;
            });
        }
    }

    function initCvvValidation() {
        const cardCvvField = document.getElementById("card_cvv");

        if (cardCvvField) {
            cardCvvField.addEventListener("input", function (e) {
                e.target.value = e.target.value.replace(/\D/g, "");
            });
        }
    }

    function initPaymentSubmission() {
        const paymentForm = document.getElementById("payment-form");

        if (paymentForm) {
            paymentForm.addEventListener("submit", function (e) {
                const submitBtn = document.querySelector(".btn-pay");

                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
                    submitBtn.disabled = true;
                }
            });
        }
    }

    function initCardTypeDetection() {
        const cardNumberField = document.getElementById("card_number");
        const cardIcons = document.querySelector(".card-icons");

        if (cardNumberField && cardIcons) {
            cardNumberField.addEventListener("input", function (e) {
                const value = e.target.value.replace(/\s/g, "");

                cardIcons.querySelectorAll("img").forEach((img) => {
                    img.style.opacity = "0.3";
                });

                if (value.startsWith("4")) {
                    const visaIcon = cardIcons.querySelector('img[alt*="Visa"]');
                    if (visaIcon) visaIcon.style.opacity = "1";
                } else if (value.startsWith("5") || value.startsWith("2")) {
                    const masterIcon = cardIcons.querySelector('img[alt*="Mastercard"]');
                    if (masterIcon) masterIcon.style.opacity = "1";
                } else if (value.startsWith("3")) {
                    const amexIcon = cardIcons.querySelector('img[alt*="American"]');
                    if (amexIcon) amexIcon.style.opacity = "1";
                }
            });
        }
    }

    function init() {
        if (window.location.href.includes("payment") || document.getElementById("payment-form")) {
            initCardNumberFormatting();
            initExpiryDateFormatting();
            initCvvValidation();
            initPaymentSubmission();
            initCardTypeDetection();

            console.log("Card Payment JS chargé avec succès");
        }
    }

    init();
});
