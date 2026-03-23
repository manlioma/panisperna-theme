<?php
/**
 * Single Consiglio (Parola di)
 *
 * @package Panisperna
 */

get_header();
the_post();

$nome  = panisperna_field('consiglio_nome', false, get_the_title());
$foto  = panisperna_field('consiglio_foto');
$libri = panisperna_field('consiglio_libri');

$hero_image = $foto ? $foto['id'] : (has_post_thumbnail() ? get_post_thumbnail_id() : '');
?>

<!-- Hero -->
<section class="hero hero--page">
    <div class="hero--page__content">
        <div class="hero--page__text">
            <a href="<?php echo esc_url(get_post_type_archive_link('consiglio')); ?>" style="display:inline-flex;align-items:center;gap:8px;font-size:var(--text-small);margin-bottom:var(--space-lg);">
                &larr; Indietro
            </a>
            <span class="h5">Consiglio del libro</span>
            <h1 class="h1" style="margin-top:var(--space-sm);">Titolo del libro</h1>
            <div style="margin-top:var(--space-lg);display:flex;flex-direction:column;gap:var(--space-xs);">
                <div><span style="font-size:14px;color:var(--color-shadow-yellow);font-weight:600;">Autore/autrice</span><br>
                <span style="font-weight:600;"><?php echo esc_html($nome); ?></span></div>
                <div><span style="font-size:14px;color:var(--color-shadow-yellow);font-weight:600;">Presentano</span><br>
                <span style="font-weight:600;"><?php echo esc_html($nome); ?></span></div>
            </div>
        </div>
        <?php if ($hero_image) : ?>
            <div class="hero--page__image">
                <?php echo wp_get_attachment_image($hero_image, 'large'); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<main class="site-main content-area">
    <!-- Frase in risalto -->
    <section class="section">
        <h2 class="h1" style="font-style:italic;max-width:600px;">Frase in risalto a scelta del libraio per spiegare</h2>
        <div class="entry-content" style="margin-top:var(--space-xl);max-width:700px;">
            <?php the_content(); ?>
        </div>
    </section>

    <!-- Libri consigliati -->
    <?php if ($libri) : ?>
        <section class="section" style="padding-top:0;">
            <div class="book-carousel" style="padding:0;">
                <?php foreach ($libri as $libro) :
                    $product = wc_get_product($libro->ID);
                    if (!$product) continue;
                ?>
                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="book-item">
                        <div class="book-item__cover">
                            <?php echo $product->get_image('book-cover'); ?>
                        </div>
                        <div>
                            <h4 class="book-item__title"><?php echo esc_html($product->get_name()); ?></h4>
                            <p class="book-item__author"><?php echo esc_html($product->get_attribute('autore')); ?></p>
                            <p class="card__price"><?php echo $product->get_price_html(); ?></p>
                            <div class="card__dot" style="margin:8px 0 0;"></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Gallery foto libreria -->
    <section style="margin-top:var(--space-3xl);">
        <div style="display:flex;gap:4px;">
            <?php
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
