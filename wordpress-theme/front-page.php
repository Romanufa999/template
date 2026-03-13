<?php
/**
 * Template Name: Главная страница
 * Шаблон главной страницы СтройМСК — 17 секций Titanium & Glass
 */

get_header();

$s3 = defined('STROYMSK_S3_BASE') ? STROYMSK_S3_BASE : 'https://s3.ru1.storage.beget.cloud/76ae0220f799-proficient-naida/generate';
$phone = function_exists('stroymsk_phone') ? stroymsk_phone() : '+7 (495) 123-45-67';
$phone_raw = function_exists('stroymsk_phone_raw') ? stroymsk_phone_raw() : '+74951234567';
?>

<!-- ====== 1. HERO SECTION ====== -->
<section class="hero-section scroll-reveal" id="hero" data-nav-label="Главная">
    <div class="noise-overlay"></div>

    <!-- Background Slides -->
    <div class="hero-bg">
        <div class="hero-slide active">
            <img src="<?php echo esc_url( $s3 ); ?>/gnt2qvulzn.webp" alt="Современный загородный дом с панорамными окнами и минималистичной архитектурой в лучах заката" loading="eager">
        </div>
        <div class="hero-slide">
            <img src="<?php echo esc_url( $s3 ); ?>/cib3fpwkk6.webp" alt="Элитная вилла с бассейном и панорамными окнами в вечернем освещении" loading="lazy">
        </div>
        <div class="hero-slide">
            <img src="<?php echo esc_url( $s3 ); ?>/1xtyphivog.webp" alt="Двухэтажный загородный дом с каменным фасадом и ландшафтным дизайном осенью" loading="lazy">
        </div>
    </div>

    <!-- Slide Dots -->
    <div class="hero-dots">
        <button class="hero-dot active" data-index="0" aria-label="Слайд 1"></button>
        <button class="hero-dot" data-index="1" aria-label="Слайд 2"></button>
        <button class="hero-dot" data-index="2" aria-label="Слайд 3"></button>
    </div>

    <div class="container">
        <div class="hero-content">
            <h1 class="hero__title">
                Построим дом вашей мечты —
                <span class="gradient-text">под ключ в Москве и МО</span>
            </h1>
            <p class="hero__subtitle">
                Проектирование, строительство и отделка загородных домов премиум-класса.
                Фиксированная цена, гарантия 10 лет, личный кабинет стройки.
            </p>
            <div class="hero__actions">
                <a href="#quiz" class="btn btn--primary btn--lg">Обсудить проект</a>
                <a href="tel:<?php echo esc_attr( $phone_raw ); ?>" class="btn btn--glass btn--lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.81.36 1.6.7 2.35a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.75.34 1.54.57 2.35.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span><?php echo esc_html( $phone ); ?></span>
                </a>
            </div>

            <!-- Trust Items -->
            <div class="hero-trust">
                <div class="hero-trust__item">
                    <span class="hero-trust__value">15 лет</span>
                    <span class="hero-trust__label">Опыта</span>
                </div>
                <div class="hero-trust__item">
                    <span class="hero-trust__value counter" data-counter="87">87</span>
                    <span class="hero-trust__label">Домов сдано</span>
                </div>
                <div class="hero-trust__item">
                    <span class="hero-trust__value">10 лет</span>
                    <span class="hero-trust__label">Гарантии</span>
                </div>
                <div class="hero-trust__item">
                    <span class="hero-trust__value">24/7</span>
                    <span class="hero-trust__label">Личный кабинет</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====== 2. PARTNERS MARQUEE ====== -->
<section class="partners-section" id="partners" data-nav-label="Партнёры">
    <?php
    $partners_row1 = array(
        array('name' => 'Член СРО', 'desc' => 'Допуск к генподряду'),
        array('name' => 'ИЖС Стандарт', 'desc' => 'Официальная регистрация'),
        array('name' => 'Домклик', 'desc' => 'Партнёр Сбербанка'),
        array('name' => 'ДОМ.РФ', 'desc' => 'Аккредитованный подрядчик'),
        array('name' => 'Shinglas', 'desc' => 'Официальный партнёр'),
        array('name' => 'Top Rated', 'desc' => 'Надёжный застройщик'),
        array('name' => 'Сбербанк', 'desc' => 'Ипотечный партнёр'),
        array('name' => 'ВТБ', 'desc' => 'Ипотечный партнёр'),
        array('name' => 'Альфа-Банк', 'desc' => 'Ипотечный партнёр'),
        array('name' => 'Газпромбанк', 'desc' => 'Ипотечный партнёр'),
        array('name' => 'Россельхозбанк', 'desc' => 'Ипотечный партнёр'),
        array('name' => 'Росбанк', 'desc' => 'Ипотечный партнёр'),
        array('name' => 'Knauf', 'desc' => 'Сухие смеси'),
        array('name' => 'Rockwool', 'desc' => 'Теплоизоляция'),
        array('name' => 'Технониколь', 'desc' => 'Гидроизоляция'),
        array('name' => 'Ceresit', 'desc' => 'Клеевые системы'),
        array('name' => 'Weber Vetonit', 'desc' => 'Строительные смеси'),
        array('name' => 'Пеноплэкс', 'desc' => 'Экструзионный XPS'),
    );
    $partners_row2 = array(
        array('name' => 'Holcim', 'desc' => 'Цемент и бетон'),
        array('name' => 'Isover', 'desc' => 'Утеплитель'),
        array('name' => 'Buderus', 'desc' => 'Котлы отопления'),
        array('name' => 'Viessmann', 'desc' => 'Котлы и бойлеры'),
        array('name' => 'Bosch', 'desc' => 'Инженерные системы'),
        array('name' => 'Vaillant', 'desc' => 'Отопление и ГВС'),
        array('name' => 'Danfoss', 'desc' => 'Автоматика отопления'),
        array('name' => 'Rehau', 'desc' => 'Окна и трубы'),
        array('name' => 'VEKA', 'desc' => 'Оконные системы'),
        array('name' => 'Sch&uuml;co', 'desc' => 'Премиум окна'),
        array('name' => 'KBE', 'desc' => 'Оконные профили'),
        array('name' => 'Металл Профиль', 'desc' => 'Кровля и фасады'),
        array('name' => 'Grand Line', 'desc' => 'Кровельные системы'),
        array('name' => 'Docke', 'desc' => 'Водосточные системы'),
        array('name' => 'Porotherm', 'desc' => 'Керамические блоки'),
        array('name' => 'Ytong', 'desc' => 'Газобетон'),
        array('name' => 'ABB', 'desc' => 'Электрика'),
        array('name' => 'Legrand', 'desc' => 'Электрооборудование'),
    );
    ?>
    <!-- Row 1 -->
    <div class="marquee">
        <div class="marquee-track">
            <?php
            $doubled = array_merge( $partners_row1, $partners_row1 );
            foreach ( $doubled as $p ) : ?>
                <span class="marquee-item">
                    <span class="dot"></span>
                    <?php echo esc_html( $p['name'] ); ?>
                    <span style="font-size:0.6rem;color:var(--color-zinc-700);"><?php echo esc_html( $p['desc'] ); ?></span>
                </span>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Row 2 (reverse) -->
    <div class="marquee" style="margin-top: 0.5rem;">
        <div class="marquee-track marquee-track--reverse">
            <?php
            $doubled2 = array_merge( $partners_row2, $partners_row2 );
            foreach ( $doubled2 as $p ) : ?>
                <span class="marquee-item">
                    <span class="dot"></span>
                    <?php echo esc_html( $p['name'] ); ?>
                    <span style="font-size:0.6rem;color:var(--color-zinc-700);"><?php echo $p['desc']; ?></span>
                </span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ====== 3. TECH SECTION (Marquee) ====== -->
