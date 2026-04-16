<?php
/**
 * Single Product - Custom template
 *
 * @package Panisperna
 */

defined('ABSPATH') || exit;

get_header();

while (have_posts()) :
    the_post();
    global $product;

    $is_bundle = $product->is_type('woosb');
?>

<!-- Product Hero (dark bg) -->
<section class="product-hero">
    <div class="product-hero__inner content-area">
        <!-- Back link -->
        <a href="javascript:history.back()" class="product-hero__back">
            &larr; <span>Indietro</span>
        </a>

        <div class="product-hero__layout">
            <!-- Left: Info -->
            <div class="product-hero__info">
                <h1 class="product-hero__title"><?php the_title(); ?></h1>

                <?php if ($is_bundle) : ?>
                    <p class="product-hero__desc"><?php echo wp_kses_post($product->get_short_description()); ?></p>
                <?php else : ?>
                    <div class="product-hero__meta">
                        <?php
                        $meta_fields = [
                            'Autore/autrice' => $product->get_attribute('autore') ?: $product->get_attribute('Autore'),
                            'Editore'        => $product->get_attribute('casa-editrice') ?: $product->get_attribute('Editore'),
                            'ISBN'           => $product->get_attribute('isbn') ?: $product->get_attribute('ISBN'),
                        ];
                        foreach ($meta_fields as $label => $value) :
                            if (!$value) continue;
                        ?>
                            <div class="product-hero__meta-item">
                                <span class="product-hero__meta-label"><?php echo esc_html($label); ?></span>
                                <span class="product-hero__meta-value"><?php echo esc_html($value); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Price -->
                <div class="product-hero__price">
                    <?php echo $product->get_price_html(); ?>
                    <span class="product-hero__shipping-note" tabindex="0">
                        Più spese di spedizione
                        <?php $shipping_note = panisperna_field('shipping_note', 'option'); if ($shipping_note) : ?>
                            <span class="product-hero__shipping-tooltip"><?php echo esc_html($shipping_note); ?></span>
                        <?php endif; ?>
                    </span>
                </div>

                <!-- Add to cart buttons -->
                <div class="product-hero__actions">
                    <div class="product-hero__qty">
                        <span>Quantità</span>
                        <span class="product-hero__qty-controls">
                            <button class="qty-minus">-</button>
                            <span class="qty-value">1</span>
                            <button class="qty-plus">+</button>
                        </span>
                    </div>
                    <a href="<?php echo esc_url('?add-to-cart=' . get_the_ID()); ?>" class="product-hero__add-to-cart ajax-add-to-cart" data-product-id="<?php echo get_the_ID(); ?>">
                        Aggiungi al carrello
                    </a>
                </div>
            </div>

            <!-- Right: Image -->
            <div class="product-hero__image">
                <?php the_post_thumbnail('large'); ?>
            </div>
        </div>
    </div>

    </div>
</section>

<!-- Description + Bundle Items -->
<main class="site-main content-area">

    <?php
    $has_content     = trim(get_the_content());
    $has_short       = trim($product->get_short_description());
    $has_description = $is_bundle ? ($has_content || $has_short) : $has_content;
    if ($has_description) :
    ?>
    <section class="section">
        <div style="padding:32px 0;">
            <div style="max-width:850px;background:white;">
                <h2 class="h1">
                    Descrizione
                </h2>
            </div>
            <div style="max-width:751px;background:white;margin-top:var(--space-md);">
                <div class="entry-content" style="font-family:var(--font-heading);font-size:24px;line-height:26px;letter-spacing:-0.456px;">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($is_bundle) : ?>
        <?php
        $bundle_items = [];
        if (method_exists($product, 'get_items')) {
            $bundle_items = $product->get_items();
        }
        if (!empty($bundle_items)) : ?>
            <section class="section">
                <div class="cards-grid cards-grid--collezione">
                    <?php foreach ($bundle_items as $item) :
                        $product = wc_get_product($item['id'] ?? $item);
                        if (!$product) continue;
                        include locate_template('template-parts/card-collezione.php');
                    endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>

</main>

<?php
endwhile;
get_footer();
