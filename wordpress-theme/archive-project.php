<?php
/**
 * Project Archive template with filterable grid
 *
 * @package StroyMSK
 */

get_header();
?>

<section class="page-section page-section--projects">
    <div class="container">

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header__title">Проекты домов</h1>
            <p class="page-header__desc">Выберите проект вашего будущего дома. Каждый проект можно адаптировать под ваши пожелания.</p>
        </div>

        <!-- Category Filter -->
        <?php
        $project_categories = get_terms( array(
            'taxonomy'   => 'project_category',
            'hide_empty' => true,
        ) );
        ?>
        <?php if ( ! empty( $project_categories ) && ! is_wp_error( $project_categories ) ) : ?>
            <div class="project-filter" id="project-filter">
                <button class="project-filter__btn project-filter__btn--active" data-filter="all">
                    Все проекты
                </button>
                <?php foreach ( $project_categories as $cat ) : ?>
                    <button class="project-filter__btn" data-filter="<?php echo esc_attr( $cat->slug ); ?>">
                        <?php echo esc_html( $cat->name ); ?>
                        <span class="project-filter__count"><?php echo esc_html( $cat->count ); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Projects Grid -->
        <?php if ( have_posts() ) : ?>
            <div class="projects-grid" id="projects-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php
                    $project_terms = get_the_terms( get_the_ID(), 'project_category' );
                    $term_slugs    = '';
                    if ( $project_terms && ! is_wp_error( $project_terms ) ) {
                        $term_slugs = implode( ' ', wp_list_pluck( $project_terms, 'slug' ) );
                    }

                    $p_area       = get_post_meta( get_the_ID(), 'project_area', true );
                    $p_floors     = get_post_meta( get_the_ID(), 'project_floors', true );
                    $p_bedrooms   = get_post_meta( get_the_ID(), 'project_bedrooms', true );
                    $p_technology = get_post_meta( get_the_ID(), 'project_technology', true );
                    ?>
                    <article class="project-card" data-categories="<?php echo esc_attr( $term_slugs ); ?>">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="project-card__thumb">
                                <?php the_post_thumbnail( 'project-card', array( 'class' => 'project-card__img' ) ); ?>
                                <?php if ( $project_terms && ! is_wp_error( $project_terms ) ) : ?>
                                    <span class="project-card__badge">
                                        <?php echo esc_html( $project_terms[0]->name ); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>

                        <div class="project-card__body">
                            <h2 class="project-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <?php if ( has_excerpt() ) : ?>
                                <p class="project-card__excerpt"><?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?></p>
                            <?php endif; ?>

                            <div class="project-card__specs">
                                <?php if ( $p_area ) : ?>
                                    <span class="project-card__spec">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                        <?php echo esc_html( $p_area ); ?> м&sup2;
                                    </span>
                                <?php endif; ?>
                                <?php if ( $p_floors ) : ?>
                                    <span class="project-card__spec">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                        <?php echo esc_html( $p_floors ); ?> эт.
                                    </span>
                                <?php endif; ?>
                                <?php if ( $p_bedrooms ) : ?>
                                    <span class="project-card__spec">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                                        <?php echo esc_html( $p_bedrooms ); ?> спал.
                                    </span>
                                <?php endif; ?>
                                <?php if ( $p_technology ) : ?>
                                    <span class="project-card__spec">
                                        <?php echo esc_html( $p_technology ); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <a href="<?php the_permalink(); ?>" class="project-card__link btn btn--outline btn--sm">
                                Подробнее
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrap">
                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '&larr; Назад',
                    'next_text' => 'Далее &rarr;',
                ) );
                ?>
            </div>

        <?php else : ?>

            <div class="no-results">
                <h2 class="no-results__title">Проекты не найдены</h2>
                <p class="no-results__text">В данный момент проекты не доступны. Пожалуйста, вернитесь позже или <a href="<?php echo esc_url( home_url( '/#contacts' ) ); ?>">свяжитесь с нами</a>.</p>
            </div>

        <?php endif; ?>

        <!-- CTA Banner -->
        <div class="projects-cta">
            <div class="projects-cta__inner">
                <h2 class="projects-cta__title">Не нашли подходящий проект?</h2>
                <p class="projects-cta__text">Мы разработаем индивидуальный проект под ваши пожелания и бюджет.</p>
                <div class="projects-cta__buttons">
                    <a href="tel:<?php echo esc_attr( stroymsk_phone_raw() ); ?>" class="btn btn--primary btn--lg">
                        Заказать проект
                    </a>
                    <a href="<?php echo esc_url( home_url( '/#contacts' ) ); ?>" class="btn btn--outline btn--lg">
                        Получить консультацию
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Inline filter script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var filterBtns = document.querySelectorAll('.project-filter__btn');
    var cards = document.querySelectorAll('.project-card[data-categories]');

    if (!filterBtns.length || !cards.length) return;

    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var filter = this.getAttribute('data-filter');

            // Update active state
            filterBtns.forEach(function(b) { b.classList.remove('project-filter__btn--active'); });
            this.classList.add('project-filter__btn--active');

            // Filter cards
            cards.forEach(function(card) {
                if (filter === 'all') {
                    card.style.display = '';
                } else {
                    var cats = card.getAttribute('data-categories') || '';
                    card.style.display = cats.indexOf(filter) !== -1 ? '' : 'none';
                }
            });
        });
    });
});
</script>

<?php
get_footer();
