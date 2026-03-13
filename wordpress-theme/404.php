<?php
/**
 * 404 Page Not Found template
 *
 * @package StroyMSK
 */

get_header();
?>

<section class="page-section page-section--404">
    <div class="container">
        <div class="error-404">

            <div class="error-404__code">404</div>

            <h1 class="error-404__title">Страница не найдена</h1>

            <p class="error-404__text">
                К сожалению, запрашиваемая страница не существует. Возможно, она была удалена или вы перешли по неверной ссылке.
            </p>

            <div class="error-404__actions">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary btn--lg">
                    На главную
                </a>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>" class="btn btn--outline btn--lg">
                    Смотреть проекты
                </a>
            </div>

            <div class="error-404__contact">
                <p>Нужна помощь? Позвоните нам:</p>
                <a href="tel:<?php echo esc_attr( stroymsk_phone_raw() ); ?>" class="error-404__phone">
                    <?php echo stroymsk_phone(); ?>
                </a>
            </div>

            <!-- Search Form -->
            <div class="error-404__search">
                <p class="error-404__search-label">Или попробуйте найти:</p>
                <?php get_search_form(); ?>
            </div>

        </div>
    </div>
</section>

<?php
get_footer();
