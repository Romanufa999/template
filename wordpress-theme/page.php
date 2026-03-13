<?php
/**
 * Generic page template
 *
 * @package StroyMSK
 */

get_header();
?>

<section class="page-section page-section--page">
    <div class="container">

        <?php while ( have_posts() ) : the_post(); ?>

            <article id="page-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>

                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-header__title"><?php the_title(); ?></h1>
                </div>

                <!-- Featured Image -->
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="page-content__hero">
                        <?php the_post_thumbnail( 'hero-slide', array( 'class' => 'page-content__hero-img' ) ); ?>
                    </div>
                <?php endif; ?>

                <!-- Content -->
                <div class="page-content__body entry-content">
                    <?php the_content(); ?>
                </div>

                <!-- Page Links (if paginated) -->
                <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links"><span class="page-links__label">Страницы:</span>',
                    'after'  => '</div>',
                ) );
                ?>

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
