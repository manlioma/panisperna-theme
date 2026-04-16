<?php
/**
 * Archive: Consigli (Parola di)
 *
 * @package Panisperna
 */

get_header();

$parola_page      = get_page_by_path('parola-di');
$hero_label       = '';
$hero_title       = 'Parola di';
$hero_description = $parola_page ? panisperna_field('hero_subtitle', $parola_page->ID) : '';
$hero_image       = $parola_page ? get_post_thumbnail_id($parola_page->ID) : '';
include locate_template('template-parts/hero-page.php');
?>

<main class="site-main content-area">
    <section class="section">
        <div class="section-title">
            <h2 class="section-title__heading">Consigliati da voi</h2>
            <p class="section-title__description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>

        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $wp_query = new WP_Query([
            'post_type'      => 'consiglio',
            'posts_per_page' => 8,
            'paged'          => $paged,
        ]);
        ?>

        <?php if ($wp_query->have_posts()) : ?>
            <div class="cards-grid cards-grid--consiglio" style="margin-top:var(--space-3xl);" data-current-page="1" data-max-pages="<?php echo esc_attr($wp_query->max_num_pages); ?>">
                <?php while ($wp_query->have_posts()) : $wp_query->the_post();
                    get_template_part('template-parts/card-consiglio');
                endwhile; ?>
            </div>

            <?php if ($wp_query->max_num_pages > 1) : ?>
            <div class="load-more-wrap">
                <button class="btn btn--load-more" id="consiglio-load-more">Carica altri contenuti</button>
            </div>
            <?php endif; ?>

        <?php else : ?>
            <p>Nessun consiglio trovato.</p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </section>
</main>

<?php get_footer();
