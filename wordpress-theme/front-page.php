<?php
/**
 * Template Name: Главная страница
 * Шаблон главной страницы СтройМСК
 */

get_header();
?>

<!-- HERO — Главный экран -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Строительство и ремонт <span>под ключ</span> в Москве</h1>
            <p>Выполняем полный цикл строительных работ — от фундамента до чистовой отделки. Гарантия качества, прозрачные сметы, соблюдение сроков.</p>
            <div class="hero-buttons">
                <a href="#contact" class="btn btn-primary">Оставить заявку</a>
                <a href="#services" class="btn btn-outline">Наши услуги</a>
            </div>
        </div>
    </div>
</section>

<!-- ПРЕИМУЩЕСТВА — Цифры компании -->
<section class="section">
    <div class="container">
        <div class="advantages-grid">
            <div class="advantage-item">
                <div class="advantage-number">15+</div>
                <h3>Лет на рынке</h3>
                <p>Опыт работы с 2010 года</p>
            </div>
            <div class="advantage-item">
                <div class="advantage-number">500+</div>
                <h3>Объектов сдано</h3>
                <p>Жилые и коммерческие</p>
            </div>
            <div class="advantage-item">
                <div class="advantage-number">50+</div>
                <h3>Специалистов</h3>
                <p>Квалифицированная команда</p>
            </div>
            <div class="advantage-item">
                <div class="advantage-number">3 года</div>
                <h3>Гарантия</h3>
                <p>На все виды работ</p>
            </div>
        </div>
    </div>
</section>

<!-- УСЛУГИ -->
<section class="section section-gray" id="services">
    <div class="container">
        <div class="section-title">
            <h2>Наши услуги</h2>
            <p>Полный спектр строительных и ремонтных работ для вашего дома или бизнеса</p>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">&#127960;</div>
                <h3>Строительство домов</h3>
                <p>Строительство загородных домов, коттеджей и таунхаусов из кирпича, газобетона, каркасных конструкций.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">&#128295;</div>
                <h3>Ремонт квартир</h3>
                <p>Капитальный и косметический ремонт квартир любой сложности. Дизайн-проект в подарок.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">&#127959;</div>
                <h3>Коммерческое строительство</h3>
                <p>Строительство офисов, складов, торговых павильонов и производственных помещений.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">&#128208;</div>
                <h3>Проектирование</h3>
                <p>Разработка архитектурных проектов, дизайн интерьеров, подготовка рабочей документации.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">&#9889;</div>
                <h3>Инженерные сети</h3>
                <p>Монтаж отопления, водоснабжения, канализации, электрики и вентиляции.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">&#127795;</div>
                <h3>Благоустройство</h3>
                <p>Ландшафтный дизайн, укладка тротуарной плитки, установка заборов и ворот.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA — Призыв к действию -->
<section class="cta-section">
    <div class="container">
        <h2>Рассчитайте стоимость вашего проекта</h2>
        <p>Бесплатный выезд замерщика и составление сметы за 1 день</p>
        <a href="#contact" class="btn btn-white">Получить расчёт</a>
    </div>
</section>

<!-- ПРОЕКТЫ / ПОРТФОЛИО -->
<section class="section" id="projects">
    <div class="container">
        <div class="section-title">
            <h2>Наши проекты</h2>
            <p>Реализованные объекты, которыми мы гордимся</p>
        </div>
        <div class="projects-grid">
            <?php
            // Выводим последние 6 записей из категории "Проекты"
            $projects = new WP_Query(array(
                'post_type'      => 'post',
                'posts_per_page' => 6,
                'category_name'  => 'projects',
            ));

            if ($projects->have_posts()) :
                while ($projects->have_posts()) : $projects->the_post();
            ?>
                <div class="project-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large'); ?>
                    <?php else : ?>
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,#e0e0e0,#f5f5f5);display:flex;align-items:center;justify-content:center;color:#999;font-size:14px;">Фото проекта</div>
                    <?php endif; ?>
                    <div class="project-overlay">
                        <h3><?php the_title(); ?></h3>
                        <p><?php echo wp_trim_words(get_the_excerpt(), 10); ?></p>
                    </div>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                // Заглушки если записей нет
                for ($i = 1; $i <= 6; $i++) :
            ?>
                <div class="project-card">
                    <div style="width:100%;height:100%;background:linear-gradient(135deg,#e0e0e0,#f5f5f5);display:flex;align-items:center;justify-content:center;color:#999;font-size:14px;">Проект <?php echo $i; ?></div>
                    <div class="project-overlay">
                        <h3>Проект <?php echo $i; ?></h3>
                        <p>Описание проекта</p>
                    </div>
                </div>
            <?php
                endfor;
            endif;
            ?>
        </div>
    </div>
</section>

<!-- КОНТАКТЫ -->
<section class="section section-gray" id="contact">
    <div class="container">
        <div class="section-title">
            <h2>Свяжитесь с нами</h2>
            <p>Оставьте заявку и мы перезвоним вам в течение 15 минут</p>
        </div>
        <div class="contact-wrapper">
            <div class="contact-info">
                <h3>Контактная информация</h3>
                <div class="contact-info-item">
                    <div class="icon">&#128222;</div>
                    <div>
                        <strong><a href="tel:+74951234567">+7 (495) 123-45-67</a></strong>
                        <span>Ежедневно с 9:00 до 19:00</span>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="icon">&#9993;</div>
                    <div>
                        <strong><a href="mailto:info@stroymsk.ru">info@stroymsk.ru</a></strong>
                        <span>Ответим в течение часа</span>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="icon">&#128205;</div>
                    <div>
                        <strong>г. Москва, ул. Строителей, д. 10</strong>
                        <span>Офис 201, 2 этаж</span>
                    </div>
                </div>
            </div>
            <div class="contact-form">
                <h3>Оставить заявку</h3>

                <?php if (isset($_GET['contact']) && $_GET['contact'] === 'success') : ?>
                    <div style="background:#e8f5e9;color:#2e7d32;padding:15px;border-radius:8px;margin-bottom:20px;">
                        Спасибо! Ваша заявка отправлена. Мы свяжемся с вами в ближайшее время.
                    </div>
                <?php endif; ?>

                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="stroymsk_contact">
                    <?php wp_nonce_field('stroymsk_contact', 'stroymsk_contact_nonce'); ?>

                    <div class="form-group">
                        <input type="text" name="contact_name" placeholder="Ваше имя" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="contact_phone" placeholder="+7 (___) ___-__-__" required>
                    </div>
                    <div class="form-group">
                        <textarea name="contact_message" placeholder="Опишите ваш проект или задайте вопрос" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;">Отправить заявку</button>
                    <p class="form-consent">Нажимая кнопку, вы соглашаетесь с <a href="<?php echo esc_url(get_privacy_policy_url()); ?>" style="color:var(--primary);">политикой конфиденциальности</a></p>
                </form>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
