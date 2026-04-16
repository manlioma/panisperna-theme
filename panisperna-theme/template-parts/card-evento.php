<?php
/**
 * Component: Event Card
 *
 * Used in: homepage, archive-evento, AJAX handler
 * Expects: inside a WP loop (the_post() called)
 *
 * @package Panisperna
 */

$tipos = get_the_terms(get_the_ID(), 'tipo_evento');
$tag   = ($tipos && !is_wp_error($tipos)) ? $tipos[0]->name : '';
?>
<div class="event-card-wrap">
    <a href="<?php the_permalink(); ?>" class="event-card">
        <div class="event-card__image">
            <?php the_post_thumbnail('event-card', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
            <?php if ($tag) : ?>
                <span class="btn--tag"><?php echo esc_html($tag); ?></span>
            <?php endif; ?>
        </div>
        <div class="event-card__body">
            <div>
                <h4 class="event-card__title"><?php the_title(); ?></h4>
                <p class="event-card__date"><?php echo esc_html(panisperna_format_event_date(panisperna_field('evento_data'))); ?></p>
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
