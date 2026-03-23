<?php
/**
 * Panisperna Libreria - Theme Functions
 *
 * @package Panisperna
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

define('PANISPERNA_VERSION', '1.0.0');
define('PANISPERNA_DIR', get_template_directory());
define('PANISPERNA_URI', get_template_directory_uri());

/* --------------------------------------------------------------------------
   0. ALLOW SVG UPLOADS
   -------------------------------------------------------------------------- */

add_filter('upload_mimes', function ($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
    if (str_ends_with($filename, '.svg')) {
        $data['ext'] = 'svg';
        $data['type'] = 'image/svg+xml';
    }
    return $data;
}, 10, 4);

/* --------------------------------------------------------------------------
   1. THEME SETUP
   -------------------------------------------------------------------------- */

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('custom-logo', [
        'height'      => 66,
        'width'       => 1141,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    register_nav_menus([
        'primary'      => __('Menu Principale', 'panisperna'),
        'footer-col-1' => __('Footer Colonna 1', 'panisperna'),
        'footer-col-2' => __('Footer Colonna 2', 'panisperna'),
    ]);

    add_image_size('card-thumb', 566, 400, true);
    add_image_size('book-cover', 248, 331, true);
    add_image_size('hero-image', 363, 200, true);
    add_image_size('event-card', 566, 272, true);
    add_image_size('consiglio-portrait', 272, 495, true);
});

/* --------------------------------------------------------------------------
   2. ENQUEUE STYLES & SCRIPTS
   -------------------------------------------------------------------------- */

add_action('wp_enqueue_scripts', function () {
    // Google Fonts: Reddit Sans + Inter
    wp_enqueue_style(
        'panisperna-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@500;600&family=Reddit+Sans:wght@400;600;700&display=swap',
        [],
        null
    );

    // Swiper
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], '11');
    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '11', true);

    wp_enqueue_style('panisperna-main', PANISPERNA_URI . '/assets/css/main.css', ['swiper'], PANISPERNA_VERSION);
    wp_enqueue_style('panisperna-style', get_stylesheet_uri(), ['panisperna-main'], PANISPERNA_VERSION);

    wp_enqueue_script('panisperna-main', PANISPERNA_URI . '/assets/js/main.js', ['swiper'], PANISPERNA_VERSION, true);
});

/* --------------------------------------------------------------------------
   3. CUSTOM POST TYPES
   -------------------------------------------------------------------------- */

add_action('init', function () {
    // CPT: Evento (Incontri)
    register_post_type('evento', [
        'labels' => [
            'name'          => 'Eventi',
            'singular_name' => 'Evento',
            'add_new_item'  => 'Aggiungi Evento',
            'edit_item'     => 'Modifica Evento',
            'all_items'     => 'Tutti gli Eventi',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'incontri'],
        'menu_icon'    => 'dashicons-calendar-alt',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);

    // CPT: Consiglio (Parola di)
    register_post_type('consiglio', [
        'labels' => [
            'name'          => 'Consigli',
            'singular_name' => 'Consiglio',
            'add_new_item'  => 'Aggiungi Consiglio',
            'edit_item'     => 'Modifica Consiglio',
            'all_items'     => 'Tutti i Consigli',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'parola-di'],
        'menu_icon'    => 'dashicons-format-quote',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);

    // Taxonomy: Tipo Evento
    register_taxonomy('tipo_evento', 'evento', [
        'labels' => [
            'name'          => 'Tipi Evento',
            'singular_name' => 'Tipo Evento',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'tipo-evento'],
        'show_in_rest' => true,
    ]);
});

/* --------------------------------------------------------------------------
   4. ACF OPTIONS PAGE
   -------------------------------------------------------------------------- */

add_action('acf/init', function () {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => 'Impostazioni Panisperna',
            'menu_title' => 'Panisperna',
            'menu_slug'  => 'panisperna-settings',
            'capability' => 'edit_posts',
            'icon_url'   => 'dashicons-book',
            'redirect'   => false,
        ]);

        acf_add_options_sub_page([
            'page_title'  => 'Footer & Contatti',
            'menu_title'  => 'Footer & Contatti',
            'parent_slug' => 'panisperna-settings',
        ]);

        acf_add_options_sub_page([
            'page_title'  => 'Social & Newsletter',
            'menu_title'  => 'Social & Newsletter',
            'parent_slug' => 'panisperna-settings',
        ]);
    }
});

/* --------------------------------------------------------------------------
   5. WOOCOMMERCE CUSTOMIZATIONS
   -------------------------------------------------------------------------- */

// Remove default WooCommerce wrappers
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', function () {
    echo '<main class="site-main content-area">';
}, 10);

add_action('woocommerce_after_main_content', function () {
    echo '</main>';
}, 10);

// Products per row
add_filter('loop_shop_columns', function () {
    return 4;
});

// Products per page
add_filter('loop_shop_per_page', function () {
    return 12;
});

/* --------------------------------------------------------------------------
   6. HELPER FUNCTIONS
   -------------------------------------------------------------------------- */

/**
 * Get ACF field with fallback
 */
function panisperna_field($field, $post_id = false, $fallback = '') {
    if (!function_exists('get_field')) {
        return $fallback;
    }
    $value = get_field($field, $post_id);
    return $value ?: $fallback;
}

/**
 * Get theme SVG icon
 */
function panisperna_icon($name) {
    $path = PANISPERNA_DIR . '/assets/images/' . $name . '.svg';
    if (file_exists($path)) {
        return file_get_contents($path);
    }
    return '';
}

/* --------------------------------------------------------------------------
   7. INCLUDES
   -------------------------------------------------------------------------- */

// ACF Field Groups (registered in PHP for version control)
require_once PANISPERNA_DIR . '/inc/acf-fields.php';
