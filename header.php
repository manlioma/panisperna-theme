<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="top-bar__social">
            <?php
            $newsletter_url = panisperna_field('newsletter_url', 'option');
            $instagram_url  = panisperna_field('instagram_url', 'option');
            $facebook_url   = panisperna_field('facebook_url', 'option');
            ?>
            <?php if ($newsletter_url) : ?>
                <a href="<?php echo esc_url($newsletter_url); ?>" class="btn--outline-white">Newsletter</a>
            <?php endif; ?>
            <?php if ($instagram_url) : ?>
                <a href="<?php echo esc_url($instagram_url); ?>" class="social-icon" aria-label="Instagram" target="_blank" rel="noopener">
                    <?php echo panisperna_icon('instagram'); ?>
                </a>
            <?php endif; ?>
            <?php if ($facebook_url) : ?>
                <a href="<?php echo esc_url($facebook_url); ?>" class="social-icon" aria-label="Facebook" target="_blank" rel="noopener">
                    <?php echo panisperna_icon('facebook'); ?>
                </a>
            <?php endif; ?>
        </div>
        <div class="top-bar__actions">
            <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" aria-label="Account">
                <?php echo panisperna_icon('user'); ?>
            </a>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-icon" aria-label="Carrello">
                <?php echo panisperna_icon('cart'); ?>
                <?php if (WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
                    <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                <?php endif; ?>
            </a>
        </div>
    </div>

    <!-- Nav Bar -->
    <nav class="nav-bar">
        <?php
        wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'nav-bar__menu',
            'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
            'fallback_cb'    => false,
        ]);
        ?>
        <div class="nav-bar__logo-row">
            <div class="nav-bar__dot"></div>
            <div class="nav-bar__line"></div>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-bar__logo" aria-label="<?php bloginfo('name'); ?>">
                <?php include PANISPERNA_DIR . '/assets/images/header-logo-inline.svg'; ?>
            </a>
        </div>
    </nav>
</header>

<!-- 4 vertical grid lines -->
<div class="grid-lines" aria-hidden="true">
    <div class="grid-lines__inner">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
