<?php
session_start();

require_once 'functions.php';

// Sprawdź SESSION Settings
if ($_SESSION['cart']['settings']) {
    $lang = $_SESSION['cart']['settings']['lang'];
    $currency = $_SESSION['cart']['settings']['currency'];

    // Pobranie pliku tłumaczeń
    $lang_file = "../mail/lang/$lang.php";

    // Sprawdź, czy plik tłumaczeń istnieje
    if (file_exists($lang_file)) {
        $translate = safeInclude($lang_file);
    } else {
        logError("Brak pliku tłumaczeń w mail: $lang_file . Zatrzymana akcja wysyłki formularza!", '404');
        exit;
    }
} else {
    // Jeśli lang lub currency nie są ustawione w sesji, zatrzymaj dalsze działanie
    logError('Brak Lang i Currency w _SESSION Settings do wysyłki e-mail. Zatrzymana akcja wysyłki formularza!');
    exit;
}


/// Inicjalizacja zmiennych
$mainProduct = [];
$extraProducts = [];
$total = 0;
$delivery_total = 0;

// Sprawdź SESSION Cart => Products
if ($_SESSION['cart']['products']) {
    // Iteracja po produktach w sesji
    foreach ($_SESSION['cart']['products'] as $key => $product) {
        // Dodaj nazwę produktu i cenę do listy
        $productDetails = $product['name'] . ', ' . $product['price'] . ' ' . $currency;

        // Dodaj ilość i cenę końcową, jeśli istnieje
        if (isset($product['quantity'])) {
            $productDetails .= ' x ' . $product['quantity'] . ' = ' . ($product['price'] * $product['quantity']) . ' ' . $currency;
        }

        // Dodaj kolor i cenę, jeśli istnieje
        if (isset($product['color']['name']) && isset($product['color']['price'])) {
            $productDetails .= "\n[color: " . $product['color']['name'] . ' [' . $product['color']['hex'] . ', ' . $product['color']['ral'] . '], ' . $product['color']['price'] . ' ' . $currency . ']';
        }

        // Dodaj personalizację i cenę, jeśli istnieje
        if (!empty($product['customization'])) {
            foreach ($product['customization'] as $customization) {
                $productDetails .= "\nPersonalizacja: " . 
                    ($customization['title'] ?? '-') . ', ' . 
                    ($customization['text'] ?? '-') . ', ' . 
                    ($customization['align'] ?? '-') . ', ' . 
                    ($customization['font'] ?? '-') . ', ' . 
                    ($customization['color'] ?? '-') . ', ' . 
                    ($customization['size'] ?? '-') . ', ' . 
                    ($customization['format'] ?? '-') . ', ' . 
                    ($customization['price'] ?? '0') . ' ' . $currency;
            }
        }

        // Dodaj flagę i cenę, jeśli istnieje
        if (!empty($product['flag'])) {
            $productDetails .= "\nFlaga: " . ($product['flag']['country'] ?? '-') . ', ' . 
                ($product['flag']['price'] ?? '0') . ' ' . $currency;
        }


        // Kategoryzuj produkty
        if ($key === 'paka') {
            $mainProduct[] = $productDetails;
        } else {
            $extraProducts[] = $productDetails;
        }

        // Dodaj cenę produktu do sumy
        $total += isset($product['quantity']) ? $product['price'] * $product['quantity'] : $product['price'];

        // Dodaj koszt dostawy do sumy
        if (isset($product['shipping']['price'])) {
            $delivery_total += $product['shipping']['price'];
        }
    }
} else {
    // Jeśli nie ma żadnych domyślnych produktów w Koszyku, zatrzymaj dalsze działanie
    logError('Brak domyślnych produktów w Koszyku w _SESSION Cart Products do wysyłki e-mail. Zatrzymana akcja wysyłki formularza!');
    exit;
}


