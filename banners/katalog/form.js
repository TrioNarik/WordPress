// =====================================================
// ================ FORMLARZ ===========================
// ===================================================== 
document.addEventListener('DOMContentLoaded', () => {
    
    // ====== Checkboxes ========
    document.querySelectorAll('.circle-checkbox input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const box = checkbox.closest('.box');
            const notification = document.getElementById('notification');
            if (checkbox.checked) {
                box.classList.add('checked');
                box.classList.add('collapsed');
                notification.style.display = 'block';
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 3000); // Powiadomienie zniknie po 3 sekundach
            } else {
                box.classList.remove('checked');
                box.classList.remove('collapsed');
            }
        });
    });
    // ============================

    const lang = document.getElementById('lang');
    // Wybrany język => przewalutowanie
    const langVal = lang.value;

    // Mapowanie na walutę
    const selectedCurrency = languageToCurrency[langVal] || 'PLN';  // Domyślnie PLN

    
    const totalPriceEl = document.getElementById('totalPrice');         // Wartość zamówienia
    const totalShippingEl = document.getElementById('totalShipping');   // Koszt dostawy
    const countPriceEl = document.getElementById('countPrice');         // Ogółem cena
    
    const productCheckboxes = document.querySelectorAll('#products input[type="checkbox"]');
    const productPrices = document.querySelectorAll('.product-price');

    const shippingCheckbox = document.getElementById('differentShippingAddress');
    const shippingAddressField = document.getElementById('shippingAddressField');

    const prepaymentCheckbox = document.getElementById('prepayment');
    const prepaymentFields = document.getElementById('prepaymentFields');
    
    const prepaymentRadios = document.querySelectorAll('input[name="prepaymentMode"]');  // disabled input jeśli nie są zaznaczone

    const prepaymentCount = document.querySelector('input[name="prepaymentCount"]');
    const orderForm = document.getElementById('orderForm');
    const confirmationMessage = document.getElementById('confirmationMessage');


    let products = {};

    // Inicjalizacja produktów z domyślnymi cenami
    productPrices.forEach((priceInput) => {
        products[priceInput.dataset.product] = parseFloat(priceInput.value);
    });

    const updatePrices = () => {
        // Obliczanie całkowitej ceny produktów
        const total = calculateTotalPrice(products, productCheckboxes);
        totalPriceEl.textContent = formatCurrency(total, selectedCurrency);
    
        // Obliczanie kosztu dostawy
        const shipping = calculateCostDelivery(products, productCheckboxes);
        totalShippingEl.textContent = formatCurrency(shipping, selectedCurrency);
    
        // Pobieranie kwoty przedpłaty z inputu (jeśli jest podana) i konwertowanie na float
        const prepayment = parseFloat(prepaymentCount.value.replace(',', '.')) || 0;
    
        // Obliczanie całkowitej kwoty (produkty + dostawa)
        const totalAmount = total + shipping;

        // Opcjonalnie, można dodać (np. rabaty, opłaty):
        const discount = 4000;  // Rabat
        const additionalFees = 0;  // Inne opłaty
    
        // Obliczanie pozostałej kwoty do zapłaty po odjęciu przedpłaty
        const remaining = totalAmount - prepayment - discount + additionalFees;
    
        // Wyświetlanie całkowitej kwoty do zapłaty (produkty + dostawa) oraz pozostałej kwoty
        countPriceEl.textContent = formatCurrency(remaining, selectedCurrency);
    };
    

    // Obsługa zmiany wartości produktu
    productPrices.forEach((priceInput) => {
        priceInput.addEventListener('input', () => {
            const productName = priceInput.dataset.product;
            products[productName] = parseFloat(priceInput.value) || 0;
            updatePrices();
        });
    });

    // Obsługa zaznaczenia/odznaczenia produktów
    productCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', updatePrices);
    });

    // Obsługa przedpłaty
    prepaymentCheckbox.addEventListener('change', () => {
        if (prepaymentCheckbox.checked) {
            prepaymentFields.classList.add('show');
        } else {
            prepaymentFields.classList.remove('show');
            prepaymentCount.value = '';
            const total = calculateTotalPrice(products, productCheckboxes);
            countPriceEl.textContent = formatCurrency(total, selectedCurrency);
        }
    });

    prepaymentCount.addEventListener('input', updatePrices);

    // =====================
    const togglePrepaymentCount = () => {
        const isChecked = Array.from(prepaymentRadios).some(radio => radio.checked);
        prepaymentCount.disabled = !isChecked; // Włącz/wyłącz input kwoty przedpłaty w zależności od zaznaczenia
    };

    // Nasłuch na zmianę stanu radio
    prepaymentRadios.forEach(radio => {
        radio.addEventListener('change', togglePrepaymentCount);
    });

    // Wywołanie funkcji na starcie, aby ustawić stan początkowy
    togglePrepaymentCount();
    // ===================

    // Obsługa zmiany adresu wysyłki
    shippingCheckbox.addEventListener('change', () => {
        shippingAddressField.classList.toggle('show', shippingCheckbox.checked);
    });

    // Obsługa wysłania formularza
    orderForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(orderForm);

        productCheckboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                const productName = checkbox.value;
                const productPrice = products[productName];
                formData.append('product[]', productName);
                formData.append(`price[${productName}]`, productPrice);
            }
        });

        fetch('include/order.php', {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.text())
            .then((data) => {
                confirmationMessage.style.display = 'block';
                orderForm.style.display = 'none';
            })
            .catch((error) => {
                console.error('Błąd:', error);
            });
    });
});




// =====================================================
// ================ FUNKCJE POMOCNICZE =================
// =====================================================

// Mapowanie języka na walutę
const languageToCurrency = {
    'pl': 'PLN',
    'en': 'EUR',  // EURO
    'gb': 'GBP',  // Funt GB
    'us': 'USD',
    'ru': 'RUB',
    'ch': 'CHF'   // Frank szwajcarski
};


// Funkcja do formatowania wartości w wybranej walucie
const formatCurrency = (value, currency) => {
    return new Intl.NumberFormat('pl-PL', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 2
    }).format(value);
};

// Funkcja aktualizacji pozostałej kwoty
const updateRemainingAmount = (total, prepayment) => {
    const remaining = total - (prepayment || 0);
    return remaining;
};

// Funkcja aktualizacji Ceny zamówienia
const calculateTotalPrice = (products, productCheckboxes) => {
    let total = 0;
    productCheckboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            const productName = checkbox.value;
            
            const productPrice = (checkbox.dataset.promo !== undefined && checkbox.dataset.promo !== null) 
            ? parseFloat(checkbox.dataset.promo) 
            : parseFloat(checkbox.dataset.price) || 0;
            // Cena z checkboxa => promo, regular
            
            total += productPrice;
        }
    });
    return total;
};


// Funkcja aktualizacji Kosztów dostawy
const calculateCostDelivery = (products, productCheckboxes) => {
    let delivery = 0;
    productCheckboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            const productName = checkbox.value;
            
            const deliveryCost = (checkbox.dataset.delivery !== undefined && checkbox.dataset.delivery !== null) 
            ? parseFloat(checkbox.dataset.delivery) 
            : parseFloat(checkbox.dataset.shipping) || 0;
            // Dostawa z checkboxa => promo, regular
            
            delivery += deliveryCost;
        }
    });
    return delivery;
};