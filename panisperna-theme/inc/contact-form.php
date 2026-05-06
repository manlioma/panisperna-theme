<?php
/**
 * Contatti form handler — Brevo Transactional Email integration
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

    $result = panisperna_contact_send_brevo($nome, $cognome, $email, $messaggio);

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
   BREVO SEND
   -------------------------------------------------------------------------- */

function panisperna_contact_send_brevo($nome, $cognome, $email, $messaggio) {
    $api_key = get_option('panisperna_brevo_api_key', '');
    if (!$api_key) {
        return new WP_Error('no_key', 'Brevo API key missing in option panisperna_brevo_api_key');
    }

    $recipient = get_option('panisperna_contatti_recipient', 'mmanlio@gmail.com');

    $body = [
        'sender'  => [ 'name' => 'Libreria Panisperna 220', 'email' => 'noreply@panisperna220.it' ],
        'to'      => [ [ 'email' => $recipient ] ],
        'replyTo' => [ 'email' => $email, 'name' => trim($nome . ' ' . $cognome) ],
        'subject' => 'Nuovo messaggio dal sito - ' . $nome . ' ' . $cognome,
        'htmlContent' => '<p><strong>Da:</strong> ' . esc_html($nome . ' ' . $cognome) . ' &lt;' . esc_html($email) . '&gt;</p>'
                       . '<p><strong>Messaggio:</strong></p><p>' . nl2br(esc_html($messaggio)) . '</p>',
    ];

    $response = wp_remote_post('https://api.brevo.com/v3/smtp/email', [
        'timeout' => 15,
        'headers' => [
            'api-key'      => $api_key,
            'accept'       => 'application/json',
            'content-type' => 'application/json',
        ],
        'body' => wp_json_encode($body),
    ]);

    if (is_wp_error($response)) {
        return $response;
    }

    $code = wp_remote_retrieve_response_code($response);
    if ($code >= 200 && $code < 300) {
        return true;
    }

    return new WP_Error('brevo_http_' . $code, wp_remote_retrieve_body($response));
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

    $result = panisperna_contact_send_brevo($nome, $cognome, $email, $messaggio);
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