// Załaduj konfigurację mailingów i klucz szyfrujący
$email_config = require '../config/mailer.php';
$admin_config = require '../config/admin.php';

// Logi
$logFile = '../logs/mailing.log';
// Plik zamówień
$file = '../orders.txt';

// Załaduj PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

// Funkcja szyfrowania
function encryptData($data, $key) {
    $iv = random_bytes(openssl_cipher_iv_length('AES-128-CBC')); // Generowanie losowego IV
    $encrypted = openssl_encrypt($data, 'AES-128-CBC', $key, 0, $iv); // Szyfrowanie danych
    return base64_encode($iv . $encrypted); // Dodanie IV do zaszyfrowanych danych
}


// Obsługa formularza
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = "POST NAME";
    $lastname = htmlspecialchars($_POST['lastname']);
    $email    = htmlspecialchars($_POST['email']);
    $phone    = htmlspecialchars($_POST['phone']);
    

    // Przygotowanie danych zamówienia
    $orderData = [
        'name'      => $name,
        'lastname'  => $lastname,
        'email'     => $email,
        'phone'     => $phone,
        'main_product'  => $mainProduct, // paka produkt z SESSION
        'extra_products' => $extraProducts, // lista dodatkowych produktów z SESSION
        'total'     => $total, // z SESSION
        'delivery'  => $delivery_total, // z SESSION
        'date'      => date('Y-m-d'), // Data zamówienia
        'timestamp' => date('Y-m-d H:i:s') // Czas zamówienia ~ do statystyk
    ];

    // Wczytaj szablon email
    $template = file_get_contents('../mail/order.html');

    // Mapa placeholderów do tłumaczeń
    $placeholders = [
        '[lang:content_title]'                          => $translate['content']['title'],
        '[lang:content_header_hello]'                   => $translate['content']['header']['hello'],
        '[lang:content_header_intro]'                   => $translate['content']['header']['intro'],
        '[lang:content_summary_table_header_title]'     => $translate['content']['summary']['table']['header']['title'],
        '[lang:content_summary_table_header_date]'      => $translate['content']['summary']['table']['header']['date'],
        '[lang:content_summary_table_rows_total]'       => $translate['content']['summary']['table']['rows']['total'],
        '[lang:content_summary_table_rows_name]'        => $translate['content']['summary']['table']['rows']['name'],
        '[lang:content_summary_table_rows_lastname]'    => $translate['content']['summary']['table']['rows']['lastname'],
        '[lang:content_summary_table_rows_email]'       => $translate['content']['summary']['table']['rows']['email'],
        '[lang:content_summary_table_rows_phone]'       => $translate['content']['summary']['table']['rows']['phone'],
        '[lang:content_summary_table_rows_order]'      => $translate['content']['summary']['table']['rows']['order'],
        '[lang:content_summary_table_rows_exta]'        => $translate['content']['summary']['table']['rows']['extra'],
        '[lang:content_helper_head]'                    => $translate['content']['helper']['head'],
        '[lang:content_helper_content]'                 => $translate['content']['helper']['content'],
        '[lang:content_helper_info]'                    => $translate['content']['helper']['info'],
        '[lang:footer_link]'                            => $translate['footer']['link'],
        '[lang:footer_copyright]'                       => $translate['footer']['copyright'],
        '[lang:currency]'                               => $currency,
        '[lang:content_header_shop]'                    => $translate['content']['header']['shop'],
        // ===== CONFIG OPTIONS ====
        '[[admin_office_phone]]'                        => $admin_config['admin']['office_phone'],
        '[[admin_office_email]]'                        => $admin_config['admin']['office_email']
    ];
        

    // Zamiana wartości z formularza w szablonie
    foreach ($orderData as $key => $value) {
        if ($key === 'main_product' || $key === 'extra_products') {
            // Generowanie listy produktów z cenami
            $productHtml = '';
            foreach ($value as $productDetails) {
                $productHtml .= "<li>" . htmlspecialchars($productDetails) . "</li>";
            }
            // Zamiana znacznika na gotową listę produktów
            $template = str_replace('{{_' . $key . '_}}', "<ul>$productHtml</ul>", $template);
        } else {
            // Zamiana innych zmiennych w szablonie
            $template = str_replace('{{_' . $key . '_}}', htmlspecialchars($value), $template);
        }
    }

    // Zamiana placeholderów na tłumaczenia
    $emailContent = strtr($template, $placeholders);
    print_r($emailContent);
    

    // Serializacja i szyfrowanie
    $orderJson = json_encode($orderData, JSON_PRETTY_PRINT);
    $encryptedOrder = encryptData($orderJson, $admin_config['admin']['secret_key']);

    // Zapis do pliku zamówień
    if (file_put_contents($file, $encryptedOrder . "\n", FILE_APPEND) === false) {
        error_log("Nie udało się zapisać zamówienia do pliku: $file\n", 3, $logFile);
        exit;
    }

    // Przygotowanie wiadomości e-mail
    $subjectAdmin   = "Nowe zamówienie - Hussaria Electra";
    $subjectClient  = 'Potwierdzenie zamówienia - Hussaria Electra';

    // $messageAdmin = "Zamówienie od klienta:\n\nImię i nazwisko: $name\nAdres: $address\n\nProdukty:\n";

    // foreach ($products as $product) {
    //     if (isset($productPrices[$product])) {
    //         $messageAdmin .= "$product - " . $productPrices[$product] . " PLN\n";
    //     }
    // }

    // $messageAdmin .= "\nŁączna kwota: $orderData['total'] $currency['symbol']";

    
    // Sprawdź czy opcja wysyłania maili jest włączona w konfiguracji formularza
    if  ($admin_config['admin']['active'] === 1) {
    // Wysyłanie e-maili (PHPMailer)
        $mail = new PHPMailer(true);
        try {
            // Konfiguracja SMTP
            $mail->isSMTP();
            $mail->Host = $email_config['smtp']['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $email_config['smtp']['username'];
            $mail->Password = $email_config['smtp']['password'];
            $mail->SMTPSecure = $email_config['smtp']['encryption'];
            $mail->Port = $email_config['smtp']['port'];

            // Mail do admina
            $mail->setFrom($email_config['smtp']['from_email'], $email_config['smtp']['from_name']);
            $mail->addAddress($admin_config['admin']['mail']);
            $mail->Subject = $subjectAdmin;
            $mail->CharSet = 'UTF-8';
            $mail->Encoding="base64";
            $mail->isHTML(true); // Tryb HTML
            $mail->Body = $template; // Załadowany i uzupełniony szablon ~ może być inny
            $mail->send();

            // Mail do klienta
            $mail->clearAddresses();
            $mail->addAddress($email);
            $mail->Subject = $subjectClient;
            $mail->CharSet = 'UTF-8';
            $mail->Encoding="base64";
            $mail->isHTML(true); // Tryb HTML
            $mail->Body = $template; // Załadowany i uzupełniony szablon
            $mail->send();

            echo "Zamówienie przyjęte! Wartość: " . number_format($total, 2) . " PLN";
        } catch (Exception $e) {
            // Zapisz błąd w pliku logów
            error_log(date('[Y-m-d H:i:s]') . " Błąd przy wysyłaniu e-maili: " . $e->getMessage() . "\n", 3, $logFile);
            echo "Nie udało się wysłać wiadomości e-mail. Błąd: {$mail->ErrorInfo}";
        }
    } else {
        echo "Wysyłania wiadomości przez formularz jest wyłączone !";
    }
// } else {
//     http_response_code(405);
//     // Zapisz błąd w pliku logów
//     error_log("Nieprawidłowa metoda żądania - " . $_SERVER['REQUEST_METHOD'] . "\n", 3, $logFile);
//     echo "Nieprawidłowa metoda żądania.";
// }
