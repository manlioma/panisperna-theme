<?php
/**
 * Template Name: Libreria
 *
 * @package Panisperna
 */

get_header();

$hero_label = '';
$hero_title = get_the_title();
$hero_image = '';
include locate_template('template-parts/hero-page.php');
?>

<main class="site-main">

    <?php if (have_rows('sezioni')) : ?>
        <?php while (have_rows('sezioni')) : the_row(); ?>

            <?php if (get_row_layout() === 'citazione') : ?>
                <section class="libreria-section libreria-section--quote">
                    <div class="libreria-section__inner">
                        <blockquote class="libreria-quote">
                            <p><?php echo esc_html(get_sub_field('testo')); ?></p>
                            <?php $autore = get_sub_field('autore'); ?>
                            <?php if ($autore) : ?>
                                <cite><?php echo esc_html($autore); ?></cite>
                            <?php endif; ?>
                        </blockquote>
                    </div>
                </section>

            <?php elseif (get_row_layout() === 'carousel') : ?>
                <?php $immagini = get_sub_field('immagini'); ?>
                <?php if ($immagini) : ?>
                    <section class="libreria-section libreria-section--carousel">
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
                <section class="libreria-section libreria-section--text">
                    <div class="libreria-section__inner">
                        <?php $titolo = get_sub_field('titolo'); ?>
                        <?php if ($titolo) : ?>
                            <h2 class="h1"><?php echo esc_html($titolo); ?></h2>
                        <?php endif; ?>
                        <div class="entry-content">
                            <?php echo wp_kses_post(get_sub_field('contenuto')); ?>
                        </div>
                    </div>
                </section>

            <?php endif; ?>

        <?php endwhile; ?>
    <?php endif; ?>

</main>

<?php get_footer();
