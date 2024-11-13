<?php
session_start();
if (!isset($_SESSION['form_token'])) {
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
}
?>
<form action="" method="POST">
    <input type="hidden" name="form_token" value="<?php echo $_SESSION['form_token']; ?>">
    <!-- Pola formularza -->
    <input type="submit" name="submit_contact_form" value="Wyślij">
</form>


if (isset($_POST['submit_contact_form'])) {
    session_start();
    
    // Sprawdzenie, czy token jest ustawiony i poprawny
    if (isset($_POST['form_token'], $_SESSION['form_token']) && $_POST['form_token'] === $_SESSION['form_token']) {
        // Usuń token z sesji po jego użyciu
        unset($_SESSION['form_token']);
        
        // Obsługa formularza (np. wysyłanie wiadomości e-mail)
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);
        
        if (wp_mail('example@example.com', 'Temat', "Wiadomość od: $name\n$email\n$message")) {
            echo 'Wiadomość została wysłana!';
        } else {
            echo 'Wystąpił błąd podczas wysyłania wiadomości.';
        }
    } else {
        echo 'Nieprawidłowy token. Spróbuj ponownie.';
    }
}
