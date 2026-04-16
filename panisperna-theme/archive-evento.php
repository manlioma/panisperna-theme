<?php
/**
 * Archive: Eventi (Incontri)
 *
 * @package Panisperna
 */

get_header();

$incontri_page    = get_page_by_path('incontri');
$hero_label       = '';
$hero_title       = 'Incontri';
$hero_description = $incontri_page ? panisperna_field('hero_subtitle', $incontri_page->ID) : '';
$hero_image       = $incontri_page ? get_post_thumbnail_id($incontri_page->ID) : '';
include locate_template('template-parts/hero-page.php');
?>

<main class="site-main content-area">
    <!-- Prossimi incontri -->
    <section class="section">
        <div class="section-title">
            <h2 class="section-title__heading">Prossimi incontri</h2>
            <p class="section-title__description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
        </div>

        <!-- Filtri dinamici da tipo_evento taxonomy -->
        <div class="eventi-filters" style="display:flex;gap:var(--space-sm);justify-content:center;margin:var(--space-xl) 0;flex-wrap:wrap;">
            <button class="btn btn--outline btn--chip btn--chip-evento is-active" data-category="tutti">Tutti</button>
            <?php
            $tipos = get_terms(['taxonomy' => 'tipo_evento', 'hide_empty' => true]);
            if (!is_wp_error($tipos)) :
                foreach ($tipos as $tipo) : ?>
                    <button class="btn btn--outline btn--chip btn--chip-evento" data-category="<?php echo esc_attr($tipo->slug); ?>"><?php echo esc_html($tipo->name); ?></button>
                <?php endforeach;
            endif;
            ?>
        </div>

        <?php
        $today = date('Ymd');

        // Prossimi eventi (>= oggi, data ASC)
        $prossimi = new WP_Query([
            'post_type'      => 'evento',
            'posts_per_page' => -1,
            'meta_key'       => 'evento_data',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_query'     => [
                [
                    'key'     => 'evento_data',
                    'value'   => $today,
                    'compare' => '>=',
                    'type'    => 'DATE',
                ],
            ],
        ]);
        ?>

        <?php if ($prossimi->have_posts()) : ?>
            <div class="cards-row cards-row--eventi">
                <?php while ($prossimi->have_posts()) : $prossimi->the_post();
                    get_template_part('template-parts/card-evento');
                endwhile; ?>
            </div>
        <?php else : ?>
            <p>Nessun evento in programma.</p>
        <?php endif;
        wp_reset_postdata(); ?>
    </section>

    <!-- Archivio -->
    <?php
    $archivio = new WP_Query([
        'post_type'      => 'evento',
        'posts_per_page' => -1,
        'meta_key'       => 'evento_data',
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
        'meta_query'     => [
            [
                'key'     => 'evento_data',
                'value'   => $today,
                'compare' => '<',
                'type'    => 'DATE',
            ],
        ],
    ]);

    if ($archivio->have_posts()) : ?>
    <hr class="section-divider">
    <section class="section">
        <div class="section-title">
            <h2 class="section-title__heading">Archivio</h2>
        </div>

        <div class="cards-row cards-row--eventi">
            <?php while ($archivio->have_posts()) : $archivio->the_post();
                get_template_part('template-parts/card-evento');
            endwhile; ?>
        </div>
    </section>
    <?php endif;
    wp_reset_postdata(); ?>
</main>

<?php get_footer();
