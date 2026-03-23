<?php
/**
 * Template Name: Contratti
 *
 * @package Panisperna
 */

get_header(); ?>

<main class="site-main content-area">
    <section class="section">
        <div class="section-title">
            <h1 class="section-title__heading"><?php the_title(); ?></h1>
        </div>

        <?php
        $sezioni = panisperna_field('contratti_sezioni');
        if ($sezioni) :
            foreach ($sezioni as $sezione) : ?>
                <div class="section" style="padding: var(--space-3xl) 0;">
                    <h2 class="h2" style="margin-bottom: var(--space-xl);"><?php echo esc_html($sezione['titolo']); ?></h2>
                    <div class="entry-content">
                        <?php echo wp_kses_post($sezione['contenuto']); ?>
                    </div>
                </div>
            <?php endforeach;
        endif; ?>
    </section>
</main>

<?php get_footer();
