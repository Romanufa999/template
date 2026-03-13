<?php
/**
 * Single post template
 *
 * @package StroyMSK
 */

get_header();
?>

<section class="page-section page-section--single">
    <div class="container">

        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>

                <!-- Post Header -->
                <div class="single-post__header">
                    <div class="single-post__meta">
                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="single-post__date">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                        <?php if ( has_category() ) : ?>
                            <span class="single-post__cats"><?php the_category( ', ' ); ?></span>
                        <?php endif; ?>
                        <span class="single-post__author">
                            <?php echo esc_html( get_the_author() ); ?>
                        </span>
                    </div>
                    <h1 class="single-post__title"><?php the_title(); ?></h1>
                </div>

                <!-- Featured Image -->
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="single-post__hero">
                        <?php the_post_thumbnail( 'hero-slide', array( 'class' => 'single-post__hero-img' ) ); ?>
                    </div>
                <?php endif; ?>

                <!-- Content -->
                <div class="single-post__body entry-content">
                    <?php the_content(); ?>
                </div>

                <!-- Page Links -->
                <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links"><span class="page-links__label">Страницы:</span>',
                    'after'  => '</div>',
                ) );
                ?>

                <!-- Tags -->
                <?php if ( has_tag() ) : ?>
                    <div class="single-post__tags">
                        <?php the_tags( '<span class="single-post__tags-label">Теги:</span> ', ', ' ); ?>
                    </div>
                <?php endif; ?>

                <!-- Post Navigation -->
                <nav class="single-post__nav" aria-label="Навигация по записям">
                    <div class="single-post__nav-inner">
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        ?>
                        <?php if ( $prev_post ) : ?>
                            <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="single-post__nav-link single-post__nav-link--prev">
                                <span class="single-post__nav-label">&larr; Предыдущая</span>
                                <span class="single-post__nav-title"><?php echo esc_html( $prev_post->post_title ); ?></span>
                            </a>
                        <?php else : ?>
                            <span></span>
                        <?php endif; ?>

                        <?php if ( $next_post ) : ?>
                            <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="single-post__nav-link single-post__nav-link--next">
                                <span class="single-post__nav-label">Следующая &rarr;</span>
                                <span class="single-post__nav-title"><?php echo esc_html( $next_post->post_title ); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </nav>

            </article>

            <?php
            // Comments
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>

        <?php endwhile; ?>

    </div>
</section>

<?php
get_footer();
