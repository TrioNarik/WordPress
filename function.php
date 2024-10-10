<?php
// =================
remove_action( 'wp_head', 'wp_generator' );

// =================
if ( ! function_exists( 'mkd_enqueue_scripts_for_home_page' ) ) :
    function mkd_enqueue_scripts_for_home_page() {
        if ( is_front_page() || is_home()) {
            wp_enqueue_style(
                'scroll-slider-style',
                get_template_directory_uri() . '/assets/css/scroll-slider.css',
                array(),
                wp_get_theme()->get( 'Version' )
            );
    
            wp_enqueue_script(
                'scroll-slider-script',
                get_template_directory_uri() . '/assets/js/scroll-slider.js',
                array(),
                wp_get_theme()->get( 'Version' ),
                true // przed zamknięciem tagu body
            );
        }
    }
       
endif;

add_action( 'wp_enqueue_scripts', 'mkd_enqueue_scripts_for_home_page' );


// ==============
function create_contact_form($atts) {
    // Użyj $_SERVER['HTTP_REFERER'] jako domyślnej wartości success_page
    $atts = shortcode_atts(array(
        'email' => get_option('admin_email'), // Użycie domyślnego emaila administratora
        'success_page' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', // Użycie referera jako domyślny adres
    ), $atts, 'contact_form');

    // Uruchom sesję, jeśli jeszcze nie jest aktywna
    if (!session_id()) {
        session_start();
    }

    // Zapisz email i stronę sukcesu w sesji
    $_SESSION['recipient_email'] = $atts['email'];
    $_SESSION['success_page'] = $atts['success_page'];

    ob_start();

    // Sprawdzenie, czy istnieją komunikaty w sesji
    if (isset($_SESSION['form_message'])) {
        echo '<div class="form-message">' . esc_html($_SESSION['form_message']) . '</div>';
        unset($_SESSION['form_message']);
    }
    ?>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <input type="hidden" name="action" value="submit_contact_form">

        <label for="name"><?php _e('Name:', 'btr-pen'); ?></label>
        <input type="text" id="name" name="name" required>

        <label for="email"><?php _e('Email:', 'btr-pen'); ?></label>
        <input type="email" id="email" name="email" required>

        <label for="message"><?php _e('Message:', 'btr-pen'); ?></label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <!-- Honeypot field -->
        <input type="text" id="valid" name="valid" style="display:none;" autocomplete="off">

        <input type="submit" value="<?php _e('Send', 'btr-pen'); ?>">
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('contact_form', 'create_contact_form');


function handle_contact_form() {
    // Uruchom sesję, jeśli jeszcze nie jest aktywna
    if (!session_id()) {
        session_start();
    }

    // Sprawdzenie, czy pole honeypot jest puste
    if (!empty($_POST['valid'])) {
        $_SESSION['form_message'] = __('Spam detected.', 'btr-pen');
        wp_redirect(home_url('/contact')); // Upewnij się, że podajesz właściwy URL do formularza
        exit;
    }

    // Pobierz dane z formularza
    $recipient_email = isset($_SESSION['recipient_email']) ? $_SESSION['recipient_email'] : get_option('admin_email');
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $message = sanitize_textarea_field($_POST['message']);

    // Przygotuj wiadomość
    $subject = sprintf(__('New contact form submission from %s', 'btr-pen'), $name);
    $body = sprintf(__('Name: %s\nEmail: %s\nMessage:\n%s', 'btr-pen'), $name, $email, $message);
    $headers = array('Content-Type: text/plain; charset=UTF-8');

    // Wysyłka e-maila
    if (wp_mail($recipient_email, $subject, $body, $headers)) {
        // Ustaw flagę, że formularz został wysłany
        $_SESSION['form_sent'] = true;
        $_SESSION['form_message'] = __('Your message has been sent successfully!', 'btr-pen');

        // Ustalamy stronę sukcesu z sesji lub używamy HTTP_REFERER jako domyślnego
        $success_page = isset($_SESSION['success_page']) ? $_SESSION['success_page'] : '';
        if (!empty($success_page)) {
            wp_redirect($success_page); // Przekierowanie na stronę sukcesu
        } else {
            // Użycie HTTP_REFERER jako domyślnej strony sukcesu
            $default_success_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : home_url('/contact');
            wp_redirect($default_success_page); // Przekierowanie na stronę referującą
        }
    } else {
        $_SESSION['form_message'] = __('Failed to send email. Please try again later.', 'btr-pen');
        wp_redirect(home_url('/contact')); // Upewnij się, że podajesz właściwy URL do formularza
    }
    exit;
}
add_action('admin_post_nopriv_submit_contact_form', 'handle_contact_form');
add_action('admin_post_submit_contact_form', 'handle_contact_form');
