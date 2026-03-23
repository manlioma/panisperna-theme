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

<main class="site-main content-area">
    <section class="section">
        <!-- Back link -->
        <a href="javascript:history.back()" style="display:inline-flex;align-items:center;gap:8px;margin-bottom:var(--space-3xl);font-size:var(--text-small);">
            &larr; Indietro
        </a>

        <!-- Product layout -->
        <div class="product-single" style="display:flex;gap:var(--space-3xl);align-items:flex-start;">
            <!-- Left: Info -->
            <div class="product-single__info" style="flex:1;">
                <h1 class="h1"><?php the_title(); ?></h1>

                <?php if ($is_bundle) : ?>
                    <!-- Bundle: description under title -->
                    <p style="margin-top:var(--space-md);font-size:var(--text-body);">
                        <?php echo wp_kses_post($product->get_short_description()); ?>
                    </p>
                <?php else : ?>
                    <!-- Libro: metadata fields -->
                    <div class="product-single__meta" style="margin-top:var(--space-xl);display:flex;flex-direction:column;gap:var(--space-md);">
                        <?php
                        $meta_fields = [
                            'Autore/autrice' => $product->get_attribute('autore'),
                            'Casa editrice'  => $product->get_attribute('casa-editrice'),
                            'Anno'           => $product->get_attribute('anno'),
                            'ISBN'           => $product->get_attribute('isbn'),
                        ];
                        foreach ($meta_fields as $label => $value) :
                            if (!$value) continue;
                        ?>
                            <div>
                                <span style="font-size:14px;color:var(--color-yellow);font-weight:600;display:block;"><?php echo esc_html($label); ?></span>
                                <span style="font-size:var(--text-small);font-weight:600;"><?php echo esc_html($value); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Price -->
                <div style="margin-top:var(--space-xl);">
                    <span style="font-size:var(--text-h3);font-weight:700;"><?php echo $product->get_price_html(); ?></span>
                </div>

                <!-- Add to cart -->
                <div style="margin-top:var(--space-xl);display:flex;gap:var(--space-md);align-items:center;">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            </div>

            <!-- Right: Image -->
            <div class="product-single__image" style="flex:0 0 45%;max-width:500px;">
                <?php the_post_thumbnail('large', ['style' => 'width:100%;height:auto;']); ?>
            </div>
        </div>

        <!-- "Chiedi in libreria" button -->
        <div style="text-align:right;margin-top:var(--space-xl);">
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contatti'))); ?>" class="btn btn--pill" style="gap:8px;">
                <span style="width:10px;height:10px;border-radius:50%;background:var(--color-yellow);display:inline-block;"></span>
                Chiedi in libreria
            </a>
        </div>

        <!-- Description -->
        <div style="margin-top:var(--space-5xl);">
            <h2 class="h1" style="margin-bottom:var(--space-xl);">
                <?php echo $is_bundle ? 'Descrizione del pacchetto' : 'Descrizione del libro'; ?>
            </h2>
            <div class="entry-content" style="max-width:700px;">
                <?php the_content(); ?>
            </div>
        </div>

        <?php if ($is_bundle) : ?>
            <!-- Bundle: show included books -->
            <?php
            $bundle_items = [];
            if (method_exists($product, 'get_items')) {
                $bundle_items = $product->get_items();
            }
            if (!empty($bundle_items)) : ?>
                <div style="margin-top:var(--space-5xl);">
                    <div class="book-carousel">
                        <?php foreach ($bundle_items as $item) :
                            $item_product = wc_get_product($item['id'] ?? $item);
                            if (!$item_product) continue;
                        ?>
                            <a href="<?php echo esc_url($item_product->get_permalink()); ?>" class="book-item">
                                <div class="book-item__cover">
                                    <?php echo $item_product->get_image('book-cover', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
                                </div>
                                <div>
                                    <h4 class="book-item__title"><?php echo esc_html($item_product->get_name()); ?></h4>
                                    <p class="book-item__author">
                                        <?php echo esc_html($item_product->get_attribute('autore')); ?>
                                    </p>
                                    <p class="card__price"><?php echo $item_product->get_price_html(); ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </section>
</main>

<?php
endwhile;
get_footer();
