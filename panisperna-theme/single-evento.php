<?php
/**
 * Single Evento (Incontri)
 *
 * @package Panisperna
 */

get_header();
the_post();

$data    = panisperna_field('evento_data');
$ora     = panisperna_field('evento_ora');
$luogo   = panisperna_field('evento_luogo', false, 'Libreria Panisperna 220');
$libro   = panisperna_field('evento_libro');
$gallery = panisperna_field('evento_gallery');
$tipos   = get_the_terms(get_the_ID(), 'tipo_evento');
$tag     = ($tipos && !is_wp_error($tipos)) ? $tipos[0]->name : '';
?>

<!-- Hero -->
<section class="hero hero--page hero--dark consiglio-hero">
    <a href="<?php echo esc_url(get_post_type_archive_link('evento')); ?>" class="back-link">
        &larr; Indietro
    </a>
    <div class="hero--page__content">
        <div class="hero--page__text">
            <?php if ($tag) : ?>
                <span class="h5"><?php echo esc_html($tag); ?></span>
            <?php endif; ?>
            <h1 class="h1" style="margin-top:var(--space-sm);"><?php the_title(); ?></h1>
            <div class="consiglio-meta">
                <?php if ($data) : ?>
                <div>
                    <span class="consiglio-meta__label">Data e ora</span><br>
                    <span class="consiglio-meta__value"><?php echo esc_html(panisperna_format_event_date($data)); ?><?php if ($ora) echo ', ore ' . esc_html($ora); ?></span>
                </div>
                <?php endif; ?>
                <div>
                    <span class="consiglio-meta__label">Luogo</span><br>
                    <span class="consiglio-meta__value"><?php echo esc_html($luogo); ?></span>
                </div>
            </div>
        </div>
        <?php if (has_post_thumbnail()) : ?>
            <div class="hero--page__image">
                <?php the_post_thumbnail('large'); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<main class="site-main content-area">
    <!-- Contenuto libero -->
    <section class="consiglio-content">
        <div class="entry-content" style="max-width:751px;">
            <?php the_content(); ?>
        </div>
    </section>

    <!-- Libro collegato -->
    <?php if ($libro) :
        $product = wc_get_product($libro);
        if ($product) : ?>
            <section class="section" style="padding-top:0;">
                <h2 class="section-title__heading" style="margin-bottom:var(--space-xl);">Libro dell'incontro</h2>
                <div class="cards-grid cards-grid--collezione">
                    <?php include locate_template('template-parts/card-collezione.php'); ?>
                </div>
            </section>
        <?php endif;
    endif; ?>

    <!-- Gallery foto (solo se caricata via ACF) -->
    <?php if ($gallery) : ?>
        <section style="margin-top:var(--space-3xl);">
            <div class="hero__images" style="display:flex;gap:4px;">
                <?php foreach ($gallery as $img) : ?>
                    <div style="flex:1;height:300px;overflow:hidden;">
                        <img src="<?php echo esc_url($img['sizes']['large'] ?? $img['url']); ?>"
                             alt="<?php echo esc_attr($img['alt']); ?>"
                             style="width:100%;height:100%;object-fit:cover;" loading="lazy">
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php get_footer();
