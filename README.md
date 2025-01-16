<div class="notification" id="notification">
    Produkt został dodany!
</div>

<style>
        .box {
            transition: background-color 0.3s, border-color 0.3s;
        }
        .box.checked {
            background-color: #e0ffe0;
            border: 1px solid #00cc00;
        }
        

        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: none;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .box.collapsed .detail, .pack-image {
            display: none;
        }
        .box.collapsed .pack-image {
            display: block;
            width: 100%;
            height: auto;
        }

</style>

<script>
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
</script>


===================================================
const calculateTotalPrice = (products, productCheckboxes) => {
    let total = 0;
    productCheckboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            const productName = checkbox.value;
            const productPrice = parseFloat(checkbox.dataset.price) || 0;  // Bezpośrednio z checkboxa
            total += productPrice;
        }
    });
    return total;
};
==================================================

        
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
