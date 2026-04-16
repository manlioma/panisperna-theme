<?php
/**
 * Hero for internal pages
 *
 * Variables expected:
 * $hero_label - tag label (es. "Libreria", "Proposte")
 * $hero_title - H1 title
 * $hero_description - subtitle text (from ACF hero_subtitle or override)
 * $hero_image - image URL or attachment ID (falls back to featured image)
 *
 * @package Panisperna
 */

$hero_label       = $hero_label ?? '';
$hero_title       = $hero_title ?? get_the_title();
$hero_description = isset($hero_description) ? $hero_description : panisperna_field('hero_subtitle');
$hero_image       = $hero_image ?: get_post_thumbnail_id();
?>

<section class="hero hero--page">
    <div class="hero--page__content">
        <div class="hero--page__text">
            <?php if ($hero_label) : ?>
                <span class="h5" style="color: var(--color-grey);"><?php echo esc_html($hero_label); ?></span>
            <?php endif; ?>
            <h1 class="h1"><?php echo esc_html($hero_title); ?></h1>
            <?php if ($hero_description) : ?>
                <p style="margin-top: var(--space-md);"><?php echo esc_html($hero_description); ?></p>
            <?php endif; ?>
        </div>
        <?php if ($hero_image) : ?>
            <div class="hero--page__image">
                <?php if (is_numeric($hero_image)) : ?>
                    <?php echo wp_get_attachment_image($hero_image, 'large'); ?>
                <?php else : ?>
                    <img src="<?php echo esc_url($hero_image); ?>" alt="" loading="lazy">
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
