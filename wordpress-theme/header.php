<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'dark-theme' ); ?>>
<?php wp_body_open(); ?>

<!-- Noscript YM pixel -->
<noscript><div><img src="https://mc.yandex.ru/watch/107155846" style="position:absolute;left:-9999px;" alt=""></div></noscript>

<!-- JSON-LD LocalBusiness -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "СтройМСК",
    "telephone": "<?php echo esc_js( stroymsk_phone() ); ?>",
    "email": "<?php echo esc_js( get_theme_mod( 'stroymsk_email', 'info@stroymsk.ru' ) ); ?>",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?php echo esc_js( get_theme_mod( 'stroymsk_address', 'г. Москва, ул. Строителей, д. 1' ) ); ?>",
        "addressLocality": "Москва",
        "addressRegion": "Московская область",
        "addressCountry": "RU"
    },
    "url": "<?php echo esc_url( home_url( '/' ) ); ?>",
    "openingHours": "Mo-Fr 09:00-19:00",
    "priceRange": "₽₽₽"
}
</script>

<!-- ====== Fixed Header with Glass Effect ====== -->
<header class="site-header" id="site-header">
    <div class="container">
        <div class="header-inner">

            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" aria-label="<?php bloginfo( 'name' ); ?>">
                <?php if ( has_custom_logo() ) : ?>
                    <?php
                    $logo_id  = get_theme_mod( 'custom_logo' );
                    $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
                    ?>
                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="site-logo__img">
                <?php else : ?>
                    <img src="https://s3.ru1.storage.beget.cloud/76ae0220f799-proficient-naida/upload/177278828511285.jpeg" alt="<?php bloginfo( 'name' ); ?>" class="site-logo__img" style="height:2.25rem;width:auto;">
                <?php endif; ?>
            </a>

            <!-- Desktop Navigation -->
            <nav class="main-nav" id="main-nav" aria-label="Основная навигация">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'main-nav__list',
                    'fallback_cb'    => 'stroymsk_fallback_menu',
                    'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                ) );
                ?>
            </nav>

            <!-- Desktop Phone -->
            <a href="tel:<?php echo esc_attr( stroymsk_phone_raw() ); ?>" class="header-phone">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <span><?php echo stroymsk_phone(); ?></span>
            </a>

            <!-- Desktop CTA -->
            <a href="#contacts" class="header-cta btn btn--primary">
                <?php echo esc_html( get_theme_mod( 'stroymsk_cta_header', 'Связаться' ) ); ?>
            </a>

            <!-- Mobile Burger -->
            <button class="menu-toggle" id="menu-toggle" aria-label="Открыть меню" aria-expanded="false">
                <span class="menu-toggle__line"></span>
                <span class="menu-toggle__line"></span>
                <span class="menu-toggle__line"></span>
            </button>

        </div>
    </div>
</header>

<!-- ====== Mobile Drawer ====== -->
<div class="mobile-drawer" id="mobile-drawer" aria-hidden="true">
    <div class="mobile-drawer__overlay" id="mobile-drawer-overlay"></div>
    <div class="mobile-drawer__panel">

        <!-- Drawer Header -->
        <div class="mobile-drawer__header">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo">
                <span class="site-logo__text">Строй<span class="site-logo__accent">МСК</span></span>
            </a>
            <button class="mobile-drawer__close" id="mobile-drawer-close" aria-label="Закрыть меню">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Drawer Navigation -->
        <nav class="mobile-drawer__nav" aria-label="Мобильная навигация">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'mobile-drawer__nav-list',
                'fallback_cb'    => 'stroymsk_fallback_menu',
                'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
            ) );
            ?>
        </nav>

        <!-- Drawer Phone -->
        <a href="tel:<?php echo esc_attr( stroymsk_phone_raw() ); ?>" class="mobile-drawer__phone">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <span><?php echo stroymsk_phone(); ?></span>
        </a>

        <!-- Drawer CTA -->
        <a href="#contacts" class="mobile-drawer__cta btn btn--primary btn--block">
            <?php echo esc_html( get_theme_mod( 'stroymsk_cta_header', 'Связаться' ) ); ?>
        </a>

        <!-- Drawer Social Links -->
        <div class="mobile-drawer__social">
            <?php
            $social_links = array(
                'vk'       => 'ВКонтакте',
                'telegram' => 'Telegram',
                'whatsapp' => 'WhatsApp',
                'youtube'  => 'YouTube',
                'dzen'     => 'Дзен',
            );
            foreach ( $social_links as $key => $label ) :
                $url = get_theme_mod( "stroymsk_social_{$key}", '' );
                if ( ! empty( $url ) ) :
                    ?>
                    <a href="<?php echo esc_url( $url ); ?>" class="mobile-drawer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $label ); ?>">
                        <?php echo esc_html( $label ); ?>
                    </a>
                    <?php
                endif;
            endforeach;
            ?>
        </div>

    </div>
</div>

<!-- Main Content Wrapper -->
<main class="site-main" id="site-main">
