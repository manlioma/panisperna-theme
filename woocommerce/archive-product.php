<?php
/**
 * Shop / Archive Product - Proposte page
 *
 * @package Panisperna
 */

defined('ABSPATH') || exit;

get_header();

$hero_label       = 'Proposte';
$hero_title       = 'Proposte';
$hero_description = 'Frase di introduzione alla pagina inserita, testo non troppo lungo, massimo quattro righe.';
$hero_image       = '';
include locate_template('template-parts/hero-page.php');
?>

<main class="site-main content-area">

    <!-- I NOSTRI PACCHETTI -->
    <section class="section">
        <div class="section-title">
            <h2 class="section-title__heading">I nostri pacchetti</h2>
            <p class="section-title__description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>

        <?php
        $bundles = new WP_Query([
            'post_type'      => 'product',
            'posts_per_page' => 5,
            'tax_query'      => [
                'relation' => 'OR',
                ['taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'pacchetti'],
                ['taxonomy' => 'product_type', 'field' => 'slug', 'terms' => 'woosb'],
            ],
        ]);

        if ($bundles->have_posts()) : ?>
            <?php
            $bundles->the_post();
            $product = wc_get_product(get_the_ID());
            ?>
            <div class="card card--featured">
                <div class="card--featured__image">
                    <?php the_post_thumbnail('large', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
                </div>
                <div class="card--featured__overlay">
                    <h3 class="h3"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <a href="<?php echo esc_url('?add-to-cart=' . get_the_ID()); ?>" class="btn--icon ajax-add-to-cart" data-product-id="<?php echo get_the_ID(); ?>">+</a>
                </div>
            </div>

            <div class="cards-grid cards-grid--pacchetti" style="margin-top:var(--space-lg);">
                <?php while ($bundles->have_posts()) : $bundles->the_post();
                    $product = wc_get_product(get_the_ID());
                ?>
                    <div class="card card--pacchetto">
                        <a href="<?php the_permalink(); ?>" class="card--pacchetto__image">
                            <?php the_post_thumbnail('card-thumb', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
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
                <?php endwhile; ?>
            </div>

            <div style="text-align:center;margin-top:var(--space-3xl);">
                <button class="contact-form__submit" style="width:100%;max-width:600px;">Carica altri contenuti</button>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>
    </section>

    <!-- LETTI DA NOI -->
    <section class="section section--beige">
        <div class="section-title">
            <h2 class="section-title__heading">Letti da noi</h2>
            <p class="section-title__description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>

        <?php
        $featured = new WP_Query([
            'post_type'      => 'product',
            'posts_per_page' => 4,
            'tax_query'      => [
                ['taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => 'featured'],
            ],
        ]);

        if ($featured->have_posts()) : ?>
            <div class="book-carousel">
                <?php while ($featured->have_posts()) : $featured->the_post();
                    $product = wc_get_product(get_the_ID());
                ?>
                    <a href="<?php the_permalink(); ?>" class="book-item">
                        <div class="book-item__cover">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                        <div>
                            <h4 class="book-item__title"><?php the_title(); ?></h4>
                            <p class="book-item__author">
                                <?php echo esc_html($product->get_attribute('autore')); ?><?php $anno = $product->get_attribute('anno'); echo $anno ? ', ' . esc_html($anno) : ''; ?>
                            </p>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>
    </section>

    <!-- LA NOSTRA COLLEZIONE -->
    <section class="section">
        <div class="section-title">
            <h2 class="section-title__heading">La nostra collezione</h2>
            <p class="section-title__description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>

        <!-- Filtri -->
        <div style="display:flex;gap:var(--space-sm);justify-content:center;margin:var(--space-xl) 0;flex-wrap:wrap;">
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn--outline" style="font-size:14px;padding:8px 20px;">Tutti</a>
            <a href="#" class="btn btn--outline" style="font-size:14px;padding:8px 20px;">Librnet</a>
            <a href="#" class="btn btn--outline" style="font-size:14px;padding:8px 20px;">English book</a>
            <a href="#" class="btn btn--outline" style="font-size:14px;padding:8px 20px;">Altro</a>
        </div>

        <?php
        $all_books = new WP_Query([
            'post_type'      => 'product',
            'posts_per_page' => 4,
            'tax_query'      => [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => 'pacchetti',
                    'operator' => 'NOT IN',
                ],
            ],
        ]);

        if ($all_books->have_posts()) : ?>
            <div class="book-carousel" style="flex-wrap:wrap;gap:var(--space-xl) 31px;padding:0;align-items:flex-start;">
                <?php while ($all_books->have_posts()) : $all_books->the_post();
                    $product = wc_get_product(get_the_ID());
                ?>
                    <a href="<?php the_permalink(); ?>" class="book-item" style="flex:0 0 calc(25% - 24px);">
                        <div class="book-item__cover">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                        <div>
                            <h4 class="book-item__title"><?php the_title(); ?></h4>
                            <p class="book-item__author"><?php echo esc_html($product->get_attribute('autore')); ?></p>
                            <p class="card__price"><?php echo $product->get_price_html(); ?></p>
                            <div class="card__dot" style="margin:8px 0 0;"></div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>

            <div style="text-align:center;margin-top:var(--space-3xl);">
                <button class="contact-form__submit" style="width:100%;max-width:600px;">Carica altri contenuti</button>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>
    </section>

</main>

<?php get_footer();
