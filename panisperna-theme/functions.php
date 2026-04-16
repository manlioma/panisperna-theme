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

    // Ensure WC cart fragments are loaded on every page (not just cart/checkout)
    if (class_exists('WooCommerce')) {
        wp_enqueue_script('wc-cart-fragments');
    }

    wp_enqueue_script('panisperna-main', PANISPERNA_URI . '/assets/js/main.js', ['swiper', 'jquery', 'wc-cart-fragments'], PANISPERNA_VERSION, true);

    wp_localize_script('panisperna-main', 'panisperna_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('panisperna_nonce'),
        'shop_url' => esc_url(get_permalink(wc_get_page_id('shop'))),
    ]);
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

    }
});

/* --------------------------------------------------------------------------
   5. WOOCOMMERCE COMING SOON — custom maintenance page
   -------------------------------------------------------------------------- */

add_filter('template_include', function ($template) {
    // Only when WooCommerce coming-soon mode is active
    if (get_option('woocommerce_coming_soon') !== 'yes') {
        return $template;
    }
    // Admins see the normal site
    if (current_user_can('manage_woocommerce')) {
        return $template;
    }
    // Private share link bypass
    if (get_option('woocommerce_private_link') === 'yes') {
        $share_key = get_option('woocommerce_share_key');
        if (
            (isset($_GET['woo-share']) && $share_key === $_GET['woo-share']) ||
            (isset($_COOKIE['woo-share']) && $share_key === $_COOKIE['woo-share'])
        ) {
            return $template;
        }
    }
    status_header(503);
    header('Retry-After: 3600');
    include get_template_directory() . '/coming-soon.php';
    exit;
}, 5); // priority 5 = before WooCommerce's default (10)

/* --------------------------------------------------------------------------
   5b. WOOCOMMERCE CUSTOMIZATIONS
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
 * Format event date: "Mar 06 Apr"
 */
function panisperna_format_event_date($date_str) {
    if (!$date_str) return '';
    $date = DateTime::createFromFormat('d/m/Y', $date_str);
    if (!$date) return $date_str;
    $days_it = ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'];
    $months_it = ['', 'Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];
    return $days_it[(int)$date->format('w')] . ' ' . $date->format('d') . ' ' . $months_it[(int)$date->format('n')];
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

/* --------------------------------------------------------------------------
   8. NAV LINKS — add data-text for bold-hover trick
   -------------------------------------------------------------------------- */

add_filter('nav_menu_link_attributes', function ($atts, $item) {
    $atts['data-text'] = $item->title;
    return $atts;
}, 10, 2);

/* --------------------------------------------------------------------------
   AJAX: La nostra collezione — filter + load more
   -------------------------------------------------------------------------- */

add_action('wp_ajax_panisperna_collezione', 'panisperna_collezione_handler');
add_action('wp_ajax_nopriv_panisperna_collezione', 'panisperna_collezione_handler');

function panisperna_collezione_handler() {
    check_ajax_referer('panisperna_nonce', 'nonce');

    $category = sanitize_text_field($_POST['category'] ?? '');
    $page     = absint($_POST['page'] ?? 1);
    $per_page = 8;

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'tax_query'      => [],
    ];

    // Exclude bundles (product type, not category)
    $args['tax_query'][] = [
        'taxonomy' => 'product_type',
        'field'    => 'slug',
        'terms'    => 'woosb',
        'operator' => 'NOT IN',
    ];

    // If a specific category is selected (not "tutti")
    if ($category && $category !== 'tutti') {
        $args['tax_query']['relation'] = 'AND';
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $category,
        ];
    }

    $query = new WP_Query($args);
    $html  = '';

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            $product = wc_get_product(get_the_ID());
            include locate_template('template-parts/card-collezione.php');
        }
        $html = ob_get_clean();
    }

    wp_reset_postdata();

    wp_send_json_success([
        'html'      => $html,
        'max_pages' => $query->max_num_pages,
        'found'     => $query->found_posts,
    ]);
}

/* --------------------------------------------------------------------------
   AJAX: Pacchetti — load more
   -------------------------------------------------------------------------- */

add_action('wp_ajax_panisperna_pacchetti_loadmore', 'panisperna_pacchetti_loadmore_handler');
add_action('wp_ajax_nopriv_panisperna_pacchetti_loadmore', 'panisperna_pacchetti_loadmore_handler');

