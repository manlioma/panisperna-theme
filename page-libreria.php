<?php
/**
 * Template Name: Libreria
 *
 * @package Panisperna
 */

get_header();

$hero_images = panisperna_field('libreria_hero_images');
$hero_image  = $hero_images ? ($hero_images[0]['id'] ?? '') : '';

$hero_label       = '';
$hero_title       = panisperna_field('libreria_hero_heading', false, 'La libreria');
$hero_description = 'Frase di introduzione alla pagina inserita, testo non troppo lungo, massimo quattro righe.';
include locate_template('template-parts/hero-page.php');
?>

<main class="site-main">

    <!-- Citazione 1 -->
    <section class="section content-area">
        <div class="libreria-quote" style="max-width:850px;margin:0 auto;background:white;padding:32px 0;text-align:center;">
            <h2 class="h1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</h2>
        </div>
    </section>

    <!-- Carousel foto 1 -->
    <?php if ($hero_images) : ?>
        <section class="libreria-carousel-section">
            <div class="swiper libreria-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($hero_images as $img) : ?>
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

    <!-- Titolo + Testo -->
    <section class="section content-area">
        <div style="max-width:850px;margin:0 auto;background:white;padding:32px 0;text-align:center;">
            <h2 class="h1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et.</h2>
        </div>
        <div style="max-width:751px;margin:0 auto;background:white;padding:24px 0;">
            <div class="entry-content" style="font-family:var(--font-heading);font-size:24px;line-height:26px;letter-spacing:-0.456px;">
                <?php
                $testo = panisperna_field('libreria_testo');
                if ($testo) {
                    echo wp_kses_post($testo);
                } else {
                    echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                    echo '<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Carousel foto 2 -->
    <?php
    $galleria = panisperna_field('libreria_galleria');
    $gallery_images = $galleria ?: $hero_images;
    if ($gallery_images) : ?>
        <section class="libreria-carousel-section">
            <div class="swiper libreria-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($gallery_images as $img) : ?>
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

    <!-- Chi siamo -->
    <section class="section content-area">
        <div class="section-title">
            <h2 class="section-title__heading">Chi siamo</h2>
            <p class="section-title__description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>
        <div class="cards-grid" style="gap:20px;margin-top:var(--space-3xl);">
            <?php
            $team = ['Masud', 'Ada', 'Vania', 'Eleonora'];
            foreach ($team as $name) : ?>
                <div class="card card--team" style="align-items:center;">
                    <div class="card--team__image">
                        <div style="width:100%;height:100%;background:var(--color-beige);"></div>
                    </div>
                    <div class="card__body" style="text-align:center;width:100%;">
                        <h4 class="card__title" style="height:auto;"><?php echo esc_html($name); ?></h4>
                    </div>
                    <div class="card__dot"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<?php get_footer();
