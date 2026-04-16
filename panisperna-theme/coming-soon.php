<?php
/**
 * Custom Coming Soon / Maintenance page
 * Overrides WooCommerce default coming-soon template
 */
defined('ABSPATH') || exit;

$instagram_url  = panisperna_field('instagram_url', 'option');
$facebook_url   = panisperna_field('facebook_url', 'option');
$indirizzo      = panisperna_field('indirizzo', 'option', 'Via Panisperna 220');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php bloginfo('name'); ?> — Stiamo arrivando</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f5f0e1;
            color: #1a1a1a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .coming-soon {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            padding: 2rem;
            max-width: 600px;
        }

        .coming-soon__logo svg {
            width: 280px;
            height: auto;
        }

        .coming-soon__title {
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .coming-soon__subtitle {
            font-size: 1.75rem;
            font-weight: 300;
            font-style: italic;
        }

        .coming-soon__address {
            font-size: 1rem;
            line-height: 1.6;
            border: 1px solid #1a1a1a;
            padding: 1rem 2rem;
            display: inline-block;
        }

        .coming-soon__social {
            display: flex;
            gap: 1.25rem;
            align-items: center;
        }

        .coming-soon__social a {
            color: #1a1a1a;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .coming-soon__social a:hover {
            opacity: 0.6;
        }

        .coming-soon__social svg {
            width: 24px;
            height: 24px;
        }


        @media (max-width: 480px) {
            .coming-soon__logo svg {
                width: 200px;
            }
            .coming-soon__title {
                font-size: 1.25rem;
            }
            .coming-soon__subtitle {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <div class="coming-soon">
        <div class="coming-soon__logo">
            <?php echo panisperna_icon('panisperna-logo-footer'); ?>
        </div>

        <h1 class="coming-soon__title"><?php bloginfo('name'); ?></h1>

        <h2 class="coming-soon__subtitle">Stiamo arrivando</h2>

        <div class="coming-soon__address">
            <?php echo esc_html($indirizzo); ?>
        </div>

        <div class="coming-soon__social">
            <?php if ($instagram_url) : ?>
                <a href="<?php echo esc_url($instagram_url); ?>" aria-label="Instagram" target="_blank" rel="noopener">
                    <?php echo panisperna_icon('instagram'); ?>
                </a>
            <?php endif; ?>
            <?php if ($facebook_url) : ?>
                <a href="<?php echo esc_url($facebook_url); ?>" aria-label="Facebook" target="_blank" rel="noopener">
                    <?php echo panisperna_icon('facebook'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
