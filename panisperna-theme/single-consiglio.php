<?php
/**
 * Single Consiglio (Parola di)
 * Figma node 345-2766
 *
 * @package Panisperna
 */

get_header();
the_post();

$nome         = panisperna_field('consiglio_nome', false, get_the_title());
$autore       = panisperna_field('consiglio_autore');
$video_embed  = panisperna_field('consiglio_video');
$video_orient = panisperna_field('consiglio_video_orientamento');
$libri        = panisperna_field('consiglio_libri');

$hero_image = has_post_thumbnail() ? get_post_thumbnail_id() : '';
$video_class = 'hero--page__image consiglio-video';
if ($video_orient === 'verticale') {
    $video_class .= ' consiglio-video--vertical';
}
?>

<!-- Hero -->
<section class="hero hero--page hero--dark consiglio-hero">
    <a href="<?php echo esc_url(get_post_type_archive_link('consiglio')); ?>" class="back-link">
        &larr; Indietro
    </a>
    <div class="hero--page__content">
        <div class="hero--page__text">
            <span class="h5">Consiglio del libro</span>
            <h1 class="h1" style="margin-top:var(--space-sm);"><?php the_title(); ?></h1>
            <div class="consiglio-meta">
                <?php if ($autore) : ?>
                <div>
                    <span class="consiglio-meta__label">Autore/autrice</span><br>
                    <span class="consiglio-meta__value"><?php echo esc_html($autore); ?></span>
                </div>
                <?php endif; ?>
                <div>
                    <span class="consiglio-meta__label">Presenta</span><br>
                    <span class="consiglio-meta__value"><?php echo esc_html($nome); ?></span>
                </div>
            </div>
        </div>
        <?php if ($video_embed) : ?>
            <div class="<?php echo esc_attr($video_class); ?>">
                <div class="consiglio-video__placeholder" data-embed="<?php echo esc_attr($video_embed); ?>">
                    <?php if ($hero_image) : ?>
                        <?php echo wp_get_attachment_image($hero_image, 'large'); ?>
                    <?php endif; ?>
                    <button class="consiglio-video__play" aria-label="Riproduci video">
                        <svg width="64" height="64" viewBox="0 0 64 64" fill="none"><circle cx="32" cy="32" r="32" fill="rgba(0,0,0,.55)"/><polygon points="26,20 26,44 46,32" fill="#fff"/></svg>
                    </button>
                </div>
            </div>
        <?php elseif ($hero_image) : ?>
            <div class="hero--page__image">
                <?php echo wp_get_attachment_image($hero_image, 'large'); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<main class="site-main content-area">
    <!-- Contenuto libero -->
    <section class="consiglio-content">
        <?php if (trim(get_the_content())) : ?>
        <div class="entry-content">
            <?php the_content(); ?>
        </div>
        <?php endif; ?>
    </section>

    <!-- Libro consigliato -->
    <?php if ($libri) : ?>
        <section class="section" style="padding-top:0;">
            <h2 class="section-title__heading" style="margin-bottom:var(--space-xl);">Libro consigliato</h2>
            <div class="cards-grid cards-grid--collezione">
                <?php foreach ($libri as $libro) :
                    $product = wc_get_product($libro->ID);
                    if (!$product) continue;
                    include locate_template('template-parts/card-collezione.php');
                endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php get_footer();
