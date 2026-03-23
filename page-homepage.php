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
        // Query pacchetti (categoria Pacchetti o WPC Bundles)
        $bundles = new WP_Query([
            'post_type'      => 'product',
            'posts_per_page' => 5,
            'tax_query'      => [
                'relation' => 'OR',
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => 'pacchetti',
                ],
                [
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'woosb',
                ],
            ],
        ]);

        if ($bundles->have_posts()) : ?>
            <!-- Featured bundle -->
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

            <!-- Bundle cards grid -->
            <div class="cards-grid cards-grid--pacchetti">
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
        $libri = new WP_Query([
            'post_type'      => 'product',
            'posts_per_page' => 4,
            'tax_query'      => [
                [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                ],
            ],
        ]);

        if ($libri->have_posts()) : ?>
            <div class="book-carousel">
                <?php while ($libri->have_posts()) : $libri->the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="book-item">
                        <div class="book-item__cover">
                            <?php the_post_thumbnail('book-cover'); ?>
                        </div>
                        <div>
                            <h4 class="book-item__title"><?php the_title(); ?></h4>
                            <?php
                            $product = wc_get_product(get_the_ID());
                            $author = $product->get_attribute('autore');
                            $anno = $product->get_attribute('anno');
                            ?>
                            <p class="book-item__author">
                                <?php echo esc_html($author); ?><?php echo $anno ? ', ' . esc_html($anno) : ''; ?>
                            </p>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>
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
                <?php while ($eventi->have_posts()) : $eventi->the_post(); ?>
                    <div class="event-card-wrap">
                        <a href="<?php the_permalink(); ?>" class="event-card">
                            <div class="event-card__image">
                                <?php the_post_thumbnail('event-card', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
                                <?php $tag = panisperna_field('evento_tipo_tag'); ?>
                                <?php if ($tag) : ?>
                                    <span class="btn--tag"><?php echo esc_html($tag); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="event-card__body">
                                <div>
                                    <h4 class="event-card__title"><?php the_title(); ?></h4>
                                    <p class="event-card__date"><?php echo esc_html(panisperna_field('evento_data')); ?></p>
                                    <p class="event-card__time">Ore <?php echo esc_html(panisperna_field('evento_ora')); ?></p>
                                </div>
                                <div class="event-card__location">
                                    <?php echo panisperna_icon('map-pin'); ?>
                                    <span><?php echo esc_html(panisperna_field('evento_luogo', false, 'Libreria Panisperna 220')); ?></span>
                                </div>
                            </div>
                        </a>
                        <div class="card__dot"></div>
                    </div>
                <?php endwhile; ?>
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
                    $foto = panisperna_field('consiglio_foto');
                    $nome = panisperna_field('consiglio_nome', false, get_the_title());
                ?>
                    <a href="<?php the_permalink(); ?>" class="card card--consiglio">
                        <div class="card__image" style="height: 495px;">
                            <?php if ($foto) : ?>
                                <img src="<?php echo esc_url($foto['sizes']['consiglio-portrait'] ?? $foto['url']); ?>"
                                     alt="<?php echo esc_attr($nome); ?>"
                                     style="width:100%;height:100%;object-fit:cover;"
                                     loading="lazy">
                            <?php else : ?>
                                <?php the_post_thumbnail('consiglio-portrait', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
                            <?php endif; ?>
                            <div class="card--consiglio__hover">
                                <span class="card--consiglio__cta">Approfondimento &rarr;</span>
                            </div>
                        </div>
                        <div class="card__body" style="text-align:center;border-bottom:var(--border-accent);width:100%;">
                            <h4 class="card__title"><?php echo esc_html($nome); ?></h4>
                        </div>
                        <div class="card__dot"></div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>
    </section>

</main>

<?php get_footer();
