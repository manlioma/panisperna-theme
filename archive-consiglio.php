<?php
/**
 * Archive: Consigli (Parola di)
 *
 * @package Panisperna
 */

get_header();

$hero_label       = 'Parola di';
$hero_title       = 'Parola di';
$hero_description = 'Frase di introduzione alla pagina inserita, testo non troppo lungo, massimo quattro righe.';
$hero_image       = '';
include locate_template('template-parts/hero-page.php');
?>

<main class="site-main content-area">
    <section class="section">
        <div class="section-title">
            <h2 class="section-title__heading">Consigliati da voi</h2>
            <p class="section-title__description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>

        <?php if (have_posts()) : ?>
            <div class="cards-grid" style="margin-top:var(--space-3xl);">
                <?php while (have_posts()) : the_post();
                    $foto = panisperna_field('consiglio_foto');
                    $nome = panisperna_field('consiglio_nome', false, get_the_title());
                ?>
                    <div class="card" style="align-items:center;">
                        <a href="<?php the_permalink(); ?>" class="card__image" style="height:495px;width:100%;display:block;">
                            <?php if ($foto) : ?>
                                <img src="<?php echo esc_url($foto['sizes']['consiglio-portrait'] ?? $foto['url']); ?>"
                                     alt="<?php echo esc_attr($nome); ?>"
                                     style="width:100%;height:100%;object-fit:cover;"
                                     loading="lazy">
                            <?php else : ?>
                                <?php the_post_thumbnail('consiglio-portrait', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
                            <?php endif; ?>
                        </a>
                        <div class="card__body" style="text-align:center;width:100%;">
                            <h4 class="card__title"><a href="<?php the_permalink(); ?>"><?php echo esc_html($nome); ?></a></h4>
                        </div>
                        <div class="card__dot"></div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div style="text-align:center;margin-top:var(--space-3xl);">
                <button class="contact-form__submit" style="width:100%;max-width:600px;">Carica altri contenuti</button>
            </div>

            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p>Nessun consiglio trovato.</p>
        <?php endif; ?>
    </section>
</main>

<?php get_footer();
