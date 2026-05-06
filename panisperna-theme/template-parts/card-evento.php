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

$descrizione = panisperna_field('evento_descrizione');
?>
<div class="event-card-wrap">
    <a href="<?php the_permalink(); ?>" class="event-card">
        <div class="event-card__image">
            <?php the_post_thumbnail('event-card', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
        </div>
        <div class="event-card__body">
            <?php if ($tag) : ?>
                <span class="btn--tag"><?php echo esc_html($tag); ?></span>
            <?php endif; ?>
            <div class="event-card__meta">
                <p class="event-card__date"><?php echo esc_html(panisperna_format_event_date(panisperna_field('evento_data'), true)); ?></p>
                <p class="event-card__time">Ore <?php echo esc_html(panisperna_field('evento_ora')); ?></p>
                <h4 class="event-card__title"><?php the_title(); ?></h4>
            </div>
            <?php if ($descrizione) : ?>
                <p class="event-card__subtitle"><?php echo esc_html($descrizione); ?></p>
            <?php endif; ?>
        </div>
    </a>
    <div class="card__dot"></div>
</div>
