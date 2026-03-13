<?php
/**
 * Main template file (blog/fallback)
 *
 * @package StroyMSK
 */

get_header();
?>

<section class="page-section page-section--blog">
    <div class="container">

        <!-- Page Header -->
        <div class="page-header">
            <?php if ( is_home() && ! is_front_page() ) : ?>
                <h1 class="page-header__title"><?php single_post_title(); ?></h1>
            <?php elseif ( is_search() ) : ?>
                <h1 class="page-header__title">Результаты поиска: &laquo;<?php the_search_query(); ?>&raquo;</h1>
            <?php elseif ( is_archive() ) : ?>
                <h1 class="page-header__title"><?php the_archive_title(); ?></h1>
                <?php the_archive_description( '<p class="page-header__desc">', '</p>' ); ?>
            <?php else : ?>
                <h1 class="page-header__title">Публикации</h1>
            <?php endif; ?>
        </div>

        <?php if ( have_posts() ) : ?>

            <div class="posts-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="post-card__thumb">
                                <?php the_post_thumbnail( 'project-card', array( 'class' => 'post-card__img' ) ); ?>
                            </a>
                        <?php endif; ?>

                        <div class="post-card__body">
                            <div class="post-card__meta">
                                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="post-card__date">
                                    <?php echo esc_html( get_the_date() ); ?>
                                </time>
                                <?php if ( has_category() ) : ?>
                                    <span class="post-card__cat"><?php the_category( ', ' ); ?></span>
                                <?php endif; ?>
                            </div>

                            <h2 class="post-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <p class="post-card__excerpt">
                                <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                            </p>

                            <a href="<?php the_permalink(); ?>" class="post-card__link">
                                Читать далее
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
                <h2 class="no-results__title">Ничего не найдено</h2>
                <p class="no-results__text">Попробуйте изменить параметры поиска или вернитесь на <a href="<?php echo esc_url( home_url( '/' ) ); ?>">главную страницу</a>.</p>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php
get_footer();
