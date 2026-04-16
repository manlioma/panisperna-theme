<?php
/**
 * Component: Consiglio Card (persona)
 *
 * Used in: archive-consiglio, homepage, AJAX handler
 * Expects: inside a WP loop (the_post() called)
 *
 * @package Panisperna
 */

$nome   = panisperna_field('consiglio_nome', false, get_the_title());
$autore = panisperna_field('consiglio_autore');
$foto   = panisperna_field('consiglio_foto');
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
        <span class="card__play-icon" aria-hidden="true">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="24" cy="24" r="24" fill="rgba(255,255,255,0.85)"/>
                <polygon points="19,14 19,34 35,24" fill="#454545"/>
            </svg>
        </span>
    </div>
    <div class="card__body" style="border-bottom:var(--border-accent);width:100%;">
        <div class="card--consiglio__default">
            <h4 class="card__title" style="height:auto;text-align:center;"><?php echo esc_html($nome); ?></h4>
        </div>
        <div class="card--consiglio__expanded">
            <span class="consiglio-name"><?php echo esc_html($nome); ?></span>
            <span class="consiglio-label">Consiglia</span>
            <span class="consiglio-book"><?php echo esc_html(get_the_title()); ?></span>
            <?php if ($autore) : ?>
                <span class="consiglio-author">Di <?php echo esc_html($autore); ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="card__dot"></div>
</a>
