</main><!-- /.site-main -->

<!-- ====== Footer ====== -->
<footer class="site-footer" id="site-footer">

    <!-- Footer Top -->
    <div class="footer__top">
        <div class="container">
            <div class="footer__grid">

                <!-- Brand Block -->
                <div class="footer__brand">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer__logo">
                        <?php if ( has_custom_logo() ) : ?>
                            <?php
                            $logo_id  = get_theme_mod( 'custom_logo' );
                            $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
                            ?>
                            <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="footer__logo-img">
                        <?php else : ?>
                            <span class="footer__logo-text">Строй<span class="footer__logo-accent">МСК</span></span>
                        <?php endif; ?>
                    </a>
                    <p class="footer__brand-desc">
                        Премиальное строительство загородных домов под ключ в Москве и Московской области.
                        Проектирование, строительство, отделка с гарантией качества и фиксированной ценой.
                    </p>

                    <?php
                    $email = get_theme_mod( 'stroymsk_email', 'info@stroymsk.ru' );
                    if ( ! empty( $email ) ) :
                        ?>
                        <a href="mailto:<?php echo esc_attr( $email ); ?>" class="footer__email">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            <span><?php echo esc_html( $email ); ?></span>
                        </a>
                    <?php endif; ?>

                    <!-- Social Links -->
                    <div class="footer__social">
                        <?php
                        $social_links = array(
                            'vk'       => 'ВКонтакте',
                            'telegram' => 'Telegram',
                            'whatsapp' => 'WhatsApp',
                            'youtube'  => 'YouTube',
                            'dzen'     => 'Яндекс Дзен',
                        );
                        foreach ( $social_links as $key => $label ) :
                            $url = get_theme_mod( "stroymsk_social_{$key}", '' );
                            if ( ! empty( $url ) ) :
                                ?>
                                <a href="<?php echo esc_url( $url ); ?>" class="footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $label ); ?>">
                                    <?php echo esc_html( $label ); ?>
                                </a>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>

                <!-- Footer Navigation -->
                <div class="footer__nav-col">
                    <h4 class="footer__heading">Навигация</h4>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'footer__nav-list',
                        'fallback_cb'    => false,
                        'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                        'depth'          => 1,
                    ) );
                    ?>
                </div>

                <!-- Certs / Badges -->
                <div class="footer__certs">
                    <h4 class="footer__heading">Сертификаты и аккредитации</h4>
                    <div class="footer__certs-grid">
                        <div class="footer__cert-badge">
                            <span class="footer__cert-badge-label">СРО</span>
                            <span class="footer__cert-badge-desc">Генподряд</span>
                        </div>
                        <div class="footer__cert-badge">
                            <span class="footer__cert-badge-label">ИЖС</span>
                            <span class="footer__cert-badge-desc">Стандарт</span>
                        </div>
                        <div class="footer__cert-badge">
                            <span class="footer__cert-badge-label">Домклик</span>
                            <span class="footer__cert-badge-desc">Партнёр</span>
                        </div>
                        <div class="footer__cert-badge">
                            <span class="footer__cert-badge-label">ДОМ.РФ</span>
                            <span class="footer__cert-badge-desc">Аккредитация</span>
                        </div>
                        <div class="footer__cert-badge">
                            <span class="footer__cert-badge-label">Shinglas</span>
                            <span class="footer__cert-badge-desc">Партнёр</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Widgets -->
                <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                    <div class="footer__widgets">
                        <?php dynamic_sidebar( 'footer-1' ); ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- Partners Marquee -->
    <div class="footer__partners">
        <div class="footer__partners-track">
            <?php
            $partners = array(
                'Knauf',
                'Rockwool',
                'Технониколь',
                'Ceresit',
                'Weber Vetonit',
                'Пеноплэкс',
                'Buderus',
                'Viessmann',
                'Rehau',
                'VEKA',
                'Sch&uuml;co',
                'Porotherm',
                'Ytong',
                'Grand Line',
                'ABB',
                'Legrand',
            );

            // Double the list for seamless infinite scroll
            $all_partners = array_merge( $partners, $partners );
            foreach ( $all_partners as $partner ) :
                ?>
                <span class="footer__partner-item"><?php echo $partner; ?></span>
                <span class="footer__partner-separator">&bull;</span>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer__bottom">
        <div class="container">
            <div class="footer__bottom-inner">
                <p class="footer__copyright">&copy; 2025 СтройМСК. Все права защищены.</p>
                <nav class="footer__bottom-nav">
                    <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" class="footer__bottom-link">Конфиденциальность</a>
                    <a href="<?php echo esc_url( home_url( '/offer/' ) ); ?>" class="footer__bottom-link">Оферта</a>
                    <a href="https://romanai.ru" class="footer__bottom-link" target="_blank" rel="noopener noreferrer">Разработка сайта — romanai.ru</a>
                </nav>
            </div>
        </div>
    </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
