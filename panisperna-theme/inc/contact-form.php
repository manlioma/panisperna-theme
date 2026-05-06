<?php
/**
 * Contatti form handler — uses wp_mail() so delivery flows through whatever
 * SMTP/relay WooCommerce already has configured (currently the
 * woocommerce-sendinblue-newsletter-subscription / Brevo plugin).
 *
 * @package Panisperna
 */

defined('ABSPATH') || exit;

/* --------------------------------------------------------------------------
   AJAX HOOKS
   -------------------------------------------------------------------------- */

add_action('wp_ajax_panisperna_contact',        'panisperna_contact_handler');
add_action('wp_ajax_nopriv_panisperna_contact', 'panisperna_contact_handler');

/* --------------------------------------------------------------------------
   AJAX HANDLER
   -------------------------------------------------------------------------- */

function panisperna_contact_handler() {
    check_ajax_referer('panisperna_contact_nonce', 'nonce');

    // Honeypot — silent success, no email
    if (!empty($_POST['website'])) {
        wp_send_json_success(['ok' => true]);
        return;
    }

    $nome      = sanitize_text_field(wp_unslash($_POST['nome'] ?? ''));
    $cognome   = sanitize_text_field(wp_unslash($_POST['cognome'] ?? ''));
    $email     = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $messaggio = sanitize_textarea_field(wp_unslash($_POST['messaggio'] ?? ''));

    if (
        !$nome || !$cognome || !$email || !$messaggio
        || !is_email($email)
        || strlen($nome) > 80
        || strlen($cognome) > 80
        || strlen($messaggio) > 500
    ) {
        wp_send_json_error(['message' => 'Campi non validi.'], 400);
        return;
    }

    $result = panisperna_contact_send_mail($nome, $cognome, $email, $messaggio);

    if (true === $result) {
        wp_send_json_success(['ok' => true, 'message' => 'Grazie! Il tuo messaggio e stato inviato.']);
        return;
    }

    if (is_wp_error($result)) {
        error_log('[panisperna_contact] ' . $result->get_error_message());
    }
    wp_send_json_error(['message' => 'Invio non riuscito. Riprova piu tardi.'], 502);
}

/* --------------------------------------------------------------------------
   SEND via wp_mail() — picked up by the Brevo SMTP plugin already wired to WC
   -------------------------------------------------------------------------- */

function panisperna_contact_send_mail($nome, $cognome, $email, $messaggio) {
    $recipient    = get_option('panisperna_contatti_recipient', 'mmanlio@gmail.com');
    $sender_name  = 'Libreria Panisperna 220';
    $sender_email = 'info@libreriapanisperna220.it';
    $full_name    = trim($nome . ' ' . $cognome);

    $subject = 'Nuovo messaggio dal sito - ' . $full_name;

    $body = '<p><strong>Da:</strong> ' . esc_html($full_name) . ' &lt;' . esc_html($email) . '&gt;</p>'
          . '<p><strong>Messaggio:</strong></p><p>' . nl2br(esc_html($messaggio)) . '</p>';

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $sender_name . ' <' . $sender_email . '>',
        'Reply-To: ' . $full_name . ' <' . $email . '>',
    ];

    $sent = wp_mail($recipient, $subject, $body, $headers);

    if (!$sent) {
        return new WP_Error('wp_mail_failed', 'wp_mail() returned false for recipient ' . $recipient);
    }
    return true;
}

/* --------------------------------------------------------------------------
   NO-JS FALLBACK (template_redirect)
   -------------------------------------------------------------------------- */

add_action('template_redirect', 'panisperna_contact_no_js_handler');
function panisperna_contact_no_js_handler() {
    if (!is_page_template('page-contatti.php')) {
        return;
    }
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return;
    }
    if (empty($_POST['panisperna_contact_submit'])) {
        return;
    }

    $back = wp_get_referer() ?: home_url('/contatti');

    if (
        !isset($_POST['panisperna_contact_nonce'])
        || !wp_verify_nonce($_POST['panisperna_contact_nonce'], 'panisperna_contact_nonce')
    ) {
        wp_safe_redirect(add_query_arg('contact', 'err', $back));
        exit;
    }

    if (!empty($_POST['website'])) {
        wp_safe_redirect(add_query_arg('contact', 'ok', $back));
        exit;
    }

    $nome      = sanitize_text_field(wp_unslash($_POST['nome'] ?? ''));
    $cognome   = sanitize_text_field(wp_unslash($_POST['cognome'] ?? ''));
    $email     = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $messaggio = sanitize_textarea_field(wp_unslash($_POST['messaggio'] ?? ''));

    if (
        !$nome || !$cognome || !is_email($email) || !$messaggio
        || strlen($nome) > 80
        || strlen($cognome) > 80
        || strlen($messaggio) > 500
    ) {
        wp_safe_redirect(add_query_arg('contact', 'err', $back));
        exit;
    }

    $result = panisperna_contact_send_mail($nome, $cognome, $email, $messaggio);
    if (is_wp_error($result)) {
        error_log('[panisperna_contact] ' . $result->get_error_message());
    }
    $flag = (true === $result) ? 'ok' : 'err';
    wp_safe_redirect(add_query_arg('contact', $flag, $back));
    exit;
}

/* --------------------------------------------------------------------------
   NOTICE RENDERER (called from page template)
   -------------------------------------------------------------------------- */

function panisperna_contact_notice() {
    $flag = $_GET['contact'] ?? '';
    if ($flag === 'ok') {
        return '<p class="contact-form__notice contact-form__notice--ok" role="status">Grazie! Il tuo messaggio e stato inviato.</p>';
    }
    if ($flag === 'err') {
        return '<p class="contact-form__notice contact-form__notice--err" role="alert">Invio non riuscito. Riprova piu tardi.</p>';
    }
    return '';
}
