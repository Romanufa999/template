<?php
/**
 * Главный шаблон (fallback)
 * Используется когда нет более специфичного шаблона
 */

get_header();
?>

<main class="section">
    <div class="container">
        <?php if (have_posts()) : ?>

            <div class="section-title">
                <?php if (is_search()) : ?>
                    <h2>Результаты поиска: &laquo;<?php the_search_query(); ?>&raquo;</h2>
                <?php elseif (is_archive()) : ?>
                    <h2><?php the_archive_title(); ?></h2>
                <?php else : ?>
                    <h2>Публикации</h2>
                <?php endif; ?>
            </div>

            <div class="services-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="service-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        <?php endif; ?>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                    </article>
                <?php endwhile; ?>
            </div>

            <div style="text-align:center;margin-top:40px;">
                <?php the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => '&laquo; Назад',
                    'next_text' => 'Далее &raquo;',
                )); ?>
            </div>

        <?php else : ?>
            <div class="section-title">
                <h2>Ничего не найдено</h2>
                <p>Попробуйте изменить параметры поиска или вернитесь на <a href="<?php echo esc_url(home_url('/')); ?>" style="color:var(--primary);">главную страницу</a>.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
