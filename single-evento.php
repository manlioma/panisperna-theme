<?php
/**
 * Single Evento
 *
 * @package Panisperna
 */

get_header();
the_post();

$data    = panisperna_field('evento_data');
$ora     = panisperna_field('evento_ora');
$luogo   = panisperna_field('evento_luogo', false, 'Libreria Panisperna');
$tag     = panisperna_field('evento_tipo_tag');
$libro   = panisperna_field('evento_libro');
?>

<!-- Hero -->
<section class="hero hero--page">
    <div class="hero--page__content">
        <div class="hero--page__text">
            <a href="<?php echo esc_url(get_post_type_archive_link('evento')); ?>" style="display:inline-flex;align-items:center;gap:8px;font-size:var(--text-small);margin-bottom:var(--space-lg);">
                &larr; Indietro
            </a>
            <h1 class="h1"><?php the_title(); ?></h1>
            <div style="margin-top:var(--space-lg);display:flex;flex-direction:column;gap:var(--space-xs);">
                <?php if ($tag) : ?>
                    <div><span style="font-size:14px;color:var(--color-shadow-yellow);font-weight:600;">Autore/autrice</span><br>
                    <span style="font-weight:600;">Nome Cognome</span></div>
                <?php endif; ?>
                <?php if ($tag) : ?>
                    <div><span style="font-size:14px;color:var(--color-shadow-yellow);font-weight:600;">Presentano</span><br>
                    <span style="font-weight:600;">Nome Cognome, Nome Cognome</span></div>
                <?php endif; ?>
                <div><span style="font-size:14px;color:var(--color-shadow-yellow);font-weight:600;">Data e ora</span><br>
                <span style="font-weight:600;"><?php echo esc_html($data); ?>, ore <?php echo esc_html($ora); ?></span></div>
                <div><span style="font-size:14px;color:var(--color-shadow-yellow);font-weight:600;">Luogo</span><br>
                <span style="font-weight:600;"><?php echo esc_html($luogo); ?></span></div>
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
    <!-- Frase in risalto + testo -->
    <section class="section">
        <h2 class="h1" style="font-style:italic;max-width:600px;">Frase in risalto a scelta del libraio per spiegare</h2>
        <div class="entry-content" style="margin-top:var(--space-xl);max-width:700px;">
            <?php the_content(); ?>
        </div>
    </section>

    <!-- Libro collegato -->
    <?php if ($libro) :
        $product = wc_get_product($libro);
        if ($product) : ?>
            <section class="section" style="padding-top:0;">
                <div class="book-carousel" style="padding:0;">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="book-item">
                        <div class="book-item__cover">
                            <?php echo $product->get_image('large'); ?>
                        </div>
                        <div>
                            <h4 class="book-item__title"><?php echo esc_html($product->get_name()); ?></h4>
                            <p class="book-item__author"><?php echo esc_html($product->get_attribute('autore')); ?></p>
                            <p class="card__price"><?php echo $product->get_price_html(); ?></p>
                            <div class="card__dot" style="margin:8px 0 0;"></div>
                        </div>
                    </a>
                </div>
            </section>
        <?php endif;
    endif; ?>

    <!-- Gallery foto libreria (placeholder) -->
    <section style="margin-top:var(--space-3xl);">
        <div class="hero__images" style="display:flex;gap:4px;">
            <?php
            // Use hero images from homepage as fallback gallery
            $gallery_images = panisperna_field('hero_images', get_option('page_on_front'));
            if ($gallery_images) :
                foreach ($gallery_images as $img) : ?>
                    <div style="flex:1;height:300px;overflow:hidden;">
                        <img src="<?php echo esc_url($img['sizes']['large'] ?? $img['url']); ?>"
                             alt="" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
    </section>
</main>

<?php get_footer();
