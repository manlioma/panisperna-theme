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
            <?php
            $prossimi_descrizione = $incontri_page ? panisperna_field('prossimi_incontri_descrizione', $incontri_page->ID) : '';
            if ($prossimi_descrizione) :
            ?>
            <p class="section-title__description"><?php echo esc_html($prossimi_descrizione); ?></p>
            <?php endif; ?>
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
            <p class="eventi-empty">Nessun evento in programma.</p>
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

    // Build the chip list from tipo_evento terms attached to PAST events only.
    $archive_term_slugs = [];
    if ($archivio->have_posts()) {
        $past_ids = wp_list_pluck($archivio->posts, 'ID');
        if (!empty($past_ids)) {
            $past_terms = wp_get_object_terms($past_ids, 'tipo_evento', ['fields' => 'all']);
            if (!is_wp_error($past_terms) && !empty($past_terms)) {
                $unique = [];
                foreach ($past_terms as $t) { $unique[$t->term_id] = $t; }
                $past_terms = array_values($unique);
                usort($past_terms, function ($a, $b) { return strcasecmp($a->name, $b->name); });
                $archive_term_slugs = $past_terms;
            }
        }
    }

    if ($archivio->have_posts()) : ?>
    <hr class="section-divider">
    <section class="section">
        <div class="section-title">
            <h2 class="section-title__heading">Archivio</h2>
        </div>

        <?php if (!empty($archive_term_slugs)) : ?>
        <div class="eventi-filters" style="display:flex;gap:var(--space-sm);justify-content:center;margin:var(--space-xl) 0;flex-wrap:wrap;">
            <button class="btn btn--outline btn--chip btn--chip-evento is-active" data-category="tutti">Tutti</button>
            <?php foreach ($archive_term_slugs as $tipo) : ?>
                <button class="btn btn--outline btn--chip btn--chip-evento" data-category="<?php echo esc_attr($tipo->slug); ?>"><?php echo esc_html($tipo->name); ?></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="cards-row cards-row--eventi cards-row--eventi--archive" data-category="tutti" data-current-page="1">
            <?php while ($archivio->have_posts()) : $archivio->the_post();
                get_template_part('template-parts/card-evento');
            endwhile; ?>
        </div>
    </section>
    <?php endif;
    wp_reset_postdata(); ?>
</main>

<?php get_footer();
