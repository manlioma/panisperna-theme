<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$newsletter_url = panisperna_field('newsletter_url', 'option');
$instagram_url  = panisperna_field('instagram_url', 'option');
$facebook_url   = panisperna_field('facebook_url', 'option');
?>

<header class="site-header">
    <!-- Top Bar (hidden on mobile) -->
    <div class="top-bar">
        <div class="top-bar__social">
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
            <button type="button" class="cart-icon" id="cart-toggle" aria-label="Carrello">
                <?php echo panisperna_icon('cart'); ?>
                <?php $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
                <span class="cart-count"<?php echo $cart_count === 0 ? ' style="display:none"' : ''; ?>><?php echo $cart_count > 0 ? esc_html($cart_count) : ''; ?></span>
            </button>
        </div>
    </div>

    <!-- Nav Bar -->
    <nav class="nav-bar">
        <!-- Desktop menu -->
        <?php
        wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'nav-bar__menu',
            'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
            'fallback_cb'    => false,
        ]);
        ?>

        <!-- Logo row -->
        <div class="nav-bar__logo-row">
            <div class="nav-bar__dot"></div>
            <div class="nav-bar__line"></div>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-bar__logo" aria-label="<?php bloginfo('name'); ?>">
                <?php include PANISPERNA_DIR . '/assets/images/header-logo-inline.svg'; ?>
            </a>
        </div>

        <!-- Mobile hamburger -->
        <button class="menu-toggle" aria-label="Menu" aria-expanded="false">
            <span class="menu-toggle__bar"></span>
            <span class="menu-toggle__bar"></span>
            <span class="menu-toggle__bar"></span>
        </button>
    </nav>
</header>

<!-- Mobile menu overlay -->
<div class="mobile-menu" id="mobile-menu">
    <div class="mobile-menu__header">
        <div class="mobile-menu__top-bar">
            <div class="mobile-menu__social">
                <?php if ($facebook_url) : ?>
                    <a href="<?php echo esc_url($facebook_url); ?>" class="social-icon" aria-label="Facebook" target="_blank" rel="noopener">
                        <?php echo panisperna_icon('facebook'); ?>
                    </a>
                <?php endif; ?>
                <?php if ($instagram_url) : ?>
                    <a href="<?php echo esc_url($instagram_url); ?>" class="social-icon" aria-label="Instagram" target="_blank" rel="noopener">
                        <?php echo panisperna_icon('instagram'); ?>
                    </a>
                <?php endif; ?>
                <?php if ($newsletter_url) : ?>
                    <a href="<?php echo esc_url($newsletter_url); ?>" class="btn--outline-grey">Newsletter</a>
                <?php endif; ?>
            </div>
            <div class="mobile-menu__actions">
                <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" aria-label="Account">
                    <?php echo panisperna_icon('user'); ?>
                </a>
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-icon" aria-label="Carrello">
                    <?php echo panisperna_icon('cart'); ?>
                </a>
            </div>
        </div>
        <div class="mobile-menu__logo-row">
            <div class="nav-bar__dot"></div>
            <div class="nav-bar__line"></div>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-bar__logo">
                <?php include PANISPERNA_DIR . '/assets/images/header-logo-inline.svg'; ?>
            </a>
            <button class="mobile-menu__close" aria-label="Chiudi menu">✕</button>
        </div>
    </div>
    <div class="mobile-menu__body">
        <?php
        wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'mobile-menu__nav',
            'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
            'fallback_cb'    => false,
        ]);
        ?>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('contratti'))); ?>" class="mobile-menu__cta">
            &rarr; Come acquistare
        </a>
    </div>
</div>

<!-- 4 vertical grid lines -->
<div class="grid-lines" aria-hidden="true">
    <div class="grid-lines__inner">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

<!-- Sidebar Cart Panel -->
<div class="sidebar-cart" id="sidebar-cart" aria-hidden="true">
    <div class="sidebar-cart__overlay"></div>
    <div class="sidebar-cart__panel">
        <div class="sidebar-cart__header">
            <h3 class="sidebar-cart__title">Carrello</h3>
            <button type="button" class="sidebar-cart__close" aria-label="Chiudi carrello">&times;</button>
        </div>
        <div class="sidebar-cart__body">
            <?php if (WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
                <div class="sidebar-cart__items">
                    <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                        $product = $cart_item['data'];
                        $product_id = $cart_item['product_id'];
                        $quantity = $cart_item['quantity'];
                        $thumbnail = $product->get_image('thumbnail');
                    ?>
                        <div class="sidebar-cart__item" data-key="<?php echo esc_attr($cart_item_key); ?>">
                            <div class="sidebar-cart__item-image"><?php echo $thumbnail; ?></div>
                            <div class="sidebar-cart__item-info">
                                <span class="sidebar-cart__item-name"><?php echo esc_html($product->get_name()); ?></span>
                                <span class="sidebar-cart__item-qty">&times;<?php echo esc_html($quantity); ?></span>
                                <span class="sidebar-cart__item-price"><?php echo $product->get_price_html(); ?></span>
                            </div>
                            <button type="button" class="sidebar-cart__item-remove" data-key="<?php echo esc_attr($cart_item_key); ?>" aria-label="Rimuovi">&times;</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="sidebar-cart__footer">
                    <div class="sidebar-cart__subtotal">
                        <span>Subtotale</span>
                        <span><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                    </div>
                    <div class="sidebar-cart__subtotal sidebar-cart__shipping">
                        <span>Spedizione</span>
                        <span><?php
                            $shipping_total = WC()->cart->get_shipping_total();
                            if (floatval($shipping_total) > 0) {
                                echo wc_price($shipping_total);
                            } else {
                                echo 'Calcolata al checkout';
                            }
                        ?></span>
                    </div>
                    <div class="sidebar-cart__subtotal sidebar-cart__total">
                        <span>Totale</span>
                        <span><?php echo WC()->cart->get_total(); ?></span>
                    </div>
                    <button type="button" class="sidebar-cart__clear" id="sidebar-cart-clear">Svuota carrello</button>
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="btn btn--outline sidebar-cart__btn">Vai al carrello</a>
                    <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="btn btn--primary sidebar-cart__btn">Checkout</a>
                </div>
            <?php else : ?>
                <p class="sidebar-cart__empty">Il carrello e' vuoto.</p>
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn--outline sidebar-cart__btn">Vai allo shop</a>
            <?php endif; ?>
        </div>
    </div>
</div>
