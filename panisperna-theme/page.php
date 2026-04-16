<?php
/**
 * Generic page template
 *
 * @package Panisperna
 */

get_header();

while (have_posts()) : the_post();

$hero_label       = '';
$hero_title       = get_the_title();
$hero_description = panisperna_field('hero_subtitle');
$hero_image       = get_post_thumbnail_id();
include locate_template('template-parts/hero-page.php');
?>

<main class="site-main content-area">
    <?php if (get_the_content()) : ?>
    <section class="section">
        <div class="entry-content">
            <?php the_content(); ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (have_rows('sezioni')) : ?>
        <?php while (have_rows('sezioni')) : the_row(); ?>

            <?php if (get_row_layout() === 'citazione') : ?>
                <section class="section" style="padding-top:0;">
                    <blockquote class="libreria-quote">
                        <p><?php echo esc_html(get_sub_field('testo')); ?></p>
                        <?php $autore = get_sub_field('autore'); ?>
                        <?php if ($autore) : ?>
                            <cite><?php echo esc_html($autore); ?></cite>
                        <?php endif; ?>
                    </blockquote>
                </section>

            <?php elseif (get_row_layout() === 'carousel') : ?>
                <?php $immagini = get_sub_field('immagini'); ?>
                <?php if ($immagini) : ?>
                    <section class="section" style="padding-top:0;">
                        <div class="swiper libreria-swiper">
                            <div class="swiper-wrapper">
                                <?php foreach ($immagini as $img) : ?>
                                    <div class="swiper-slide">
                                        <div class="libreria-slide">
                                            <img src="<?php echo esc_url($img['sizes']['large'] ?? $img['url']); ?>"
                                                 alt="<?php echo esc_attr($img['alt']); ?>"
                                                 loading="lazy">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

            <?php elseif (get_row_layout() === 'testo') : ?>
                <section class="section" style="padding-top:0;">
                    <?php $titolo = get_sub_field('titolo'); ?>
                    <?php if ($titolo) : ?>
                        <h2 class="h2" style="margin-bottom: var(--space-xl);"><?php echo esc_html($titolo); ?></h2>
                    <?php endif; ?>
                    <div class="entry-content">
                        <?php echo wp_kses_post(get_sub_field('contenuto')); ?>
                    </div>
                </section>

            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php
endwhile;
get_footer();
