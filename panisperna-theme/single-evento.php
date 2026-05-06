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
$libro   = panisperna_field('evento_libro');
$gallery = panisperna_field('evento_gallery');

$autore      = $libro ? get_post_meta((int) $libro, 'libro_autore', true) : '';
$editore     = $libro ? get_post_meta((int) $libro, 'libro_editore', true) : '';
$descrizione = get_the_excerpt();
?>

<!-- Hero -->
<section class="hero hero--page hero--dark consiglio-hero">
    <a href="<?php echo esc_url(get_post_type_archive_link('evento')); ?>" class="back-link">
        &larr; Indietro
    </a>
    <div class="hero--page__content">
        <div class="hero--page__text">
            <div class="consiglio-meta consiglio-meta--evento">
                <?php if ($data) : ?>
                    <div class="consiglio-meta__group">
                        <span class="consiglio-meta__label">Data e ora</span>
                        <p class="consiglio-meta__value"><?php echo esc_html(panisperna_format_event_date($data, true)); ?><?php if ($ora) echo ', ore ' . esc_html($ora); ?></p>
                    </div>
                <?php endif; ?>
                <div class="consiglio-meta__group">
                    <span class="consiglio-meta__label">Titolo</span>
                    <h1 class="consiglio-meta__value consiglio-meta__value--title"><?php the_title(); ?></h1>
                </div>
                <?php if ($autore) : ?>
                    <div class="consiglio-meta__group">
                        <span class="consiglio-meta__label">Autore/Autrice</span>
                        <p class="consiglio-meta__value"><?php echo esc_html($autore); ?></p>
                    </div>
                <?php endif; ?>
                <?php if ($editore) : ?>
                    <div class="consiglio-meta__group">
                        <span class="consiglio-meta__label">Casa editrice</span>
                        <p class="consiglio-meta__value"><?php echo esc_html($editore); ?></p>
                    </div>
                <?php endif; ?>
                <?php if ($descrizione) : ?>
                    <div class="consiglio-meta__group">
                        <span class="consiglio-meta__label">Descrizione</span>
                        <p class="consiglio-meta__value"><?php echo esc_html($descrizione); ?></p>
                    </div>
                <?php endif; ?>
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