function panisperna_pacchetti_loadmore_handler() {
    check_ajax_referer('panisperna_nonce', 'nonce');
    $page     = absint($_POST['page'] ?? 1);
    $per_page = 4;

    $query = new WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'offset'         => 5 + ($page - 2) * $per_page,
        'tax_query'      => [
            ['taxonomy' => 'product_type', 'field' => 'slug', 'terms' => 'woosb'],
        ],
    ]);

    $html = '';
    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            $product = wc_get_product(get_the_ID());
            ?>
            <div class="card card--pacchetto">
                <a href="<?php the_permalink(); ?>" class="card--pacchetto__image">
                    <?php the_post_thumbnail('large'); ?>
                </a>
                <div class="card__body">
                    <h4 class="card__title"><?php the_title(); ?></h4>
                    <div class="card__meta">
                        <span class="card__price"><?php echo $product->get_price_html(); ?></span>
                        <a href="<?php echo esc_url('?add-to-cart=' . get_the_ID()); ?>" class="btn--icon ajax-add-to-cart" data-product-id="<?php echo get_the_ID(); ?>">+</a>
                    </div>
                </div>
                <div class="card__dot"></div>
            </div>
            <?php
        }
        $html = ob_get_clean();
    }
    wp_reset_postdata();

    // Total bundles minus the 5 initially shown
    $total = new WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'tax_query'      => [
            ['taxonomy' => 'product_type', 'field' => 'slug', 'terms' => 'woosb'],
        ],
    ]);
    $total_count = $total->found_posts;
    $shown = 5 + ($page - 1) * $per_page;

    wp_send_json_success([
        'html'     => $html,
        'has_more' => $shown < $total_count,
    ]);
}

/* --------------------------------------------------------------------------
   AJAX: Parola di (consiglio archive) — load more
   -------------------------------------------------------------------------- */

add_action('wp_ajax_panisperna_consiglio_loadmore', 'panisperna_consiglio_loadmore_handler');
add_action('wp_ajax_nopriv_panisperna_consiglio_loadmore', 'panisperna_consiglio_loadmore_handler');

function panisperna_consiglio_loadmore_handler() {
    check_ajax_referer('panisperna_nonce', 'nonce');
    $page     = absint($_POST['page'] ?? 1);
    $per_page = 8;

    $query = new WP_Query([
        'post_type'      => 'consiglio',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    ]);

    $html = '';
    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/card-consiglio');
        endwhile;
        $html = ob_get_clean();
    }
    wp_reset_postdata();

    wp_send_json_success([
        'html'      => $html,
        'max_pages' => $query->max_num_pages,
        'found'     => $query->found_posts,
    ]);
}

/* --------------------------------------------------------------------------
   AJAX: Incontri (archive-evento) — filter by tipo_evento + load more
   -------------------------------------------------------------------------- */

add_action('wp_ajax_panisperna_eventi', 'panisperna_eventi_handler');
add_action('wp_ajax_nopriv_panisperna_eventi', 'panisperna_eventi_handler');

function panisperna_eventi_handler() {
    check_ajax_referer('panisperna_nonce', 'nonce');

    $category = sanitize_text_field($_POST['category'] ?? '');
    $page     = absint($_POST['page'] ?? 1);
    $per_page = 8;

    $args = [
        'post_type'      => 'evento',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    ];

    if ($category && $category !== 'tutti') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'tipo_evento',
                'field'    => 'slug',
                'terms'    => $category,
            ],
        ];
    }

    $query = new WP_Query($args);
    $html  = '';

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/card-evento');
        endwhile;
        $html = ob_get_clean();
    }
    wp_reset_postdata();

    wp_send_json_success([
        'html'      => $html,
        'max_pages' => $query->max_num_pages,
        'found'     => $query->found_posts,
    ]);
}

/* --------------------------------------------------------------------------
   9. SIDEBAR CART AJAX — remove item & clear cart
   -------------------------------------------------------------------------- */

add_action('wp_ajax_panisperna_cart_remove', 'panisperna_cart_remove');
add_action('wp_ajax_nopriv_panisperna_cart_remove', 'panisperna_cart_remove');

function panisperna_cart_remove() {
    check_ajax_referer('panisperna_nonce', 'nonce');
    $key = sanitize_text_field($_POST['cart_key'] ?? '');
    if ($key) {
        WC()->cart->remove_cart_item($key);
    }
    wp_send_json_success(['count' => WC()->cart->get_cart_contents_count()]);
}

add_action('wp_ajax_panisperna_cart_clear', 'panisperna_cart_clear');
add_action('wp_ajax_nopriv_panisperna_cart_clear', 'panisperna_cart_clear');

function panisperna_cart_clear() {
    check_ajax_referer('panisperna_nonce', 'nonce');
    WC()->cart->empty_cart();
    wp_send_json_success(['count' => 0]);
}

/* --------------------------------------------------------------------------
   10. SHORTCODE: novità grid (card-collezione) for empty cart
   -------------------------------------------------------------------------- */

add_shortcode('panisperna_novita', function ($atts) {
    $atts = shortcode_atts(['count' => 4], $atts);
    $query = new WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => intval($atts['count']),
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => [
            ['taxonomy' => 'product_type', 'field' => 'slug', 'terms' => 'woosb', 'operator' => 'NOT IN'],
        ],
    ]);
    if (!$query->have_posts()) return '';
    ob_start();
    echo '<div class="cards-grid cards-grid--collezione">';
    while ($query->have_posts()) {
        $query->the_post();
        $product = wc_get_product(get_the_ID());
        include locate_template('template-parts/card-collezione.php');
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
});

