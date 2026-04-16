<?php
$prefooter_text = panisperna_field('global_prefooter_text', 'option', 'Hai dei pacchetti da consigliare o dei libri da chiederci?');
$prefooter_cta  = panisperna_field('global_prefooter_cta', 'option', 'Contattaci');
$prefooter_link = panisperna_field('global_prefooter_link', 'option', '#');
?>

<!-- Prefooter -->
<div class="prefooter">
    <div class="prefooter__text">
        <?php echo esc_html($prefooter_text); ?>
    </div>
    <a href="<?php echo esc_url($prefooter_link); ?>" class="btn--cta prefooter__cta">
        <?php echo esc_html($prefooter_cta); ?>
    </a>
</div>

<!-- Footer -->
<footer class="site-footer">
    <div class="footer__top">
        <div class="footer__logo">
            <?php echo panisperna_icon('panisperna-logo-footer'); ?>
        </div>
        <div class="footer__social">
            <?php
            $instagram_url  = panisperna_field('instagram_url', 'option');
            $facebook_url   = panisperna_field('facebook_url', 'option');
            $newsletter_url = panisperna_field('newsletter_url', 'option');
            ?>
            <?php if ($instagram_url) : ?>
                <a href="<?php echo esc_url($instagram_url); ?>" class="social-icon social-icon--white" aria-label="Instagram" target="_blank" rel="noopener">
                    <?php echo panisperna_icon('instagram'); ?>
                </a>
            <?php endif; ?>
            <?php if ($facebook_url) : ?>
                <a href="<?php echo esc_url($facebook_url); ?>" class="social-icon social-icon--white" aria-label="Facebook" target="_blank" rel="noopener">
                    <?php echo panisperna_icon('facebook'); ?>
                </a>
            <?php endif; ?>
            <?php if ($newsletter_url) : ?>
                <a href="<?php echo esc_url($newsletter_url); ?>" class="btn--outline-white">Newsletter</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer__columns">
        <div class="footer__menu">
            <div class="footer__menu-column">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer-col-1',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'fallback_cb'    => false,
                ]);
                ?>
            </div>
            <div class="footer__menu-column">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer-col-2',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'fallback_cb'    => false,
                ]);
                ?>
            </div>
        </div>

        <div class="footer__info">
            <div class="footer__hours">
                <p>Dal Lunedì al Sabato</p>
                <p><?php echo esc_html(panisperna_field('orari_lun_sab', 'option', '10:00 - 20:00')); ?></p>
            </div>
            <div class="footer__hours">
                <p>Domenica</p>
                <p><?php echo esc_html(panisperna_field('orari_dom', 'option', '11:00 - 13.30 / 16:30 - 20:00')); ?></p>
            </div>
            <div class="footer__address">
                <?php echo panisperna_icon('ico-marker'); ?>
                <span class="footer__address-text">
                    <?php echo esc_html(panisperna_field('indirizzo', 'option', 'Via Panisperna 220')); ?>
                </span>
            </div>
        </div>
    </div>
</footer>

<!-- Chat widget: WhatsApp -->
<?php $whatsapp_url = panisperna_field('whatsapp_url', 'option'); if ($whatsapp_url) : ?>
<a href="<?php echo esc_url($whatsapp_url); ?>" class="chat-widget" aria-label="Chiedi in libreria" target="_blank" rel="noopener">
    <span class="chat-widget__dot"></span>
    <span>Chiedi in libreria</span>
</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
