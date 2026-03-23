<?php
/**
 * Archive: Eventi (Incontri)
 *
 * @package Panisperna
 */

get_header();

$hero_label       = 'Incontri';
$hero_title       = 'Incontri';
$hero_description = 'Frase di introduzione alla pagina inserita, testo non troppo lungo, massimo quattro righe.';
$hero_image       = '';
include locate_template('template-parts/hero-page.php');
?>

<main class="site-main content-area">
    <!-- Prossimi incontri -->
    <section class="section">
        <div class="section-title">
            <h2 class="section-title__heading">Prossimi incontri</h2>
            <p class="section-title__description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>

        <!-- Filtri -->
        <div style="display:flex;gap:var(--space-sm);justify-content:center;margin:var(--space-xl) 0;">
            <a href="#" class="btn btn--outline" style="font-size:14px;padding:8px 20px;">Tutti</a>
            <a href="#" class="btn btn--outline" style="font-size:14px;padding:8px 20px;">GdL</a>
            <a href="#" class="btn btn--outline" style="font-size:14px;padding:8px 20px;">Altro</a>
        </div>

        <?php if (have_posts()) : ?>
            <div class="cards-row cards-row--eventi">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="event-card-wrap">
                        <a href="<?php the_permalink(); ?>" class="event-card">
                            <div class="event-card__image">
                                <?php the_post_thumbnail('event-card', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
                                <?php $tag = panisperna_field('evento_tipo_tag'); ?>
                                <?php if ($tag) : ?>
                                    <span class="btn--tag"><?php echo esc_html($tag); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="event-card__body">
                                <div>
                                    <h4 class="event-card__title"><?php the_title(); ?></h4>
                                    <p class="event-card__date"><?php echo esc_html(panisperna_field('evento_data')); ?></p>
                                    <p class="event-card__time">Ore <?php echo esc_html(panisperna_field('evento_ora')); ?></p>
                                </div>
                                <div class="event-card__location">
                                    <?php echo panisperna_icon('map-pin'); ?>
                                    <span><?php echo esc_html(panisperna_field('evento_luogo', false, 'Libreria Panisperna 220')); ?></span>
                                </div>
                            </div>
                        </a>
                        <div class="card__dot"></div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div style="text-align:center;margin-top:var(--space-3xl);">
                <button class="contact-form__submit" style="width:100%;max-width:600px;">Carica altri contenuti</button>
            </div>

            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p>Nessun evento in programma.</p>
        <?php endif; ?>
    </section>
</main>

<?php get_footer();
