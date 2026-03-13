<?php
/**
 * Single Project template
 *
 * @package StroyMSK
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

    <?php
    // Project meta
    $area       = get_post_meta( get_the_ID(), 'project_area', true );
    $floors     = get_post_meta( get_the_ID(), 'project_floors', true );
    $bedrooms   = get_post_meta( get_the_ID(), 'project_bedrooms', true );
    $bathrooms  = get_post_meta( get_the_ID(), 'project_bathrooms', true );
    $technology = get_post_meta( get_the_ID(), 'project_technology', true );
    $location   = get_post_meta( get_the_ID(), 'project_location', true );
    $style      = get_post_meta( get_the_ID(), 'project_style', true );
    $plan1      = get_post_meta( get_the_ID(), 'project_plan1', true );
    $plan2      = get_post_meta( get_the_ID(), 'project_plan2', true );
    $plan3      = get_post_meta( get_the_ID(), 'project_plan3', true );
    $gallery    = get_post_meta( get_the_ID(), 'project_gallery', true );
    $terms      = get_the_terms( get_the_ID(), 'project_category' );
    ?>

    <!-- ====== Project Hero ====== -->
    <section class="project-hero">
        <div class="container">
            <div class="project-hero__inner">
                <!-- Breadcrumbs -->
                <nav class="breadcrumbs" aria-label="Хлебные крошки">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="breadcrumbs__link">Главная</a>
                    <span class="breadcrumbs__sep">/</span>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>" class="breadcrumbs__link">Проекты</a>
                    <span class="breadcrumbs__sep">/</span>
                    <span class="breadcrumbs__current"><?php the_title(); ?></span>
                </nav>

                <h1 class="project-hero__title"><?php the_title(); ?></h1>

                <?php if ( $terms && ! is_wp_error( $terms ) ) : ?>
                    <div class="project-hero__cats">
                        <?php foreach ( $terms as $term ) : ?>
                            <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="project-hero__cat-badge">
                                <?php echo esc_html( $term->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ( has_excerpt() ) : ?>
                    <p class="project-hero__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ====== Project Featured Image ====== -->
    <?php if ( has_post_thumbnail() ) : ?>
        <section class="project-featured">
            <div class="container">
                <div class="project-featured__img-wrap">
                    <?php the_post_thumbnail( 'hero-slide', array( 'class' => 'project-featured__img' ) ); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- ====== Project Specs ====== -->
    <?php if ( $area || $floors || $bedrooms || $bathrooms || $technology || $location || $style ) : ?>
        <section class="project-specs">
            <div class="container">
                <h2 class="project-specs__heading">Характеристики</h2>
                <div class="project-specs__grid">
                    <?php if ( $area ) : ?>
                        <div class="project-specs__item">
                            <span class="project-specs__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                            </span>
                            <span class="project-specs__label">Площадь</span>
                            <span class="project-specs__value"><?php echo esc_html( $area ); ?> м&sup2;</span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $floors ) : ?>
                        <div class="project-specs__item">
                            <span class="project-specs__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            </span>
                            <span class="project-specs__label">Этажность</span>
                            <span class="project-specs__value"><?php echo esc_html( $floors ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $bedrooms ) : ?>
                        <div class="project-specs__item">
                            <span class="project-specs__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                            </span>
                            <span class="project-specs__label">Спальни</span>
                            <span class="project-specs__value"><?php echo esc_html( $bedrooms ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $bathrooms ) : ?>
                        <div class="project-specs__item">
                            <span class="project-specs__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="10" x2="8" y1="5" y2="7"/><line x1="2" x2="22" y1="12" y2="12"/><line x1="7" x2="7" y1="19" y2="21"/><line x1="17" x2="17" y1="19" y2="21"/></svg>
                            </span>
                            <span class="project-specs__label">Санузлы</span>
                            <span class="project-specs__value"><?php echo esc_html( $bathrooms ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $technology ) : ?>
                        <div class="project-specs__item">
                            <span class="project-specs__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20h20"/><path d="M5 20V8.2a1 1 0 0 1 .4-.8l6-4.5a1 1 0 0 1 1.2 0l6 4.5a1 1 0 0 1 .4.8V20"/></svg>
                            </span>
                            <span class="project-specs__label">Технология</span>
                            <span class="project-specs__value"><?php echo esc_html( $technology ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $location ) : ?>
                        <div class="project-specs__item">
                            <span class="project-specs__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </span>
                            <span class="project-specs__label">Расположение</span>
                            <span class="project-specs__value"><?php echo esc_html( $location ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $style ) : ?>
                        <div class="project-specs__item">
                            <span class="project-specs__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                            </span>
                            <span class="project-specs__label">Стиль</span>
                            <span class="project-specs__value"><?php echo esc_html( $style ); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- ====== Project Description ====== -->
    <section class="project-description">
        <div class="container">
            <div class="project-description__content entry-content">
                <?php the_content(); ?>
            </div>
        </div>
    </section>

    <!-- ====== Floor Plans ====== -->
    <?php if ( $plan1 || $plan2 || $plan3 ) : ?>
        <section class="project-plans">
            <div class="container">
                <h2 class="project-plans__heading">Планировки</h2>
                <div class="project-plans__tabs">
                    <?php if ( $plan1 ) : ?>
                        <button class="project-plans__tab project-plans__tab--active" data-plan="1">1 этаж</button>
                    <?php endif; ?>
                    <?php if ( $plan2 ) : ?>
                        <button class="project-plans__tab" data-plan="2">2 этаж</button>
                    <?php endif; ?>
                    <?php if ( $plan3 ) : ?>
                        <button class="project-plans__tab" data-plan="3">3 этаж / Мансарда</button>
                    <?php endif; ?>
                </div>
                <div class="project-plans__panels">
                    <?php if ( $plan1 ) : ?>
                        <div class="project-plans__panel project-plans__panel--active" data-plan="1">
                            <img src="<?php echo esc_url( $plan1 ); ?>" alt="Планировка 1 этажа — <?php the_title_attribute(); ?>" class="project-plans__img" loading="lazy">
                        </div>
                    <?php endif; ?>
                    <?php if ( $plan2 ) : ?>
                        <div class="project-plans__panel" data-plan="2">
                            <img src="<?php echo esc_url( $plan2 ); ?>" alt="Планировка 2 этажа — <?php the_title_attribute(); ?>" class="project-plans__img" loading="lazy">
                        </div>
                    <?php endif; ?>
                    <?php if ( $plan3 ) : ?>
                        <div class="project-plans__panel" data-plan="3">
                            <img src="<?php echo esc_url( $plan3 ); ?>" alt="Планировка 3 этажа — <?php the_title_attribute(); ?>" class="project-plans__img" loading="lazy">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- ====== Project Gallery ====== -->
    <?php
    $gallery_images = array();
    if ( ! empty( $gallery ) ) {
        $gallery_images = array_map( 'trim', explode( ',', $gallery ) );
        $gallery_images = array_filter( $gallery_images );
    }
    ?>
    <?php if ( ! empty( $gallery_images ) ) : ?>
        <section class="project-gallery">
            <div class="container">
                <h2 class="project-gallery__heading">Галерея</h2>
                <div class="project-gallery__grid">
                    <?php foreach ( $gallery_images as $index => $img_url ) : ?>
                        <a href="<?php echo esc_url( $img_url ); ?>" class="project-gallery__item" data-lightbox="project-gallery" data-index="<?php echo esc_attr( $index ); ?>">
                            <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?> — фото <?php echo (int) $index + 1; ?>" class="project-gallery__img" loading="lazy">
                            <div class="project-gallery__overlay">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M11 8v6"/><path d="M8 11h6"/></svg>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- ====== CTA Section ====== -->
    <section class="project-cta">
        <div class="container">
            <div class="project-cta__inner">
                <h2 class="project-cta__title">Нравится этот проект?</h2>
                <p class="project-cta__text">Свяжитесь с нами для расчёта стоимости строительства или получения бесплатной консультации.</p>
                <div class="project-cta__buttons">
                    <a href="tel:<?php echo esc_attr( stroymsk_phone_raw() ); ?>" class="btn btn--primary btn--lg">
                        <?php echo esc_html( get_theme_mod( 'stroymsk_cta_primary', 'Рассчитать стоимость' ) ); ?>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/#contacts' ) ); ?>" class="btn btn--outline btn--lg">Задать вопрос</a>
                </div>
                <p class="project-cta__phone">
                    или позвоните: <a href="tel:<?php echo esc_attr( stroymsk_phone_raw() ); ?>"><?php echo stroymsk_phone(); ?></a>
                </p>
            </div>
        </div>
    </section>

    <!-- ====== Related Projects ====== -->
    <?php
    $related_args = array(
        'post_type'      => 'project',
        'posts_per_page' => 3,
        'post__not_in'   => array( get_the_ID() ),
        'orderby'        => 'rand',
    );

    // Try same category first
    if ( $terms && ! is_wp_error( $terms ) ) {
        $term_ids = wp_list_pluck( $terms, 'term_id' );
        $related_args['tax_query'] = array(
            array(
                'taxonomy' => 'project_category',
                'field'    => 'term_id',
                'terms'    => $term_ids,
            ),
        );
    }

    $related_query = new WP_Query( $related_args );
    ?>

    <?php if ( $related_query->have_posts() ) : ?>
        <section class="project-related">
            <div class="container">
                <h2 class="project-related__heading">Похожие проекты</h2>
                <div class="project-related__grid">
                    <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                        <article class="project-card">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" class="project-card__thumb">
                                    <?php the_post_thumbnail( 'project-card', array( 'class' => 'project-card__img' ) ); ?>
                                </a>
                            <?php endif; ?>
                            <div class="project-card__body">
                                <h3 class="project-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <?php
                                $rel_area   = get_post_meta( get_the_ID(), 'project_area', true );
                                $rel_floors = get_post_meta( get_the_ID(), 'project_floors', true );
                                ?>
                                <?php if ( $rel_area || $rel_floors ) : ?>
                                    <div class="project-card__specs">
                                        <?php if ( $rel_area ) : ?>
                                            <span class="project-card__spec"><?php echo esc_html( $rel_area ); ?> м&sup2;</span>
                                        <?php endif; ?>
                                        <?php if ( $rel_floors ) : ?>
                                            <span class="project-card__spec"><?php echo esc_html( $rel_floors ); ?> эт.</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>

<?php endwhile; ?>

<?php
get_footer();
