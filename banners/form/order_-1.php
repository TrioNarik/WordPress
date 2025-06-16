// Pobieranie zapisanych w SESSION danych
function prepareCartSessionData($currency) {
    // Initialize zmiennych
    $mainProduct = [];
    $extraProducts = [];
    $total = false; // ====== UWAGA: false, bo nie wiemy czy będą ceny >= 0 (-1 to ustalana indywidualnie)
    $delivery_total = false;

    // Sprawdź SESSION Cart => Products
    if (isset($_SESSION['cart']['products']) && !empty($_SESSION['cart']['products'])) {

        // Iteracj produktów w session
    
        foreach ($_SESSION['cart']['products'] as $key => $product) {

            // ====== 11.04.2025 =========================================
            // Jeśli to produkt ('paka'), sprawdź [addToCart] => true/false
            if ($key === 'paka' && (!isset($product['addToCart']) || $product['addToCart'] !== true)) {
                continue; // pomiń jeśli addToCart nie jest true
            }
            // ============================================================

            // 1. Dodaj Pakę do listy zamówień
            if ($key === 'paka') {
                // PAKA z personalizacją
                $mainProduct = [
                    'name' => $product['name'],
                    'price' => ($product['price'] < 0) ? false : $product['price'],
                    'promo' => $product['promo'] ?? false,
                    'currency' => $currency,
                    'color' => null,
                    'customization' => [],
                    'flag' => null,
                ];

                // Sprawdź czy ceny to nie -1
                if ($product['price'] >= 0) {
                    $total = ($total === false ? 0 : $total) + $product['price'];
                }

                // Kolor paki
                if (isset($product['color']) && !empty($product['color'])) {
                    $mainProduct['color'] = [
                        'name' => $product['color']['name'],
                        'hex' => $product['color']['hex'],
                        'ral' => $product['color']['ral'],
                        'price' => ($product['color']['price'] < 0) ? false : $product['color']['price'],
                        'promo' => $product['color']['promo'] ?? false,
                        'currency' => $currency,
                    ];

                    if (isset($product['color']['price']) && $product['color']['price'] >= 0) {
                        $total = ($total === false ? 0 : $total) + $product['color']['price'];
                    }
                }

                // Dodaj personalizację  => if exists
                if (isset($product['customization']) && !empty($product['customization'])) {
                    foreach ($product['customization'] as $customization) {
                        $mainProduct['customization'][] = [
                            'title' => $customization['title'] ?? '',
                            'text' => $customization['text'] ?? false,
                            'align' => $customization['align'] ?? false,
                            'font' => $customization['font'] ?? false,
                            'color' => $customization['color'] ?? false,
                            'size' => $customization['size'] ?? false,
                            'format' => $customization['format'] ?? false,
                            'price' => ($customization['price'] < 0) ? false : $customization['price'],
                            'promo' => $customization['promo'] ?? false,
                            'currency' => $currency,
                        ];

                        if (isset($customization['price']) && $customization['price'] >= 0) {
                            $total = ($total === false ? 0 : $total) + $customization['price'];
                        }
                    }
                }

                // Dodaj szczegóły flagi => if exists
                if (isset($product['flag']) && !empty($product['flag'])) {
                    $mainProduct['flag'] = [
                        'title'     => $product['flag']['title'],
                        'position'  => $product['flag']['position'],
                        'align'     => $product['flag']['align'],
                        'country'   => $product['flag']['country'],
                        'price'     => ($product['flag']['price'] < 0) ? false : $product['flag']['price'],
                        'currency'  => $currency,
                    ];

                    if (isset($product['flag']['price']) && $product['flag']['price'] >= 0) {
                            $total = ($total === false ? 0 : $total) + $product['flag']['price'];
                    }

                }

                // Dodaj koszt dostawy do kwoty => if exists
                if (isset($product['shipping']['price'])) {
                    $delivery_total += $product['shipping']['price'];
                }

            } else {
                
                // 2. Pozostałe produkty z Quantity
                $quantity = $product['quantity'] ?? 1;
                $extraProducts[] = [
                    'name' => $product['name'],
                    'price' => ($product['price'] < 0) ? false : $product['price'],
                    'promo' => $product['promo'] ?? false,
                    'currency' => $currency,
                ];
                if ($product['price'] >= 0) {
                    $total = ($total === false ? 0 : $total) + $product['price'];
                }
                
            }
        }

    } else {
        // Brak domyślnych produktów in Cart, stop action
        logError('Brak domyślnych produktów w Koszyku w _SESSION Cart Products do wysyłki e-mail. Zatrzymana akcja wysyłki formularza!');
        exit;
    }

    // Return prepared Data
    return [
        'mainProduct' => $mainProduct,
        'extraProducts' => $extraProducts,
        'total' => $total,
        'delivery_total' => $delivery_total,
    ];
}