<section class="tech-section" id="technologies" data-nav-label="Технологии">
    <div class="noise-overlay"></div>
    <div style="position:absolute;left:0;top:0;bottom:0;width:6rem;background:linear-gradient(to right,#000,transparent);z-index:10;pointer-events:none;"></div>
    <div style="position:absolute;right:0;top:0;bottom:0;width:6rem;background:linear-gradient(to left,#000,transparent);z-index:10;pointer-events:none;"></div>
    <?php
    $tech_items = array(
        array('name' => 'Кирпич', 'subtitle' => 'Классическая кладка'),
        array('name' => 'Монолит', 'subtitle' => 'Бетон и арматура'),
        array('name' => 'Газоблок', 'subtitle' => 'Лёгкий и тёплый'),
        array('name' => 'Керамоблок', 'subtitle' => 'Porotherm'),
        array('name' => 'Каркас', 'subtitle' => 'Быстрый монтаж'),
        array('name' => 'Барнхаус', 'subtitle' => 'Современный стиль'),
        array('name' => 'А-фрейм', 'subtitle' => 'Треугольный дом'),
    );
    $tripled = array_merge( $tech_items, $tech_items, $tech_items );
    ?>
    <div class="marquee-track tech-marquee" style="display:flex;gap:0;width:max-content;animation:marquee-left 50s linear infinite;">
        <?php foreach ( $tripled as $t ) : ?>
            <div style="display:flex;align-items:center;gap:1rem;padding:0.75rem 2rem;flex-shrink:0;border-right:1px solid rgba(255,255,255,0.04);">
                <div style="width:2.5rem;height:2.5rem;border-radius:0.5rem;background:rgba(255,255,255,0.04);display:flex;align-items:center;justify-content:center;color:var(--color-zinc-600);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/></svg>
                </div>
                <div>
                    <span style="display:block;font-size:0.875rem;font-weight:600;color:var(--color-zinc-400);"><?php echo esc_html( $t['name'] ); ?></span>
                    <span style="display:block;font-size:0.6875rem;color:var(--color-zinc-700);"><?php echo esc_html( $t['subtitle'] ); ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ====== 4. ADVANTAGES SECTION (Bento) ====== -->
<section class="advantages-section spotlight-section scroll-reveal" id="advantages" data-nav-label="Преимущества" data-spotlight>
    <div class="noise-overlay"></div>
    <div class="container" style="position:relative;z-index:10;">
        <div class="s-label">Почему мы</div>
        <h2 class="s-title">Пять причин <span>строить с нами</span></h2>

        <div class="bento-grid" style="grid-template-columns:repeat(5,1fr);margin-top:3.5rem;">
            <?php
            $advantages = array(
                array('icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>', 'title' => 'Личный кабинет', 'accent' => 'стройки', 'desc' => 'Фотоотчёты, акты, графики — всё онлайн.'),
                array('icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 8-6 4 6 4V8Z"/><rect width="14" height="12" x="2" y="6" rx="2" ry="2"/></svg>', 'title' => 'Камера', 'accent' => '24/7', 'desc' => 'Онлайн-трансляция со стройки в телефоне.'),
                array('icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>', 'title' => 'Готовые', 'accent' => 'решения', 'desc' => 'Помогаем выбрать лучшее. Берём рутину на себя.'),
                array('icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>', 'title' => 'Сервис', 'accent' => 'после сдачи', 'desc' => 'Обслуживаем инженерные системы дома.'),
                array('icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>', 'title' => 'Один', 'accent' => 'контакт', 'desc' => 'Менеджер, который знает каждую деталь проекта.'),
            );
            foreach ( $advantages as $i => $adv ) : ?>
                <div class="bento-card scroll-reveal" style="transition-delay:<?php echo $i * 100; ?>ms;">
                    <div class="bento-card__icon"><?php echo $adv['icon']; ?></div>
                    <div class="bento-card__title"><?php echo esc_html( $adv['title'] ); ?> <span style="background:linear-gradient(to right,var(--color-zinc-400),var(--color-zinc-700));-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;"><?php echo esc_html( $adv['accent'] ); ?></span></div>
                    <div class="bento-card__desc"><?php echo esc_html( $adv['desc'] ); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ====== 5. HOUSE PROJECTS CATALOG ====== -->
