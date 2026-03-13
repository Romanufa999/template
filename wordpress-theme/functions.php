<?php
/**
 * СтройМСК — functions.php
 * Настройка темы: меню, стили, скрипты, виджеты
 */

// Настройка темы
function stroymsk_setup() {
    // Поддержка заголовка из админки
    add_theme_support('title-tag');

    // Миниатюры записей
    add_theme_support('post-thumbnails');

    // Кастомный логотип
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // HTML5 разметка
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Регистрация меню
    register_nav_menus(array(
        'primary'  => 'Главное меню',
        'footer'   => 'Меню в подвале',
    ));
}
add_action('after_setup_theme', 'stroymsk_setup');

// Подключение стилей и скриптов
function stroymsk_scripts() {
    // Google Fonts — Inter
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    // Основной стиль темы
    wp_enqueue_style(
        'stroymsk-style',
        get_stylesheet_uri(),
        array('google-fonts'),
        wp_get_theme()->get('Version')
    );

    // Основной скрипт
    wp_enqueue_script(
        'stroymsk-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'stroymsk_scripts');

// Регистрация сайдбаров / виджетов
function stroymsk_widgets() {
    register_sidebar(array(
        'name'          => 'Сайдбар',
        'id'            => 'sidebar-1',
        'description'   => 'Основная боковая панель',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => 'Подвал',
        'id'            => 'footer-1',
        'description'   => 'Виджеты в подвале',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'stroymsk_widgets');

// Обработка формы обратной связи
function stroymsk_handle_contact_form() {
    if (!isset($_POST['stroymsk_contact_nonce']) ||
        !wp_verify_nonce($_POST['stroymsk_contact_nonce'], 'stroymsk_contact')) {
        wp_die('Ошибка безопасности');
    }

    $name    = sanitize_text_field($_POST['contact_name'] ?? '');
    $phone   = sanitize_text_field($_POST['contact_phone'] ?? '');
    $message = sanitize_textarea_field($_POST['contact_message'] ?? '');

    $to      = get_option('admin_email');
    $subject = 'Заявка с сайта: ' . $name;
    $body    = "Имя: {$name}\nТелефон: {$phone}\nСообщение:\n{$message}";
    $headers = array('Content-Type: text/plain; charset=UTF-8');

    wp_mail($to, $subject, $body, $headers);

    wp_redirect(home_url('/?contact=success'));
    exit;
}
add_action('admin_post_stroymsk_contact', 'stroymsk_handle_contact_form');
add_action('admin_post_nopriv_stroymsk_contact', 'stroymsk_handle_contact_form');
