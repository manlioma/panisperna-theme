<?php
/**
 * Generic page template
 *
 * @package Panisperna
 */

get_header(); ?>

<main class="site-main content-area">
    <?php while (have_posts()) : the_post(); ?>
        <section class="section">
            <h1 class="h1"><?php the_title(); ?></h1>
            <div class="entry-content" style="margin-top: var(--space-xl);">
                <?php the_content(); ?>
            </div>
        </section>
    <?php endwhile; ?>
</main>

<?php get_footer();
