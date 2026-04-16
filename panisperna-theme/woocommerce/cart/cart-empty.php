<?php
/**
 * Empty cart page — custom layout with "Il tuo carrello è vuoto" + Novità grid
 *
 * @package Panisperna
 */

defined('ABSPATH') || exit;

do_action('woocommerce_cart_is_empty');
?>

<div class="cart-empty-custom">
    <h1 class="cart-empty-custom__title">Il tuo carrello è vuoto</h1>
    <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="btn btn--outline cart-empty-custom__btn">Vai allo shop</a>
</div>

<?php
$args = [
    'post_type'      => 'product',
    'posts_per_page' => 4,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'tax_query'      => [
        [
            'taxonomy' => 'product_type',
            'field'    => 'slug',
            'terms'    => 'woosb',
            'operator' => 'NOT IN',
        ],
    ],
];
$collezione = new WP_Query($args);

if ($collezione->have_posts()) : ?>
    <section class="section" style="margin-top: var(--space-3xl);">
        <div class="section-title">
            <h2 class="section-title__heading">Novità</h2>
        </div>
        <div class="cards-grid cards-grid--collezione">
            <?php while ($collezione->have_posts()) : $collezione->the_post();
                $product = wc_get_product(get_the_ID());
                include locate_template('template-parts/card-collezione.php');
            endwhile; ?>
        </div>
    </section>
    <?php
    wp_reset_postdata();
endif;
?>
