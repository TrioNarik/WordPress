<?php
session_start();

// Wybór języka
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'pl';
$translations = include("lang/$lang.php");
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations['form_title'] ?></title>
</head>
<body>
    <nav>
        <a href="?lang=pl">Polski</a> | <a href="?lang=en">English</a>
    </nav>

    <form>
        <h1><?= $translations['form_title'] ?></h1>
==================================================
/lang
    pl.php
    en.php
/index.php
/config.php
Pliki językowe (np. pl.php):

<?php
return [
    'form_title' => 'Formularz zamówienia',
    'personal_data' => 'Dane personalne',
    'first_name' => 'Imię / Nazwa firmy',
    'last_name' => 'Nazwisko / Numer NIP',
    'email' => 'E-mail',
    'submit_button' => 'Zamawiam',
];


        
Klucze reCAPTCHA
Użyj tego klucza witryny w kodzie HTML wyświetlanym użytkownikom przez Twoją witrynę:
6Lfmd8gSAAAAAE11qV3fCPEVIsy4v9f_0Stp8f6w
Użyj tego tajnego klucza do komunikacji między Twoją witryną a reCAPTCHA:
6Lfmd8gSAAAAALhnRc13l9uWhGtBbe5sNonmC9QB


ShortCode
[contact_form email="ddd@gg.pl" success_page='']

/wp-content/languages/themes/
---------
https://localise.biz/free/poeditor 
===============> generowanie *.mo na podstawie *.po (btr-pen-pl_PL.po => btr-pen-pl_PL.mo)


SECURITY:
https://www.web2generators.com/apache-tools/htpasswd-generator
File:
.htpasswd => folder .secure
.htaccess => 
<Files wp-login.php>
    AuthType Basic
    AuthName "Restricted Area"
    AuthUserFile path\to\.htpasswd
    Require valid-user
</Files>
ps. echo __DIR__; w info.php

https://pl.dental-tribune.com/
+ Interaktywna mapa online "Bezpieczny Gabinet"



--------------------------------------------------
1. <input type="hidden" name="form_start_time" value="<?php echo time(); ?>">
2. if (isset($_POST['submit_contact_form'])) {
    // Sprawdzanie referera
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $allowed_referer = home_url();

    if (strpos($referer, $allowed_referer) === false) {
        die('Nieautoryzowane przesłanie formularza.');
    }

    // Sprawdzanie czasu wypełnienia formularza
    $form_start_time = intval($_POST['form_start_time'] ?? 0);
    $current_time = time();
   // Czas wymagany na wypełnienie (np. minimum 5 sekund)
    $minimum_time = 5;

    if (($current_time - $form_start_time) < $minimum_time) {
        die('Formularz został wypełniony zbyt szybko. Podejrzewamy, że to bot.');
    }

    // Przetwarzanie danych
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $message = sanitize_textarea_field($_POST['message']);

    // Wysłanie wiadomości
    $to = 'example@example.com';
    $subject = 'Nowa wiadomość ze strony';
    $message_body = "Imię: $name\nE-mail: $email\nWiadomość:\n$message";
    $headers = array('Content-Type: text/plain; charset=UTF-8');

    if (wp_mail($to, $subject, $message_body, $headers)) {
        echo 'Wiadomość została wysłana!';
    } else {
        echo 'Wystąpił błąd podczas wysyłania wiadomości.';
    }
}