<section class="catalog-section scroll-reveal" id="catalog" data-nav-label="Каталог">
    <div class="noise-overlay"></div>
    <div class="container" style="position:relative;z-index:10;">
        <div class="s-label">Каталог проектов</div>
        <h2 class="s-title">Проекты домов — <span>от эскиза до ключей</span></h2>
        <p class="s-subtitle" style="margin-bottom:2rem;">Каждый проект адаптируется под ваш участок. Выберите стиль и мы рассчитаем стоимость.</p>

        <!-- Filters -->
        <div class="catalog-filters">
            <button class="catalog-filter active" data-filter="all">Все</button>
            <?php
            $categories = get_terms( array(
                'taxonomy'   => 'project_category',
                'hide_empty' => true,
            ) );
            if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) :
                foreach ( $categories as $cat ) : ?>
                    <button class="catalog-filter" data-filter="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></button>
                <?php endforeach;
            else : ?>
                <button class="catalog-filter" data-filter="kirpich">Кирпич</button>
                <button class="catalog-filter" data-filter="monolith">Монолит</button>
                <button class="catalog-filter" data-filter="gazobeton">Газоблок</button>
                <button class="catalog-filter" data-filter="karkasnyj">Каркас</button>
            <?php endif; ?>
        </div>

        <!-- Projects Grid -->
        <div class="catalog-grid">
            <?php
            $projects = new WP_Query( array(
                'post_type'      => 'project',
                'posts_per_page' => 12,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ) );
            if ( $projects->have_posts() ) :
                while ( $projects->have_posts() ) : $projects->the_post();
                    $terms    = get_the_terms( get_the_ID(), 'project_category' );
                    $cat_slug = $terms ? $terms[0]->slug : '';
                    $cat_name = $terms ? $terms[0]->name : '';
                    $area     = get_post_meta( get_the_ID(), '_project_area', true );
                    $price    = get_post_meta( get_the_ID(), '_project_price', true );
                    $floors   = get_post_meta( get_the_ID(), '_project_floors', true );
                    $gallery  = get_post_meta( get_the_ID(), '_project_gallery', true );
                    $thumb    = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                    ?>
                    <div class="catalog-card catalog-item visible"
                         data-category="<?php echo esc_attr( $cat_slug ); ?>"
                         data-title="<?php echo esc_attr( get_the_title() ); ?>"
                         data-description="<?php echo esc_attr( wp_strip_all_tags( get_the_content() ) ); ?>"
                         data-image="<?php echo esc_url( $thumb ); ?>"
                         <?php if ( $gallery ) : ?>data-gallery="<?php echo esc_attr( $gallery ); ?>"<?php endif; ?>
                         data-specs="<?php echo esc_attr( $area ? $area . ' м² · ' : '' ); ?><?php echo esc_attr( $floors ? $floors . ' эт.' : '' ); ?>">
                        <div class="catalog-card__image">
                            <?php if ( $thumb ) : ?>
                                <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                            <?php else : ?>
                                <div style="width:100%;height:100%;background:var(--color-zinc-900);display:flex;align-items:center;justify-content:center;color:var(--color-zinc-700);font-size:0.75rem;">Фото</div>
                            <?php endif; ?>
                        </div>
                        <?php if ( $cat_name ) : ?>
                            <span class="catalog-card__badge"><?php echo esc_html( $cat_name ); ?></span>
                        <?php endif; ?>
                        <div class="catalog-card__arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                        </div>
                        <div class="catalog-card__info">
                            <h3 class="catalog-card__title"><?php the_title(); ?></h3>
                            <div class="catalog-card__meta">
                                <?php if ( $area ) : ?><span><?php echo esc_html( $area ); ?> м²</span><?php endif; ?>
                                <?php if ( $area && $floors ) : ?><span class="sep"></span><?php endif; ?>
                                <?php if ( $floors ) : ?><span><?php echo esc_html( $floors ); ?> эт.</span><?php endif; ?>
                                <?php if ( $price ) : ?><span class="sep"></span><span><?php echo esc_html( $price ); ?></span><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            else :
                // Fallback: static demo projects
                $demo_projects = array(
                    array('name' => 'Вилла «Нова»', 'area' => '450', 'style' => 'Райт', 'img' => $s3 . '/rudvztm73t.webp'),
                    array('name' => 'Резиденция «Титан»', 'area' => '620', 'style' => 'Монолит', 'img' => $s3 . '/jel7da6ler.webp'),
                    array('name' => 'Шале «Норд»', 'area' => '380', 'style' => 'Шале', 'img' => $s3 . '/6j0g343ba8.webp'),
                    array('name' => 'Дом «Альпин»', 'area' => '520', 'style' => 'Газоблок', 'img' => $s3 . '/n3p9306g80.webp'),
                    array('name' => 'Вилла «Крит»', 'area' => '410', 'style' => 'Средиземноморский', 'img' => $s3 . '/xjnqk1k1au.webp'),
                    array('name' => 'Поместье «Форест»', 'area' => '780', 'style' => 'Кирпич', 'img' => $s3 . '/v9dbpfgt7c.webp'),
                );
                foreach ( $demo_projects as $dp ) : ?>
                    <div class="catalog-card catalog-item visible" data-category="all">
                        <div class="catalog-card__image">
                            <img src="<?php echo esc_url( $dp['img'] ); ?>" alt="<?php echo esc_attr( $dp['name'] ); ?>" loading="lazy">
                        </div>
                        <span class="catalog-card__badge"><?php echo esc_html( $dp['style'] ); ?></span>
                        <div class="catalog-card__arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                        </div>
                        <div class="catalog-card__info">
                            <h3 class="catalog-card__title"><?php echo esc_html( $dp['name'] ); ?></h3>
                            <div class="catalog-card__meta">
                                <span><?php echo esc_html( $dp['area'] ); ?> м²</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

<!-- ====== 6. CASES / VIDEO SECTION ====== -->
<section class="cases-section scroll-reveal" id="cases" data-nav-label="Наши дома">
    <div class="noise-overlay"></div>
    <div class="container" style="position:relative;z-index:10;">
        <div class="cases-header">
            <div>
                <div class="s-label">Наши дома</div>
                <h2 class="s-title">Приезжайте — <span>убедитесь лично</span></h2>
            </div>
            <div class="cases-inline-form">
                <input type="tel" class="phone-input" placeholder="+7 (___) ___-__-__">
                <button class="btn btn--primary" onclick="if(window.stroymskPhoneMask&&window.stroymskPhoneMask.isPhoneComplete(this.previousElementSibling.value)){this.parentElement.innerHTML='<span style=\'color:var(--color-emerald-400);font-size:0.875rem;\'>✓ Заявка отправлена</span>'}">
                    Хочу на объект
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </div>
        </div>

        <div class="cases-grid">
            <?php
            $cases = array(
                array( 'name' => 'Вилла «Нова»', 'loc' => 'Рублёво', 'area' => '450 м²', 'style' => 'Райт', 'img' => $s3 . '/rudvztm73t.webp', 'alt' => 'Элитная вилла в стиле Фрэнка Ллойда Райта' ),
                array( 'name' => 'Резиденция «Титан»', 'loc' => 'Миллениум Парк', 'area' => '620 м²', 'style' => 'Монолит', 'img' => $s3 . '/jel7da6ler.webp', 'alt' => 'Трёхэтажная современная резиденция с монолитным каркасом' ),
                array( 'name' => 'Шале «Норд»', 'loc' => 'Дмитровский р-н', 'area' => '380 м²', 'style' => 'Шале', 'img' => $s3 . '/6j0g343ba8.webp', 'alt' => 'Шале в альпийском стиле' ),
                array( 'name' => 'Дом «Альпин»', 'loc' => 'Новорижское ш.', 'area' => '520 м²', 'style' => 'Газоблок', 'img' => $s3 . '/n3p9306g80.webp', 'alt' => 'Двухэтажный дом из газобетонных блоков' ),
                array( 'name' => 'Вилла «Крит»', 'loc' => 'Истринский р-н', 'area' => '410 м²', 'style' => 'Средиземноморский', 'img' => $s3 . '/xjnqk1k1au.webp', 'alt' => 'Средиземноморская вилла' ),
                array( 'name' => 'Поместье «Форест»', 'loc' => 'Пятницкое ш.', 'area' => '780 м²', 'style' => 'Кирпич', 'img' => $s3 . '/v9dbpfgt7c.webp', 'alt' => 'Роскошное каменно-кирпичное поместье' ),
                array( 'name' => 'Дом «Райт»', 'loc' => 'Калужское ш.', 'area' => '340 м²', 'style' => 'Райт', 'img' => $s3 . '/b88ougmu90.webp', 'alt' => 'Загородный дом в стиле Райта' ),
                array( 'name' => 'Вилла «Прерия»', 'loc' => 'Минское ш.', 'area' => '460 м²', 'style' => 'Райт', 'img' => $s3 . '/39evj5z7ey.webp', 'alt' => 'Вилла в стиле прерий' ),
                array( 'name' => 'Дом «Барон»', 'loc' => 'Ленинградское ш.', 'area' => '290 м²', 'style' => 'Кирпич', 'img' => $s3 . '/rwzjtay7hy.webp', 'alt' => 'Классический дом из красного кирпича' ),
                array( 'name' => 'Резиденция «Империал»', 'loc' => 'Рублёво-Успенское ш.', 'area' => '550 м²', 'style' => 'Кирпич', 'img' => $s3 . '/cplxditjq5.webp', 'alt' => 'Трёхэтажный особняк из клинкерного кирпича' ),
                array( 'name' => 'Дом «Сканди»', 'loc' => 'Дмитровское ш.', 'area' => '180 м²', 'style' => 'Газоблок', 'img' => $s3 . '/cehaiwcbc2.webp', 'alt' => 'Дом из газобетона в скандинавском стиле' ),
                array( 'name' => 'Коттедж «Хюгге»', 'loc' => 'Ярославское ш.', 'area' => '220 м²', 'style' => 'Сканди', 'img' => $s3 . '/ruhczmo8y3.webp', 'alt' => 'Скандинавский коттедж с деревянным фасадом' ),
                array( 'name' => 'Бунгало «Горизонт»', 'loc' => 'Киевское ш.', 'area' => '260 м²', 'style' => 'Монолит', 'img' => $s3 . '/2tekdyknj0.webp', 'alt' => 'Одноэтажный дом с монолитным каркасом' ),
                array( 'name' => 'Дом «Ранчо»', 'loc' => 'Симферопольское ш.', 'area' => '310 м²', 'style' => 'Монолит', 'img' => $s3 . '/m6imzfgc84.webp', 'alt' => 'Дом в стиле ранчо с каменной отделкой' ),
            );
            foreach ( $cases as $i => $c ) : ?>
                <div class="case-card scroll-reveal" style="transition-delay:<?php echo $i * 100; ?>ms;" data-full-image="<?php echo esc_url( $c['img'] ); ?>">
                    <div class="case-card__image">
                        <img src="<?php echo esc_url( $c['img'] ); ?>" alt="<?php echo esc_attr( $c['alt'] ); ?>" loading="lazy">
                    </div>
                    <span class="case-card__style"><?php echo esc_html( $c['style'] ); ?></span>
                    <div class="case-card__arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                    </div>
                    <div class="case-card__info">
                        <h3 class="case-card__title"><?php echo esc_html( $c['name'] ); ?></h3>
                        <div class="case-card__meta">
                            <div class="case-card__meta-left">
                                <span><?php echo esc_html( $c['loc'] ); ?></span>
                                <span style="width:1px;height:0.75rem;background:rgba(255,255,255,0.1);"></span>
                                <span><?php echo esc_html( $c['area'] ); ?></span>
                            </div>
                            <a href="#cases" class="case-card__link" onclick="event.stopPropagation();">
                                Посетить объект
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Stats -->
        <div class="cases-stats scroll-reveal" style="transition-delay:300ms;">
            <div class="cases-stats__item">
                <div class="cases-stats__value counter" data-counter="150+">150+</div>
                <div class="cases-stats__label">Домов</div>
            </div>
            <div class="cases-stats__item">
                <div class="cases-stats__value counter" data-counter="15">15</div>
                <div class="cases-stats__label">Лет</div>
            </div>
            <div class="cases-stats__item">
                <div class="cases-stats__value">100%</div>
                <div class="cases-stats__label">В срок</div>
            </div>
        </div>
    </div>
