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

<main class="site-main content-area">

    <!-- Testo principale + immagine -->
    <section class="section">
        <div style="display:flex;gap:var(--space-3xl);">
            <div style="flex:1;">
                <h2 class="h1" style="margin-bottom:var(--space-xl);">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</h2>
                <div class="entry-content">
                    <?php
                    $testo = panisperna_field('libreria_testo');
                    if ($testo) {
                        echo wp_kses_post($testo);
                    } else {
                        echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>';
                        echo '<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Galleria -->
    <?php
    $galleria = panisperna_field('libreria_galleria');
    if ($galleria) : ?>
        <section style="padding:0 0 var(--space-5xl);">
            <div class="cards-grid" style="gap:4px;">
                <?php foreach ($galleria as $img) : ?>
                    <div style="flex:1;height:300px;overflow:hidden;">
                        <img src="<?php echo esc_url($img['sizes']['large'] ?? $img['url']); ?>"
                             alt="<?php echo esc_attr($img['alt']); ?>"
                             style="width:100%;height:100%;object-fit:cover;"
                             loading="lazy">
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Chi siamo -->
    <section class="section">
        <div class="section-title">
            <h2 class="section-title__heading">Chi siamo</h2>
            <p class="section-title__description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>
        <div class="cards-grid" style="gap:20px;margin-top:var(--space-3xl);">
            <?php
            $team = ['Manuel', 'Ada', 'Vania', 'Eleonora'];
            foreach ($team as $name) : ?>
                <div class="card" style="align-items:center;">
                    <div style="height:200px;width:100%;background:var(--color-beige);"></div>
                    <div class="card__body" style="text-align:center;width:100%;">
                        <h4 class="card__title"><?php echo esc_html($name); ?></h4>
                    </div>
                    <div class="card__dot"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<?php get_footer();
