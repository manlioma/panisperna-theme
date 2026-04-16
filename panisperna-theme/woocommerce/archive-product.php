<?php
/**
 * Shop / Archive Product - Proposte page
 *
 * @package Panisperna
 */

defined('ABSPATH') || exit;

get_header();

$shop_page_id = wc_get_page_id('shop');

$hero_title       = get_the_title($shop_page_id) ?: 'Proposte';
$hero_description = panisperna_field('hero_subtitle', $shop_page_id);
$hero_image       = get_post_thumbnail_id($shop_page_id);
include locate_template('template-parts/hero-page.php');
?>

<main class="site-main content-area">

    <!-- I NOSTRI PACCHETTI -->
    <section class="section">
        <div class="section-title">
            <h2 class="section-title__heading"><?php echo esc_html(get_field('shop_pacchetti_title', $shop_page_id) ?: 'I nostri pacchetti'); ?></h2>
            <?php $desc = get_field('shop_pacchetti_description', $shop_page_id); if ($desc) : ?>
                <p class="section-title__description"><?php echo esc_html($desc); ?></p>
            <?php endif; ?>
        </div>

        <?php
        // PH10-PACFEAT: 2 featured pacchetti from option page (new plural field)
        $featured_ids = get_field('featured_pacchetti', 'option');
        if (!$featured_ids || !is_array($featured_ids)) {
            $single_feat = get_field('featured_pacchetto', 'option');
            $featured_ids = $single_feat ? [$single_feat] : [];
        }

        if (empty($featured_ids)) {
            $first_bundles = new WP_Query([
                'post_type'      => 'product',
                'posts_per_page' => 2,
                'tax_query'      => [
                    ['taxonomy' => 'product_type', 'field' => 'slug', 'terms' => 'woosb'],
                ],
            ]);
            $featured_ids = [];
            while ($first_bundles->have_posts()) { $first_bundles->the_post(); $featured_ids[] = get_the_ID(); }
            wp_reset_postdata();
        }

        if (!empty($featured_ids)) : ?>
            <div class="cards-grid--pacchetti-featured">
                <?php foreach ($featured_ids as $fid) :
                    $product = wc_get_product($fid);
                    if (!$product) continue;
                    global $post;
                    $post = get_post($fid);
                    setup_postdata($post);
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
                <?php endforeach;
                wp_reset_postdata(); ?>
            </div>
        <?php endif;

        $remaining_args = [
            'post_type'      => 'product',
            'posts_per_page' => 4,
            'tax_query'      => [
                ['taxonomy' => 'product_type', 'field' => 'slug', 'terms' => 'woosb'],
            ],
        ];
        if (!empty($featured_ids)) {
            $remaining_args['post__not_in'] = $featured_ids;
        }
        $remaining = new WP_Query($remaining_args);

        if ($remaining->have_posts()) : ?>
            <div class="cards-grid cards-grid--pacchetti" id="pacchetti-grid" style="margin-top:var(--space-lg);" data-current-page="1" data-max-pages="<?php echo esc_attr($remaining->max_num_pages); ?>">
                <?php while ($remaining->have_posts()) : $remaining->the_post();
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
                <?php endwhile; ?>
            </div>

            <?php if ($remaining->max_num_pages > 1) : ?>
            <div class="load-more-wrap">
                <button class="btn btn--load-more" id="pacchetti-load-more">Carica altri contenuti</button>
            </div>
            <?php endif; ?>
        <?php endif;
        wp_reset_postdata(); ?>
    </section>

    <!-- LETTI DA NOI -->
    <section class="section section--beige">
        <div class="section-title">
            <h2 class="section-title__heading"><?php echo esc_html(get_field('shop_letti_title', $shop_page_id) ?: 'Letti da noi'); ?></h2>
            <?php $desc = get_field('shop_letti_description', $shop_page_id); if ($desc) : ?>
                <p class="section-title__description"><?php echo esc_html($desc); ?></p>
            <?php endif; ?>
        </div>

        <?php
        $letti_ids = get_field('shop_letti_products', 'option');
        if (!$letti_ids) {
            // Fallback: first 4 simple products
            $fallback = new WP_Query([
                'post_type'      => 'product',
                'posts_per_page' => 4,
                'tax_query'      => [
                    ['taxonomy' => 'product_type', 'field' => 'slug', 'terms' => 'simple'],
                ],
            ]);
            $letti_ids = [];
            while ($fallback->have_posts()) { $fallback->the_post(); $letti_ids[] = get_the_ID(); }
            wp_reset_postdata();
        }

        if ($letti_ids) : ?>
            <div class="book-grid">
                <?php foreach ($letti_ids as $pid) :
                    $product = wc_get_product($pid);
                    if (!$product) continue;
                ?>
                    <a href="<?php echo get_permalink($pid); ?>" class="book-item">
                        <div class="book-item__cover">
                            <?php echo get_the_post_thumbnail($pid, 'large'); ?>
                        </div>
                        <div class="book-item__info">
                            <h4 class="book-item__title"><?php echo get_the_title($pid); ?></h4>
                            <?php $autore = $product->get_attribute('autore'); ?>
                            <?php if ($autore) : ?>
                                <p class="book-item__author"><?php echo esc_html($autore); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- LA NOSTRA COLLEZIONE -->
    <section class="section">
        <div class="section-title">
            <h2 class="section-title__heading"><?php echo esc_html(get_field('shop_collezione_title', $shop_page_id) ?: 'La nostra collezione'); ?></h2>
            <?php $desc = get_field('shop_collezione_description', $shop_page_id); if ($desc) : ?>
                <p class="section-title__description"><?php echo esc_html($desc); ?></p>
            <?php endif; ?>
        </div>

        <!-- Filtri (dynamic WooCommerce categories per D-10) -->
        <div class="collezione-filters" style="display:flex;gap:var(--space-sm);justify-content:center;margin:var(--space-xl) 0;flex-wrap:wrap;">
            <button class="btn btn--outline btn--chip is-active" data-category="tutti">Tutti</button>
            <?php
            $cats = get_terms([
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'exclude'    => [
                    get_term_by('slug', 'uncategorized', 'product_cat') ? get_term_by('slug', 'uncategorized', 'product_cat')->term_id : 0,
                ],
            ]);
            if (!is_wp_error($cats)) :
                foreach ($cats as $cat) : ?>
                    <button class="btn btn--outline btn--chip" data-category="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></button>
                <?php endforeach;
            endif;
            ?>
        </div>

        <?php
        // Initial load: 8 simple products (exclude bundles by product_type)
        $all_books = new WP_Query([
            'post_type'      => 'product',
            'posts_per_page' => 8,
            'tax_query'      => [
                [
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'woosb',
                    'operator' => 'NOT IN',
                ],
            ],
        ]);

        if ($all_books->have_posts()) : ?>
            <div class="cards-grid cards-grid--collezione" data-max-pages="<?php echo esc_attr($all_books->max_num_pages); ?>" data-current-page="1" data-category="tutti">
                <?php while ($all_books->have_posts()) : $all_books->the_post();
                    $product = wc_get_product(get_the_ID());
                    include locate_template('template-parts/card-collezione.php');
                endwhile; ?>
            </div>

            <?php if ($all_books->max_num_pages > 1) : ?>
            <div class="load-more-wrap">
                <button class="btn btn--load-more" id="collezione-load-more">Carica altri contenuti</button>
            </div>
            <?php endif; ?>
        <?php endif;
        wp_reset_postdata(); ?>
    </section>

</main>

<?php get_footer();