</section>

<!-- ====== 7. VIDEO REPORTS ====== -->
<section class="video-section scroll-reveal" id="video-reports" data-nav-label="Видео">
    <div class="noise-overlay"></div>
    <div class="container" style="position:relative;z-index:10;">
        <div class="s-label">Видеоотчёты</div>
        <h2 class="s-title">Строим — <span>снимаем</span></h2>
        <p class="s-subtitle" style="margin-bottom:2rem;">Видеообзоры реальных объектов на каждом этапе строительства.</p>

        <div class="video-grid">
            <?php
            $videos = array(
                array( 'title' => 'Презентация компании', 'desc' => 'Построим дом вашей мечты — от проекта до сдачи ключей.', 'thumb' => 'https://pic.rtbcdn.ru/video/2026-02-02/fb/e8/fbe86f87cd35cc338a449f1a1dba30fa.jpg', 'url' => 'https://rutube.ru/video/3976064f068d0d7e1c59b3860132a3f7/', 'duration' => '02:19', 'episode' => 'Презентация' ),
                array( 'title' => 'Каркасный дом проект Джоуль', 'desc' => 'Каркасный дом на стадии готового каркаса и утепления.', 'thumb' => 'https://pic.rtbcdn.ru/video/2026-02-02/3e/1a/3e1ab63a1f7c5abe737e86190ce921ee.jpg', 'url' => 'https://rutube.ru/video/c1533ee7b0d18888ced2805ea4f728cd/', 'duration' => '04:54', 'episode' => 'Обзор' ),
                array( 'title' => 'Тёплые полы в доме из газобетона', 'desc' => 'Монтаж водяных тёплых полов. Нюансы укладки.', 'thumb' => 'https://pic.rtbcdn.ru/video/2026-02-02/08/b6/08b62feaccb565e766b0385e02a3493a.jpg', 'url' => 'https://rutube.ru/video/e0c8989554e33646698ae044bf56fc1c/', 'duration' => '04:38', 'episode' => 'Технология' ),
                array( 'title' => 'Барнхаус Бибери — тёплый контур', 'desc' => 'Обзор барнхауса на стадии тёплого контура.', 'thumb' => 'https://pic.rtbcdn.ru/video/2026-02-02/1c/c9/1cc946f655daf4979199e74f5ff86c26.jpg', 'url' => 'https://rutube.ru/video/c3093cdcc9c195e576c88d3b48e0cfbb/', 'duration' => '04:12', 'episode' => 'Обзор' ),
                array( 'title' => 'Кирпичный дом — отзыв заказчика', 'desc' => 'Отзыв клиента о строительстве кирпичного дома.', 'thumb' => 'https://pic.rtbcdn.ru/video/2026-02-02/fb/e8/fbe86f87cd35cc338a449f1a1dba30fa.jpg', 'url' => 'https://rutube.ru/video/3976064f068d0d7e1c59b3860132a3f7/', 'duration' => '02:35', 'episode' => 'Отзыв' ),
                array( 'title' => 'Дом из газобетона — от фундамента', 'desc' => 'Строительство дома из газобетона с нуля.', 'thumb' => 'https://pic.rtbcdn.ru/video/2026-02-02/3e/1a/3e1ab63a1f7c5abe737e86190ce921ee.jpg', 'url' => 'https://rutube.ru/video/c1533ee7b0d18888ced2805ea4f728cd/', 'duration' => '05:17', 'episode' => 'Процесс' ),
            );
            foreach ( $videos as $i => $v ) : ?>
                <div class="video-card scroll-reveal" style="transition-delay:<?php echo $i * 100; ?>ms;" data-video-url="<?php echo esc_url( $v['url'] ); ?>">
                    <div class="video-card__thumb">
                        <img src="<?php echo esc_url( $v['thumb'] ); ?>" alt="<?php echo esc_attr( $v['title'] ); ?>" loading="lazy">
                        <div class="video-card__play">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                        </div>
                        <span style="position:absolute;bottom:0.5rem;right:0.5rem;z-index:5;font-family:var(--font-label);font-size:0.625rem;color:var(--color-zinc-400);background:rgba(0,0,0,0.6);padding:0.125rem 0.375rem;border-radius:0.25rem;"><?php echo esc_html( $v['duration'] ); ?></span>
                        <span style="position:absolute;top:0.5rem;left:0.5rem;z-index:5;font-family:var(--font-label);font-size:0.5625rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--color-zinc-400);background:rgba(0,0,0,0.6);padding:0.125rem 0.5rem;border-radius:9999px;"><?php echo esc_html( $v['episode'] ); ?></span>
                    </div>
                    <div class="video-card__info">
                        <h3 class="video-card__title"><?php echo esc_html( $v['title'] ); ?></h3>
                        <p class="video-card__desc"><?php echo esc_html( $v['desc'] ); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ====== 8. JOURNEY STAGES (Timeline) ====== -->
