<?php
/**
 * Template Name: Homepage
 *
 * @package Panisperna
 */

get_header(); ?>

<!-- HERO -->
<section class="hero">
    <div class="hero__content">
        <div class="hero__heading">
            <?php $hero_label = panisperna_field('hero_label', false, 'Libreria'); ?>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('libreria'))); ?>" class="btn btn--outline">
                <?php echo esc_html($hero_label); ?>
            </a>
            <h1 class="h1" style="margin-top: 16px;">
                <?php echo esc_html(panisperna_field('hero_heading', false, 'Frase sulla libreria, non molto lunga massimo due righe.')); ?>
            </h1>
        </div>
        <?php
        $hero_images = panisperna_field('hero_images');
        if ($hero_images) : ?>
            <div class="swiper hero-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($hero_images as $img) : ?>
                        <div class="swiper-slide hero__image">
                            <img src="<?php echo esc_url($img['sizes']['large'] ?? $img['url']); ?>"
                                 alt="<?php echo esc_attr($img['alt']); ?>"
                                 loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<main class="site-main content-area">

    <!-- PACCHETTI -->
    <section class="section" id="pacchetti">
        <div class="section-title">
            <span class="section-title__label">
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn--outline">Proposte</a>
            </span>
            <h2 class="section-title__heading">
                <?php echo esc_html(panisperna_field('pacchetti_title', false, 'I nostri pacchetti')); ?>
            </h2>
            <p class="section-title__description">
                <?php echo esc_html(panisperna_field('pacchetti_description')); ?>
            </p>
        </div>

        <?php
        // PH10-PACFEAT: 2 featured pacchetti from option page (new plural field)
        $featured_ids = get_field('featured_pacchetti', 'option');
        if (!$featured_ids || !is_array($featured_ids)) {
            // Fallback: use the single featured_pacchetto if set
            $single_feat = get_field('featured_pacchetto', 'option');
            $featured_ids = $single_feat ? [$single_feat] : [];
        }

        // If still no featured, grab first 2 bundles
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

        // Show featured row (2 card--featured with overlay, side by side)
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

        // Remaining 4 bundles (exclude featured)
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
            <div class="cards-grid cards-grid--pacchetti">
                <?php while ($remaining->have_posts()) : $remaining->the_post();
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
        <?php endif;
        wp_reset_postdata(); ?>
    </section>

    <!-- LETTI DA NOI -->
    <section class="section section--beige" id="letti-da-noi">
        <div class="section-title">
            <span class="section-title__label">
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn--outline">Proposte</a>
            </span>
            <h2 class="section-title__heading">
                <?php echo esc_html(panisperna_field('letti_title', false, 'Letti da noi')); ?>
            </h2>
            <p class="section-title__description">
                <?php echo esc_html(panisperna_field('letti_description')); ?>
            </p>
        </div>

        <?php
        $letti_ids = get_field('shop_letti_products', 'option');
        if (!$letti_ids) {
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

    <!-- PROSSIMI EVENTI -->
    <section class="section" id="eventi">
        <div class="section-title">
            <span class="section-title__label">
                <a href="<?php echo esc_url(get_post_type_archive_link('evento')); ?>" class="btn btn--outline">Incontri</a>
            </span>
            <h2 class="section-title__heading">
                <?php echo esc_html(panisperna_field('eventi_title', false, 'Prossimi eventi')); ?>
            </h2>
            <p class="section-title__description">
                <?php echo esc_html(panisperna_field('eventi_description')); ?>
            </p>
        </div>

        <?php
        $eventi = new WP_Query([
            'post_type'      => 'evento',
            'posts_per_page' => 4,
            'meta_key'       => 'evento_data',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_query'     => [
                [
                    'key'     => 'evento_data',
                    'value'   => date('Ymd'),
                    'compare' => '>=',
                    'type'    => 'DATE',
                ],
            ],
        ]);

        if ($eventi->have_posts()) : ?>
            <div class="cards-row cards-row--eventi">
                <?php while ($eventi->have_posts()) : $eventi->the_post();
                    get_template_part('template-parts/card-evento');
                endwhile; ?>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>
    </section>

    <!-- CONSIGLIATI DA VOI -->
    <section class="section" id="consigliati">
        <div class="section-title">
            <span class="section-title__label">
                <a href="<?php echo esc_url(get_post_type_archive_link('consiglio')); ?>" class="btn btn--outline">Parola di</a>
            </span>
            <h2 class="section-title__heading">
                <?php echo esc_html(panisperna_field('consigliati_title', false, 'Consigliati da voi')); ?>
            </h2>
            <p class="section-title__description">
                <?php echo esc_html(panisperna_field('consigliati_description')); ?>
            </p>
        </div>

        <?php
        $consigli = new WP_Query([
            'post_type'      => 'consiglio',
            'posts_per_page' => 4,
        ]);

        if ($consigli->have_posts()) : ?>
            <div class="cards-grid">
                <?php while ($consigli->have_posts()) : $consigli->the_post();
                    get_template_part('template-parts/card-consiglio');
                endwhile; ?>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>
    </section>

</main>

<?php get_footer();
