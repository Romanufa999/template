<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>Строй<span style="color: var(--primary)">МСК</span></h4>
                <p>Строительная компания с многолетним опытом. Выполняем полный цикл строительных и ремонтных работ в Москве и Московской области.</p>
            </div>
            <div class="footer-col">
                <h4>Услуги</h4>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container'      => false,
                    'fallback_cb'    => false,
                ));
                ?>
            </div>
            <div class="footer-col">
                <h4>Контакты</h4>
                <ul>
                    <li><a href="tel:+74951234567">+7 (495) 123-45-67</a></li>
                    <li><a href="mailto:info@stroymsk.ru">info@stroymsk.ru</a></li>
                    <li>г. Москва, ул. Строителей, д. 10</li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Режим работы</h4>
                <ul>
                    <li>Пн-Пт: 9:00 — 19:00</li>
                    <li>Сб: 10:00 — 16:00</li>
                    <li>Вс: выходной</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Все права защищены.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