/* --------------------------------------------------------------------------
   11. SHIPPING RULES — 5+ libri o classe "B - Pesante" → 7,90
   -------------------------------------------------------------------------- */

add_filter('woocommerce_package_rates', function ($rates, $package) {
    $heavy_class_slug = 'b-pesante';
    $threshold_qty    = 5;
    $high_cost        = 7.90;

    $total_qty   = 0;
    $has_heavy   = false;

    foreach ($package['contents'] as $item) {
        $product = $item['data'];
        $product_obj = wc_get_product($item['product_id']);

        // Bundle (WooSB): count bundled items inside
        if ($product_obj && $product_obj->is_type('woosb') && method_exists($product_obj, 'get_items')) {
            $bundle_items = $product_obj->get_items();
            $total_qty += count($bundle_items) * $item['quantity'];
        } else {
            // Skip bundle child items (already counted via parent)
            if (!empty($item['woosb_parent_id'])) continue;
            $total_qty += $item['quantity'];
        }

        if ($product->get_shipping_class() === $heavy_class_slug) {
            $has_heavy = true;
        }
    }

    if ($total_qty >= $threshold_qty || $has_heavy) {
        foreach ($rates as $rate_id => $rate) {
            if ($rate->method_id === 'flat_rate') {
                $rates[$rate_id]->cost = $high_cost;
                // Reset taxes for the new cost
                $taxes = [];
                foreach ($rates[$rate_id]->taxes as $tax_id => $tax) {
                    $taxes[$tax_id] = 0;
                }
                $rates[$rate_id]->taxes = $taxes;
            }
        }
    }

    return $rates;
}, 10, 2);

/* --------------------------------------------------------------------------
   11. CART FRAGMENTS — badge + sidebar auto-refresh (standard WC pattern)
   -------------------------------------------------------------------------- */

add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    $count = WC()->cart->get_cart_contents_count();

    // Fragment 1: cart badge
    if ($count > 0) {
        $fragments['.cart-count'] = '<span class="cart-count">' . esc_html($count) . '</span>';
    } else {
        $fragments['.cart-count'] = '<span class="cart-count" style="display:none"></span>';
    }

    // Fragment 2: sidebar cart body
    ob_start();
    ?>
    <div class="sidebar-cart__body">
    <?php if ($count > 0) : ?>
        <div class="sidebar-cart__items">
            <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                $product = $cart_item['data'];
                $quantity = $cart_item['quantity'];
                $thumbnail = $product->get_image('thumbnail');
            ?>
                <div class="sidebar-cart__item" data-key="<?php echo esc_attr($cart_item_key); ?>">
                    <div class="sidebar-cart__item-image"><?php echo $thumbnail; ?></div>
                    <div class="sidebar-cart__item-info">
                        <span class="sidebar-cart__item-name"><?php echo esc_html($product->get_name()); ?></span>
                        <span class="sidebar-cart__item-qty">&times;<?php echo esc_html($quantity); ?></span>
                        <span class="sidebar-cart__item-price"><?php echo $product->get_price_html(); ?></span>
                    </div>
                    <button type="button" class="sidebar-cart__item-remove" data-key="<?php echo esc_attr($cart_item_key); ?>" aria-label="Rimuovi">&times;</button>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="sidebar-cart__footer">
            <div class="sidebar-cart__subtotal">
                <span>Subtotale</span>
                <span><?php echo WC()->cart->get_cart_subtotal(); ?></span>
            </div>
            <div class="sidebar-cart__subtotal sidebar-cart__shipping">
                <span>Spedizione</span>
                <span><?php
                    $shipping_total = WC()->cart->get_shipping_total();
                    echo floatval($shipping_total) > 0 ? wc_price($shipping_total) : 'Calcolata al checkout';
                ?></span>
            </div>
            <div class="sidebar-cart__subtotal sidebar-cart__total">
                <span>Totale</span>
                <span><?php echo WC()->cart->get_total(); ?></span>
            </div>
            <button type="button" class="sidebar-cart__clear" id="sidebar-cart-clear">Svuota carrello</button>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="btn btn--outline sidebar-cart__btn">Vai al carrello</a>
            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="btn btn--primary sidebar-cart__btn">Checkout</a>
        </div>
    <?php else : ?>
        <p class="sidebar-cart__empty">Il carrello e' vuoto.</p>
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn--outline sidebar-cart__btn">Vai allo shop</a>
    <?php endif; ?>
    </div>
    <?php
    $fragments['.sidebar-cart__body'] = ob_get_clean();

    return $fragments;
});