<section class="journey-section scroll-reveal" id="journey" data-nav-label="Этапы">
    <div class="noise-overlay"></div>
    <div class="container" style="position:relative;z-index:10;">
        <div class="s-label">Путь к дому</div>
        <h2 class="s-title">От идеи — <span>до ключей</span></h2>
        <p class="s-subtitle" style="margin-bottom:2rem;">Прозрачный процесс на каждом этапе. Вы всегда знаете, что происходит.</p>

        <div class="journey-timeline scrollbar-hide">
            <?php
            $steps = array(
                array( 'num' => '01', 'title' => 'Знакомство', 'desc' => 'Первая встреча. Обсуждаем ваши ожидания, бюджет и видение идеального дома.', 'tag' => 'Замеры' ),
                array( 'num' => '02', 'title' => 'Проектирование', 'desc' => 'Разработка концепции, 3D визуализация и полный пакет рабочих чертежей.', 'tag' => 'Концепт · 3D' ),
                array( 'num' => '03', 'title' => 'Строительство', 'desc' => 'Возведение дома. Все этапы, чек-листы и документы фиксируются цифрово.', 'tag' => 'Акты · Фотоотчёты' ),
                array( 'num' => '04', 'title' => 'Приёмка', 'desc' => 'Тщательная проверка объекта нашим внутренним независимым технадзором.', 'tag' => 'Технадзор' ),
                array( 'num' => '05', 'title' => 'Ключи', 'desc' => 'Торжественная передача ключей, гарантийных сертификатов и всей документации.', 'tag' => 'Документы' ),
                array( 'num' => '06', 'title' => 'Сервис', 'desc' => 'Регулярный чекап систем и обслуживание. Мы всегда на связи после переезда.', 'tag' => 'Поддержка' ),
            );
            foreach ( $steps as $i => $step ) : ?>
                <div class="journey-step scroll-reveal" style="transition-delay:<?php echo $i * 120; ?>ms;">
                    <div class="journey-step__number"><?php echo esc_html( $step['num'] ); ?></div>
                    <h3 class="journey-step__title"><?php echo esc_html( $step['title'] ); ?></h3>
                    <p class="journey-step__desc"><?php echo esc_html( $step['desc'] ); ?></p>
                    <span class="journey-step__duration"><?php echo esc_html( $step['tag'] ); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ====== 9. CABINET SECTION ====== -->
<section class="cabinet-section scroll-reveal" id="cabinet" data-nav-label="Кабинет">
    <div class="noise-overlay"></div>
    <div class="container" style="position:relative;z-index:10;">
        <div class="s-label">Цифровая стройка</div>
        <h2 class="s-title">Личный кабинет — <span>контроль 24/7</span></h2>
        <p class="s-subtitle" style="margin-bottom:2rem;">Фотоотчёты, акты, чек-листы и графики — всё в одном месте. Не нужно ездить на стройку.</p>

        <div class="cabinet-layout">
            <div class="cabinet-tabs" role="tablist">
                <button class="tab-button active" data-tab="photos" role="tab" aria-selected="true">
                    <span class="tab-button__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                    </span>
                    Фотоотчёты
                </button>
                <button class="tab-button" data-tab="checklists" role="tab" aria-selected="false">
                    <span class="tab-button__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    </span>
                    Чек-листы
                </button>
                <button class="tab-button" data-tab="documents" role="tab" aria-selected="false">
                    <span class="tab-button__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    </span>
                    Документы
                </button>
                <button class="tab-button" data-tab="schedule" role="tab" aria-selected="false">
                    <span class="tab-button__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                    Графики
                </button>
            </div>
            <div>
                <div class="tab-panel cabinet-preview active" data-tab-panel="photos">
                    <img src="<?php echo esc_url( $s3 ); ?>/cabinet-photos.webp" alt="Личный кабинет — фотоотчёты стройки" loading="lazy">
                </div>
                <div class="tab-panel cabinet-preview" data-tab-panel="checklists" hidden>
                    <img src="<?php echo esc_url( $s3 ); ?>/cabinet-checklists.webp" alt="Личный кабинет — чек-листы" loading="lazy">
                </div>
                <div class="tab-panel cabinet-preview" data-tab-panel="documents" hidden>
                    <img src="<?php echo esc_url( $s3 ); ?>/cabinet-documents.webp" alt="Личный кабинет — документы" loading="lazy">
                </div>
                <div class="tab-panel cabinet-preview" data-tab-panel="schedule" hidden>
                    <img src="<?php echo esc_url( $s3 ); ?>/cabinet-schedule.webp" alt="Личный кабинет — график" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====== 10. TEAM SECTION ====== -->
<section class="team-section scroll-reveal" id="team" data-nav-label="Команда">
    <div class="noise-overlay"></div>
    <div class="container" style="position:relative;z-index:10;">
        <div class="s-label">Команда</div>
        <h2 class="s-title">Люди, которым <span>вы доверяете</span></h2>

        <div class="team-layout" style="margin-top:2.5rem;">
            <div class="team-photo-wrapper">
                <img src="https://s3.ru1.storage.beget.cloud/76ae0220f799-proficient-naida/upload/177278836252721.jpeg" alt="" class="team-photo" loading="lazy">
            </div>
            <div class="team-info">
                <?php
                $team = array(
                    array( 'name' => 'Мария Сергеевна Сас', 'role' => 'Менеджер продаж', 'photo' => 'https://s3.ru1.storage.beget.cloud/76ae0220f799-proficient-naida/upload/177278836252721.jpeg', 'bio' => 'Помогает клиентам на каждом этапе — от первого звонка до подписания договора.' ),
                    array( 'name' => 'Мухин Иван Владимирович', 'role' => 'Инженер-проектировщик', 'photo' => 'https://s3.ru1.storage.beget.cloud/76ae0220f799-proficient-naida/upload/177278836643805.jpeg', 'bio' => 'Разрабатывает конструктивные решения. Более 200 проектов за 12 лет.' ),
                    array( 'name' => 'Алла Викторовна Илюшина', 'role' => 'Архитектор', 'photo' => 'https://s3.ru1.storage.beget.cloud/76ae0220f799-proficient-naida/upload/177278830161303.jpeg', 'bio' => 'Создаёт концептуальные проекты, 3D-визуализации и рабочие чертежи.' ),
                    array( 'name' => 'Анатолий Петрович Новоселов', 'role' => 'Производитель работ', 'photo' => 'https://s3.ru1.storage.beget.cloud/76ae0220f799-proficient-naida/upload/177278829433752.jpeg', 'bio' => 'Контролирует строительство на объекте. Опыт 15+ лет в сфере ИЖС.' ),
                    array( 'name' => 'Русецкий Игорь Юрьевич', 'role' => 'Инженер-проектировщик', 'photo' => 'https://s3.ru1.storage.beget.cloud/76ae0220f799-proficient-naida/upload/177278836252721.jpeg', 'bio' => 'Специализируется на фундаментах и несущих конструкциях.' ),
                    array( 'name' => 'Матвиенко Филипп Анатольевич', 'role' => 'Дизайнер', 'photo' => 'https://s3.ru1.storage.beget.cloud/76ae0220f799-proficient-naida/upload/177278836643805.jpeg', 'bio' => 'Создаёт интерьеры, которые сочетают эстетику и функциональность.' ),
                    array( 'name' => 'Ефимов Олег Борисович', 'role' => 'Производитель работ', 'photo' => 'https://s3.ru1.storage.beget.cloud/76ae0220f799-proficient-naida/upload/177278830161303.jpeg', 'bio' => 'Руководит бригадами на строительных площадках.' ),
                );
                ?>
                <div style="display:none;">
                    <?php foreach ( $team as $i => $member ) : ?>
                        <div class="team-member"
                             data-photo="<?php echo esc_url( $member['photo'] ); ?>"
                             data-name="<?php echo esc_attr( $member['name'] ); ?>"
                             data-role="<?php echo esc_attr( $member['role'] ); ?>"
                             data-bio="<?php echo esc_attr( $member['bio'] ); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="team-name"><?php echo esc_html( $team[0]['name'] ); ?></div>
                <div class="team-role"><?php echo esc_html( $team[0]['role'] ); ?></div>
                <div class="team-bio"><?php echo esc_html( $team[0]['bio'] ); ?></div>
                <div class="team-nav">
                    <button class="team-prev" aria-label="Предыдущий">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <span class="team-counter">1 / <?php echo count( $team ); ?></span>
                    <button class="team-next" aria-label="Следующий">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                    <div class="team-dots">
                        <?php for ( $d = 0; $d < count( $team ); $d++ ) : ?>
                            <button class="team-dot<?php echo $d === 0 ? ' active' : ''; ?>" data-index="<?php echo $d; ?>" aria-label="Участник <?php echo $d + 1; ?>"></button>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====== 11. INDIVIDUAL DESIGN ====== -->
