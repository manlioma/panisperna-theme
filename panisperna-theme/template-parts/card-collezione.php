<?php
/**
 * Component: Collezione Card (product)
 *
 * Used in: archive-product, taxonomy-product_cat/tag, single-product, single-consiglio, single-evento, AJAX handler
 * Expects: $product (WC_Product object) set before include
 *
 * @package Panisperna
 */

if (!$product || !is_a($product, 'WC_Product')) return;
?>
<div class="card card--collezione">
    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="card--collezione__image">
        <?php echo $product->get_image('large'); ?>
    </a>
    <div class="card__body">
        <h4 class="card__title"><?php echo esc_html($product->get_name()); ?></h4>
        <?php $autore = $product->get_attribute('autore') ?: $product->get_attribute('Autore');
        if ($autore) : ?>
            <p class="card__author"><?php echo esc_html($autore); ?></p>
        <?php endif; ?>
        <div class="card__meta">
            <span class="card__price"><?php echo $product->get_price_html(); ?></span>
            <a href="<?php echo esc_url('?add-to-cart=' . $product->get_id()); ?>" class="btn--icon ajax-add-to-cart" data-product-id="<?php echo esc_attr($product->get_id()); ?>">+</a>
        </div>
    </div>
    <div class="card__dot"></div>
</div>
