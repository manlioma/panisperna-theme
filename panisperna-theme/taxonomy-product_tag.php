<?php
/**
 * WooCommerce Product Tag Archive
 *
 * @package Panisperna
 */

defined('ABSPATH') || exit;

get_header();

$term = get_queried_object();

$hero_label       = 'Tag';
$hero_title       = $term->name;
$hero_description = $term->description ?: '';
$hero_image       = '';
include locate_template('template-parts/hero-page.php');
?>

<main class="site-main content-area">
    <section class="section">
        <?php if (have_posts()) : ?>
            <div class="cards-grid cards-grid--collezione">
                <?php while (have_posts()) : the_post();
                    $product = wc_get_product(get_the_ID());
                    if (!$product) continue;
                    include locate_template('template-parts/card-collezione.php');
                endwhile; ?>
            </div>

            <?php if ($wp_query->max_num_pages > 1) : ?>
            <div class="load-more-wrap" style="margin-top:var(--space-3xl);">
                <?php the_posts_pagination([
                    'prev_text' => '&larr;',
                    'next_text' => '&rarr;',
                ]); ?>
            </div>
            <?php endif; ?>

        <?php else : ?>
            <p>Nessun prodotto trovato con questo tag.</p>
        <?php endif; ?>
    </section>
</main>

<?php get_footer();