<section class="design-section scroll-reveal" id="individual-design" data-nav-label="Проектирование">
    <div class="noise-overlay"></div>
    <div class="container" style="position:relative;z-index:10;">
        <div class="s-label">Проектирование</div>
        <h2 class="s-title">Индивидуальный проект — <span>ваш дом, ваши правила</span></h2>
        <p class="s-subtitle" style="margin-bottom:2rem;">Архитектурный проект с нуля под ваш участок, стиль жизни и бюджет. От эскиза до рабочих чертежей.</p>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-top:2.5rem;">
            <div>
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <?php
                    $design_features = array(
                        array( 'title' => 'Индивидуальный эскиз', 'desc' => '2-3 варианта концепции под ваш участок, стиль жизни и бюджет' ),
                        array( 'title' => '3D-визуализация', 'desc' => 'Фотореалистичные рендеры и VR-тур — увидите дом до начала стройки' ),
                        array( 'title' => 'Рабочая документация', 'desc' => 'Полный комплект чертежей: АР, КР, инженерные сети, спецификации' ),
                        array( 'title' => 'BIM-модель', 'desc' => 'Цифровой двойник дома — точная стройка, экономия до 15% бюджета' ),
                    );
                    foreach ( $design_features as $f ) : ?>
                        <div style="padding:1.25rem;border-radius:var(--radius-xl);background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.06);">
                            <h4 style="font-size:0.9375rem;font-weight:600;color:var(--color-white);margin-bottom:0.25rem;"><?php echo esc_html( $f['title'] ); ?></h4>
                            <p style="font-size:0.8125rem;color:var(--color-zinc-500);line-height:1.5;"><?php echo esc_html( $f['desc'] ); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Stats -->
                <div style="display:flex;gap:2rem;margin-top:1.5rem;">
                    <div>
                        <div style="font-family:var(--font-label);font-size:1.5rem;font-weight:700;color:var(--color-white);">350+</div>
                        <div style="font-family:var(--font-label);font-size:0.5625rem;color:var(--color-zinc-600);text-transform:uppercase;letter-spacing:0.15em;">проектов</div>
                    </div>
                    <div>
                        <div style="font-family:var(--font-label);font-size:1.5rem;font-weight:700;color:var(--color-white);">28</div>
                        <div style="font-family:var(--font-label);font-size:0.5625rem;color:var(--color-zinc-600);text-transform:uppercase;letter-spacing:0.15em;">специалистов</div>
                    </div>
                    <div>
                        <div style="font-family:var(--font-label);font-size:1.5rem;font-weight:700;color:var(--color-white);">30</div>
                        <div style="font-family:var(--font-label);font-size:0.5625rem;color:var(--color-zinc-600);text-transform:uppercase;letter-spacing:0.15em;">дней — средний срок</div>
                    </div>
                </div>
            </div>
            <div class="design-card">
                <img src="<?php echo esc_url( $s3 ); ?>/rudvztm73t.webp" alt="Индивидуальное проектирование дома" loading="lazy">
                <div class="design-card__overlay">
                    <div class="design-card__title">Авторский проект</div>
                    <div class="design-card__desc">Каждый дом уникален — как и его владелец</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====== 12. SERVICES PERIODIC TABLE ====== -->
<section class="periodic-section scroll-reveal" id="service" data-nav-label="Сервис">
    <div class="noise-overlay"></div>
    <div class="container" style="position:relative;z-index:10;">
        <div class="s-label">Долгосрочные отношения</div>
        <h2 class="s-title">Сервис после <span>сдачи ключей</span></h2>
        <p class="s-subtitle" style="margin-bottom:2rem;">Обслуживаем инженерные системы. Вам не нужно ни о чём заботиться.</p>

        <div class="periodic-layout" style="margin-top:2.5rem;">
            <div>
                <div class="periodic-grid periodic-table service-periodic">
                    <?php
                    $tiles = array(
                        array( 'sym' => 'Ht', 'num' => 1, 'label' => 'Отопление', 'tooltip' => 'Котлы, радиаторы, тёплые полы — регулярное ТО и диагностика', 'photo' => $s3 . '/lhfbiyszx3.webp' ),
                        array( 'sym' => 'Vn', 'num' => 2, 'label' => 'Вентиляция', 'tooltip' => 'Приточные установки, фильтры — замена и чистка раз в сезон', 'photo' => $s3 . '/wr3idmsfq6.webp' ),
                        array( 'sym' => 'Ok', 'num' => 3, 'label' => 'Окна', 'tooltip' => 'Регулировка фурнитуры, замена уплотнителей, смазка механизмов', 'photo' => $s3 . '/kgf7w5xnxs.webp' ),
                        array( 'sym' => 'El', 'num' => 4, 'label' => 'Электрика', 'tooltip' => 'Автоматы, УЗО, проводка — проверка и замена по регламенту', 'photo' => $s3 . '/awyo05oap9.webp' ),
                        array( 'sym' => 'Wd', 'num' => 5, 'label' => 'Водоснабж.', 'tooltip' => 'Трубы, фильтры, насосы — замена картриджей и давление', 'photo' => $s3 . '/0avuzafe54.webp' ),
                        array( 'sym' => 'Kn', 'num' => 6, 'label' => 'Канализация', 'tooltip' => 'Септик, дренаж, ливнёвка — промывка и инспекция', 'photo' => $s3 . '/ea3mq0hqfl.webp' ),
                        array( 'sym' => 'Sm', 'num' => 7, 'label' => 'Умный дом', 'tooltip' => 'Контроллеры, датчики, сценарии — обновление и настройка', 'photo' => $s3 . '/8nwwo0wt18.webp' ),
                        array( 'sym' => 'Ac', 'num' => 8, 'label' => 'Кондиц.', 'tooltip' => 'Сплит-системы — заправка фреоном и чистка фильтров', 'photo' => $s3 . '/8ehsvyx099.webp' ),
                        array( 'sym' => 'Fs', 'num' => 9, 'label' => 'Фасад', 'tooltip' => 'Штукатурка, покраска, плитка — осмотр и ремонт повреждений', 'photo' => $s3 . '/ljcv40g44o.webp' ),
                        array( 'sym' => 'Lk', 'num' => 10, 'label' => 'Ландшафт', 'tooltip' => 'Газон, полив, освещение — сезонное обслуживание', 'photo' => $s3 . '/huvtf9i8tc.webp' ),
                        array( 'sym' => 'Gd', 'num' => 11, 'label' => 'Ворота', 'tooltip' => 'Автоматика, ролеты, шлагбаумы — смазка и диагностика', 'photo' => $s3 . '/klg6uneinz.webp' ),
                        array( 'sym' => 'Rf', 'num' => 12, 'label' => 'Кровля', 'tooltip' => 'Желоба, снегозадержание — осмотр после каждой зимы', 'photo' => $s3 . '/omfoo6p5w0.webp' ),
                    );
                    foreach ( $tiles as $i => $tile ) : ?>
                        <div class="periodic-element service-element<?php echo $i === 0 ? ' active' : ''; ?>"
                             tabindex="0"
                             data-title="<?php echo esc_attr( $tile['label'] ); ?>"
                             data-description="<?php echo esc_attr( $tile['tooltip'] ); ?>"
                             data-photo="<?php echo esc_url( $tile['photo'] ); ?>">
                            <span class="periodic-element__number"><?php echo esc_html( $tile['num'] ); ?></span>
                            <span class="periodic-element__symbol"><?php echo esc_html( $tile['sym'] ); ?></span>
                            <span class="periodic-element__name"><?php echo esc_html( $tile['label'] ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Info panel -->
                <div style="margin-top:1rem;padding:1rem;border-radius:var(--radius-xl);background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
                    <div style="font-size:0.875rem;font-weight:600;color:var(--color-white);margin-bottom:0.25rem;" class="service-detail-title"><?php echo esc_html( $tiles[0]['label'] ); ?></div>
                    <div style="font-size:0.8125rem;color:var(--color-zinc-500);" class="service-detail-description"><?php echo esc_html( $tiles[0]['tooltip'] ); ?></div>
                </div>
            </div>
            <div class="service-detail">
                <img src="<?php echo esc_url( $tiles[0]['photo'] ); ?>" alt="<?php echo esc_attr( $tiles[0]['label'] ); ?>" class="service-detail-photo element-photo" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- ====== 13. CONSULTATION FORM ====== -->
<section class="consult-section scroll-reveal" id="contacts" data-nav-label="Консультация">
    <div class="noise-overlay"></div>
    <div class="container" style="position:relative;z-index:10;">
        <div class="s-label">Консультация</div>
        <h2 class="s-title">Обсудите проект — <span>бесплатно</span></h2>

        <div class="consult-layout" style="margin-top:2.5rem;">
            <div class="consult-info">
                <p class="s-subtitle">Расскажите о вашей задаче — архитектор перезвонит и предложит оптимальное решение.</p>
                <div class="consult-features">
                    <?php
                    $benefits = array(
                        'Бесплатная консультация архитектора',
                        'Расчёт стоимости за 24 часа',
                        'Индивидуальный проект под ваш участок',
                        'Фиксированная цена в договоре',
                    );
                    foreach ( $benefits as $b ) : ?>
                        <div class="consult-feature">
                            <svg class="consult-feature__icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span><?php echo esc_html( $b ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="consultation-form consult-form">
                <!-- Service Options -->
                <div class="consult-services">
                    <button type="button" class="service-option" data-service="Строительство дома">Строительство дома</button>
                    <button type="button" class="service-option" data-service="Проектирование">Проектирование</button>
                    <button type="button" class="service-option" data-service="Реконструкция">Реконструкция</button>
                    <button type="button" class="service-option" data-service="Расчёт сметы">Расчёт сметы</button>
                </div>

                <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-form-id="consultation_form">
                    <input type="hidden" name="action" value="stroymsk_contact">
                    <input type="hidden" name="selected_services" value="">
                    <?php wp_nonce_field( 'stroymsk_contact', 'stroymsk_contact_nonce' ); ?>

                    <div class="form-group">
                        <input type="text" name="contact_name" placeholder="Ваше имя" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="contact_phone" class="phone-input" placeholder="+7 (___) ___-__-__" required>
                        <div class="phone-error"></div>
                    </div>
                    <div class="form-group">
                        <textarea name="contact_message" placeholder="Опишите ваш проект или задайте вопрос" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn--primary btn--block btn--lg">Получить консультацию</button>
                    <p class="form-consent">Нажимая кнопку, вы соглашаетесь с <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">политикой конфиденциальности</a></p>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ====== 14. QUIZ SECTION ====== -->
<section class="quiz-section scroll-reveal" id="quiz" data-nav-label="Квиз">
    <div class="noise-overlay"></div>
    <div class="container" style="position:relative;z-index:10;">
        <div class="s-label">Подберём проект</div>
        <h2 class="s-title">Ответьте на 6 вопросов — <span>получите подборку</span></h2>
        <p class="s-subtitle" style="margin-bottom:2rem;">За 2 минуты подберём проекты под ваши пожелания. Бонус — подарок при заключении договора.</p>

        <div class="quiz-container quiz">
            <!-- Progress -->
            <div class="quiz-progress">
                <div class="quiz-progress__bar">
                    <div class="quiz-progress__fill quiz-progress-bar"></div>
                </div>
                <div class="quiz-step-counter">Шаг 1 из 7</div>
            </div>

            <!-- Step 1: Style -->
            <div class="quiz-step active" data-type="single">
                <h3 class="quiz-step__title">Какой стиль дома вам ближе?</h3>
                <div class="quiz-options">
                    <div class="quiz-option" data-value="Классика"><span class="quiz-option__icon">🏛️</span><span>Классика</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="Модерн"><span class="quiz-option__icon">🏢</span><span>Модерн</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="Скандинавский"><span class="quiz-option__icon">🌲</span><span>Скандинавский</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="Хай-тек"><span class="quiz-option__icon">⚡</span><span>Хай-тек</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="Барнхаус"><span class="quiz-option__icon">🏚️</span><span>Барнхаус</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="Шале"><span class="quiz-option__icon">🏔️</span><span>Шале</span><span class="quiz-option__check"></span></div>
                </div>
            </div>

            <!-- Step 2: Floors -->
            <div class="quiz-step" data-type="single" style="display:none;">
                <h3 class="quiz-step__title">Сколько этажей?</h3>
                <div class="quiz-options">
                    <div class="quiz-option" data-value="1 этаж"><span>Один этаж</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="2 этажа"><span>Два этажа</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="С мансардой"><span>С мансардой</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="3 этажа"><span>Три этажа</span><span class="quiz-option__check"></span></div>
                </div>
            </div>

            <!-- Step 3: Area -->
            <div class="quiz-step" data-type="single" style="display:none;">
                <h3 class="quiz-step__title">Какая площадь вам нужна?</h3>
                <div class="quiz-options">
                    <div class="quiz-option" data-value="до 120 м²"><span>до 120 м²</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="120–200 м²"><span>120–200 м²</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="200–350 м²"><span>200–350 м²</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="от 350 м²"><span>от 350 м²</span><span class="quiz-option__check"></span></div>
                </div>
            </div>

            <!-- Step 4: Land -->
            <div class="quiz-step" data-type="single" style="display:none;">
                <h3 class="quiz-step__title">Есть ли участок?</h3>
                <div class="quiz-options">
                    <div class="quiz-option" data-value="Да, есть"><span>Да, есть участок</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="В процессе выбора"><span>В процессе выбора</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="Пока нет"><span>Пока нет</span><span class="quiz-option__check"></span></div>
                </div>
            </div>

            <!-- Step 5: Budget -->
            <div class="quiz-step" data-type="single" style="display:none;">
                <h3 class="quiz-step__title">Ориентировочный бюджет?</h3>
                <div class="quiz-options">
                    <div class="quiz-option" data-value="до 10 млн"><span>до 10 млн</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="10–20 млн"><span>10–20 млн</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="20–40 млн"><span>20–40 млн</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="от 40 млн"><span>от 40 млн</span><span class="quiz-option__check"></span></div>
                </div>
            </div>

            <!-- Step 6: Gift -->
            <div class="quiz-step" data-type="single" style="display:none;">
                <h3 class="quiz-step__title">Выберите подарок!</h3>
                <div class="quiz-options">
                    <div class="quiz-option" data-value="Мойка высокого давления"><span class="quiz-option__icon">💧</span><span>Мойка высокого давления</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="Газонокосилка"><span class="quiz-option__icon">🌿</span><span>Газонокосилка</span><span class="quiz-option__check"></span></div>
                    <div class="quiz-option" data-value="Робот-пылесос"><span class="quiz-option__icon">🤖</span><span>Робот-пылесос</span><span class="quiz-option__check"></span></div>
                </div>
            </div>

            <!-- Step 7: Contact Info -->
            <div class="quiz-step" data-type="contact" style="display:none;">
                <h3 class="quiz-step__title">Куда отправить подборку?</h3>
                <div style="max-width:24rem;">
                    <div class="form-group">
                        <input type="text" name="quiz_name" placeholder="Ваше имя">
                    </div>
                    <div class="form-group">
                        <input type="tel" name="quiz_phone" class="phone-input" placeholder="+7 (___) ___-__-__" required>
                        <div class="phone-error"></div>
                    </div>
                </div>
            </div>

            <!-- Success State -->
            <div class="quiz-success quiz-result">
                <div class="quiz-success__icon">🎉</div>
                <h3 class="quiz-success__title">Спасибо!</h3>
                <p class="quiz-success__text">Мы подготовим подборку проектов и свяжемся с вами в ближайшее время.</p>
            </div>

            <div class="quiz-error"></div>

            <!-- Navigation -->
            <div class="quiz-nav">
                <button class="quiz-prev quiz-back" style="display:none;">← Назад</button>
                <div style="flex:1;"></div>
                <button class="quiz-next quiz-forward" style="display:none;">Далее →</button>
                <button class="quiz-submit" style="display:none;">Получить подборку</button>
            </div>
        </div>
    </div>
</section>

<!-- ====== 15. FAQ SECTION ====== -->
<section class="faq-section scroll-reveal" id="faq" data-nav-label="FAQ">
    <div class="noise-overlay"></div>
    <div class="container" style="position:relative;z-index:10;">
        <div style="text-align:center;">
            <div class="s-label" style="justify-content:center;">Частые вопросы</div>
            <h2 class="s-title">Ответы, которые <span>снимают сомнения</span></h2>
        </div>

        <div class="faq-list" style="max-width:48rem;margin:3.5rem auto 0;">
            <?php
            $faqs = array(
                array(
                    'q' => 'Как формируется стоимость?',
                    'a' => 'Фиксированная смета в договоре. Никаких «доплат» и «непредвиденных расходов». Цена включает все работы и материалы. Мы зарабатываем на объёме закупок, а не на наценках к клиенту.',
                ),
                array(
                    'q' => 'А если сроки сорвутся?',
                    'a' => 'В договоре прописана фиксированная дата сдачи и штрафные санкции за каждый день просрочки. За 87 построенных домов мы ни разу не нарушили сроки. Средний запас — 2 недели до дедлайна.',
                ),
                array(
                    'q' => 'Что входит в гарантию?',
                    'a' => '10 лет гарантии на конструктив (фундамент, стены, кровля) и 5 лет на инженерные системы. Гарантийные случаи устраняем за свой счёт в течение 48 часов после обращения.',
                ),
                array(
                    'q' => 'Можно ли менять проект в процессе?',
                    'a' => 'Да, до этапа отделки изменения возможны. Архитектор пересчитает стоимость и сроки, и вы примете решение. Мы гибкие, но всегда предупреждаем о последствиях для бюджета и графика.',
                ),
                array(
                    'q' => 'Нужно ли мне ездить на стройку?',
                    'a' => 'Нет. Личный кабинет, еженедельные фотоотчёты и камеры 24/7 дают полный контроль удалённо. За весь период строительства вам нужно будет приехать максимум 2–3 раза — на ключевые приёмки.',
                ),
                array(
                    'q' => 'Работаете ли вы с ипотекой?',
                    'a' => 'Да, у нас есть партнёрские программы с ведущими банками. Помогаем оформить ипотеку на строительство загородного дома на выгодных условиях. Консультация бесплатна.',
                ),
            );
            foreach ( $faqs as $i => $faq ) : ?>
                <div class="faq-item scroll-reveal" style="transition-delay:<?php echo $i * 60; ?>ms;">
                    <div class="faq-question" role="button" tabindex="0" aria-expanded="false">
                        <span class="faq-question__text"><?php echo esc_html( $faq['q'] ); ?></span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </div>
                    <div class="faq-answer" style="max-height:0;opacity:0;">
                        <div class="faq-answer__inner"><?php echo esc_html( $faq['a'] ); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ====== 16. FINAL CTA ====== -->
<section class="final-cta scroll-reveal" id="final-cta">
    <div class="noise-overlay"></div>
    <div class="container" style="position:relative;z-index:10;">
        <div class="final-cta__inner">
            <!-- Decorative orbits -->
            <div class="cta-orbit-ring" style="width:40rem;height:40rem;top:-10rem;left:-10rem;"></div>
            <div class="cta-orbit-ring" style="width:30rem;height:30rem;bottom:-8rem;right:-8rem;animation-direction:reverse;"></div>

            <h2 class="final-cta__title">Готовы начать?</h2>
            <p class="final-cta__subtitle">Оставьте номер — архитектор перезвонит в течение 30 минут и ответит на все вопросы.</p>

            <form class="final-cta__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-form-id="final_cta">
                <input type="hidden" name="action" value="stroymsk_contact">
                <?php wp_nonce_field( 'stroymsk_contact', 'stroymsk_contact_nonce' ); ?>
                <input type="tel" name="contact_phone" class="phone-input" placeholder="+7 (___) ___-__-__" required>
                <button type="submit" class="btn btn--primary">
                    Жду звонка
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>

            <div class="final-cta__trust">
                <div class="final-cta__trust-item">
                    <svg class="final-cta__trust-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>Бесплатная консультация</span>
                </div>
                <div class="final-cta__trust-item">
                    <svg class="final-cta__trust-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>Расчёт за 24 часа</span>
                </div>
                <div class="final-cta__trust-item">
                    <svg class="final-cta__trust-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>Никакого спама</span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
