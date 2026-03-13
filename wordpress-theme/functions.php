<?php
/**
 * СтройМСК — Premium Dark Construction Theme
 *
 * @package StroyMSK
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* --------------------------------------------------------------------------
 * Constants
 * ----------------------------------------------------------------------- */
define( 'STROYMSK_VERSION', '1.0.0' );
define( 'STROYMSK_DIR', get_template_directory() );
define( 'STROYMSK_URI', get_template_directory_uri() );
define( 'STROYMSK_S3_BASE', 'https://s3.ru1.storage.beget.cloud/76ae0220f799-proficient-naida/generate' );
define( 'STROYMSK_PHONE', '+7 (495) 123-45-67' );
define( 'STROYMSK_PHONE_RAW', '+74951234567' );

/* --------------------------------------------------------------------------
 * 1. Theme Setup
 * ----------------------------------------------------------------------- */
function stroymsk_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );

    register_nav_menus( array(
        'primary' => 'Основное меню',
        'footer'  => 'Меню подвала',
    ) );
}
add_action( 'after_setup_theme', 'stroymsk_setup' );

/* --------------------------------------------------------------------------
 * 2. Custom Image Sizes
 * ----------------------------------------------------------------------- */
function stroymsk_image_sizes() {
    add_image_size( 'hero-slide',    1920, 1080, true );
    add_image_size( 'project-card',  800,  600,  true );
    add_image_size( 'project-popup', 1200, 800,  true );
    add_image_size( 'team-member',   400,  500,  true );
    add_image_size( 'case-card',     800,  500,  true );
}
add_action( 'after_setup_theme', 'stroymsk_image_sizes' );

/* --------------------------------------------------------------------------
 * 3. Enqueue Styles & Scripts
 * ----------------------------------------------------------------------- */
function stroymsk_enqueue_assets() {
    // Google Fonts — Manrope (200-800) + Inter (300-600)
    wp_enqueue_style(
        'stroymsk-google-fonts',
        'https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap',
        array(),
        null
    );

    // Main stylesheet (versioned by filemtime)
    wp_enqueue_style(
        'stroymsk-style',
        get_stylesheet_uri(),
        array( 'stroymsk-google-fonts' ),
        filemtime( get_template_directory() . '/style.css' )
    );

    // Main script (versioned by filemtime, deferred)
    $js_path = get_template_directory() . '/assets/js/main.js';
    wp_enqueue_script(
        'stroymsk-main',
        STROYMSK_URI . '/assets/js/main.js',
        array(),
        file_exists( $js_path ) ? filemtime( $js_path ) : STROYMSK_VERSION,
        array(
            'in_footer' => true,
            'strategy'  => 'defer',
        )
    );

    wp_localize_script( 'stroymsk-main', 'stroymsk_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'stroymsk_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'stroymsk_enqueue_assets' );

/* --------------------------------------------------------------------------
 * 3b. Inline Yandex Metrika (hardcoded ID 107155846)
 * ----------------------------------------------------------------------- */
function stroymsk_yandex_metrika_inline() {
    ?>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for(var j=0;j<document.scripts.length;j++){if(document.scripts[j].src===r){return;}}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window,document,"script","https://mc.yandex.ru/metrika/tag.js","ym");

        ym(107155846, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script>
    <!-- /Yandex.Metrika counter -->
    <?php
}
add_action( 'wp_head', 'stroymsk_yandex_metrika_inline', 1 );

/* --------------------------------------------------------------------------
 * 4. Register Sidebars / Widgets
 * ----------------------------------------------------------------------- */
function stroymsk_widgets_init() {
    register_sidebar( array(
        'name'          => 'Боковая панель',
        'id'            => 'sidebar-1',
        'description'   => 'Основная боковая панель.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget__title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => 'Подвал',
        'id'            => 'footer-1',
        'description'   => 'Виджеты в подвале сайта.',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget__title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'stroymsk_widgets_init' );

/* --------------------------------------------------------------------------
 * 5. Custom Post Type — Projects (Проекты домов)
 * ----------------------------------------------------------------------- */
function stroymsk_register_project_cpt() {
    $labels = array(
        'name'                  => 'Проекты домов',
        'singular_name'         => 'Проект',
        'menu_name'             => 'Проекты',
        'name_admin_bar'        => 'Проект',
        'add_new'               => 'Добавить новый',
        'add_new_item'          => 'Добавить новый проект',
        'new_item'              => 'Новый проект',
        'edit_item'             => 'Редактировать проект',
        'view_item'             => 'Просмотреть проект',
        'all_items'             => 'Все проекты',
        'search_items'          => 'Искать проекты',
        'parent_item_colon'     => 'Родительский проект:',
        'not_found'             => 'Проекты не найдены.',
        'not_found_in_trash'    => 'В корзине проектов не найдено.',
        'archives'              => 'Архив проектов',
        'insert_into_item'      => 'Вставить в проект',
        'uploaded_to_this_item' => 'Загружено в этот проект',
        'filter_items_list'     => 'Фильтровать список проектов',
        'items_list_navigation' => 'Навигация по списку проектов',
        'items_list'            => 'Список проектов',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'projects' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-building',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
    );

    register_post_type( 'project', $args );
}
add_action( 'init', 'stroymsk_register_project_cpt' );

/* --------------------------------------------------------------------------
 * 6. Custom Taxonomy — Project Category (Категории проектов)
 * ----------------------------------------------------------------------- */
function stroymsk_register_project_taxonomy() {
    $labels = array(
        'name'                       => 'Категории проектов',
        'singular_name'              => 'Категория проекта',
        'search_items'               => 'Искать категории',
        'popular_items'              => 'Популярные категории',
        'all_items'                  => 'Все категории',
        'parent_item'                => 'Родительская категория',
        'parent_item_colon'          => 'Родительская категория:',
        'edit_item'                  => 'Редактировать категорию',
        'update_item'                => 'Обновить категорию',
        'add_new_item'               => 'Добавить новую категорию',
        'new_item_name'              => 'Название новой категории',
        'separate_items_with_commas' => 'Разделяйте категории запятыми',
        'add_or_remove_items'        => 'Добавить или удалить категории',
        'choose_from_most_used'      => 'Выбрать из часто используемых',
        'not_found'                  => 'Категории не найдены.',
        'menu_name'                  => 'Категории проектов',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'project-category' ),
    );

    register_taxonomy( 'project_category', array( 'project' ), $args );
}
add_action( 'init', 'stroymsk_register_project_taxonomy' );

/* --------------------------------------------------------------------------
 * 7. Project Custom Fields — Meta Boxes
 * ----------------------------------------------------------------------- */
function stroymsk_project_meta_boxes() {
    add_meta_box(
        'stroymsk_project_specs',
        'Характеристики проекта',
        'stroymsk_project_specs_callback',
        'project',
        'normal',
        'high'
    );

    add_meta_box(
        'stroymsk_project_plans',
        'Планировки (URL изображений)',
        'stroymsk_project_plans_callback',
        'project',
        'normal',
        'default'
    );

    add_meta_box(
        'stroymsk_project_gallery',
        'Галерея и отображение',
        'stroymsk_project_gallery_callback',
        'project',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'stroymsk_project_meta_boxes' );

/**
 * Meta box: Project Specs
 */
function stroymsk_project_specs_callback( $post ) {
    wp_nonce_field( 'stroymsk_project_meta', 'stroymsk_project_meta_nonce' );

    $fields = array(
        'project_area'       => array( 'label' => 'Площадь (м²)',      'type' => 'text' ),
        'project_floors'     => array( 'label' => 'Этажность',         'type' => 'text' ),
        'project_bedrooms'   => array( 'label' => 'Спальни',           'type' => 'text' ),
        'project_bathrooms'  => array( 'label' => 'Санузлы',           'type' => 'text' ),
        'project_technology' => array( 'label' => 'Технология',        'type' => 'text' ),
        'project_location'   => array( 'label' => 'Расположение',      'type' => 'text' ),
        'project_style'      => array( 'label' => 'Стиль',             'type' => 'text' ),
    );

    echo '<table class="form-table"><tbody>';
    foreach ( $fields as $key => $field ) {
        $value = get_post_meta( $post->ID, $key, true );
        printf(
            '<tr><th><label for="%1$s">%2$s</label></th><td><input type="%3$s" id="%1$s" name="%1$s" value="%4$s" class="regular-text"></td></tr>',
            esc_attr( $key ),
            esc_html( $field['label'] ),
            esc_attr( $field['type'] ),
            esc_attr( $value )
        );
    }
    echo '</tbody></table>';
}

/**
 * Meta box: Floor Plans
 */
function stroymsk_project_plans_callback( $post ) {
    $plans = array(
        'project_plan1' => '1-й этаж (URL изображения)',
        'project_plan2' => '2-й этаж (URL изображения)',
        'project_plan3' => '3-й этаж / мансарда (URL изображения)',
    );

    echo '<table class="form-table"><tbody>';
    foreach ( $plans as $key => $label ) {
        $value = get_post_meta( $post->ID, $key, true );
        printf(
            '<tr><th><label for="%1$s">%2$s</label></th><td><input type="url" id="%1$s" name="%1$s" value="%3$s" class="large-text"></td></tr>',
            esc_attr( $key ),
            esc_html( $label ),
            esc_attr( $value )
        );
    }
    echo '</tbody></table>';
}

/**
 * Meta box: Gallery & Hero
 */
function stroymsk_project_gallery_callback( $post ) {
    $gallery = get_post_meta( $post->ID, 'project_gallery', true );
    $hero    = get_post_meta( $post->ID, 'project_hero', true );

    echo '<table class="form-table"><tbody>';
    printf(
        '<tr><th><label for="project_gallery">Галерея (URL через запятую)</label></th><td><textarea id="project_gallery" name="project_gallery" rows="4" class="large-text">%s</textarea><p class="description">Укажите URL изображений через запятую.</p></td></tr>',
        esc_textarea( $gallery )
    );
    printf(
        '<tr><th><label for="project_hero">Показать в Hero</label></th><td><label><input type="checkbox" id="project_hero" name="project_hero" value="1" %s> Отображать этот проект в секции Hero на главной</label></td></tr>',
        checked( $hero, '1', false )
    );
    echo '</tbody></table>';
}

/**
 * Save project meta fields
 */
function stroymsk_save_project_meta( $post_id ) {
    // Verify nonce
    if ( ! isset( $_POST['stroymsk_project_meta_nonce'] ) ||
         ! wp_verify_nonce( $_POST['stroymsk_project_meta_nonce'], 'stroymsk_project_meta' ) ) {
        return;
    }

    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Text fields
    $text_fields = array(
        'project_area',
        'project_floors',
        'project_bedrooms',
        'project_bathrooms',
        'project_technology',
        'project_location',
        'project_style',
    );
    foreach ( $text_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }

    // URL fields (floor plans)
    $url_fields = array( 'project_plan1', 'project_plan2', 'project_plan3' );
    foreach ( $url_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, esc_url_raw( $_POST[ $field ] ) );
        }
    }

    // Gallery (comma-separated URLs)
    if ( isset( $_POST['project_gallery'] ) ) {
        update_post_meta( $post_id, 'project_gallery', sanitize_textarea_field( $_POST['project_gallery'] ) );
    }

    // Hero checkbox
    $hero_value = isset( $_POST['project_hero'] ) ? '1' : '';
    update_post_meta( $post_id, 'project_hero', $hero_value );
}
add_action( 'save_post_project', 'stroymsk_save_project_meta' );

/* --------------------------------------------------------------------------
 * 8. Form Handlers (admin-post.php)
 * ----------------------------------------------------------------------- */

/**
 * Contact Form Handler
 */
function stroymsk_handle_contact() {
    // Verify nonce
    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'stroymsk_contact_action' ) ) {
        wp_die( 'Ошибка безопасности. Попробуйте снова.', 'Ошибка', array( 'back_link' => true ) );
    }

    $name    = isset( $_POST['name'] )    ? sanitize_text_field( $_POST['name'] )        : '';
    $phone   = isset( $_POST['phone'] )   ? sanitize_text_field( $_POST['phone'] )       : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

    if ( empty( $name ) || empty( $phone ) ) {
        wp_safe_redirect( add_query_arg( 'form_error', '1', wp_get_referer() ) );
        exit;
    }

    // Save to database
    stroymsk_save_submission( 'contact', array(
        'name'    => $name,
        'phone'   => $phone,
        'message' => $message,
    ) );

    // Email notification
    $to      = get_option( 'admin_email' );
    $subject = 'Новая заявка с сайта СтройМСК от ' . $name;
    $body    = "Имя: {$name}\nТелефон: {$phone}\nСообщение:\n{$message}\n";
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );

    wp_mail( $to, $subject, $body, $headers );

    wp_safe_redirect( add_query_arg( 'success', '1', wp_get_referer() ) );
    exit;
}
add_action( 'admin_post_stroymsk_contact',        'stroymsk_handle_contact' );
add_action( 'admin_post_nopriv_stroymsk_contact', 'stroymsk_handle_contact' );

/**
 * Quiz Form Handler
 */
function stroymsk_handle_quiz() {
    // Verify nonce
    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'stroymsk_quiz_action' ) ) {
        wp_die( 'Ошибка безопасности. Попробуйте снова.', 'Ошибка', array( 'back_link' => true ) );
    }

    $phone   = isset( $_POST['phone'] )   ? sanitize_text_field( $_POST['phone'] )   : '';
    $answers = isset( $_POST['answers'] )  ? $_POST['answers']                         : '';

    if ( empty( $phone ) ) {
        wp_safe_redirect( add_query_arg( 'form_error', '1', wp_get_referer() ) );
        exit;
    }

    // Sanitize answers
    if ( is_array( $answers ) ) {
        $answers = array_map( 'sanitize_text_field', $answers );
        $answers_json = wp_json_encode( $answers, JSON_UNESCAPED_UNICODE );
    } else {
        $answers_json = sanitize_text_field( $answers );
    }

    // Save to database
    stroymsk_save_submission( 'quiz', array(
        'phone'   => $phone,
        'answers' => $answers_json,
    ) );

    // Email notification
    $to      = get_option( 'admin_email' );
    $subject = 'Новая заявка из квиза — СтройМСК';
    $body    = "Телефон: {$phone}\n\nОтветы:\n{$answers_json}\n";
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );

    wp_mail( $to, $subject, $body, $headers );

    wp_safe_redirect( add_query_arg( 'success', '1', wp_get_referer() ) );
    exit;
}
add_action( 'admin_post_stroymsk_quiz',        'stroymsk_handle_quiz' );
add_action( 'admin_post_nopriv_stroymsk_quiz', 'stroymsk_handle_quiz' );

/**
 * Save submission to custom DB table
 */
function stroymsk_save_submission( $type, $data ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'stroymsk_submissions';

    // Create table if not exists
    if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) !== $table_name ) {
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE {$table_name} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            type varchar(50) NOT NULL DEFAULT '',
            data longtext NOT NULL,
            ip_address varchar(100) DEFAULT '',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    $wpdb->insert(
        $table_name,
        array(
            'type'       => $type,
            'data'       => wp_json_encode( $data, JSON_UNESCAPED_UNICODE ),
            'ip_address' => sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' ),
            'created_at' => current_time( 'mysql' ),
        ),
        array( '%s', '%s', '%s', '%s' )
    );
}

/* --------------------------------------------------------------------------
 * 8b. AJAX Form Handlers (backward compatibility)
 * ----------------------------------------------------------------------- */
function stroymsk_ajax_handle_contact_form() {
    if ( ! isset( $_POST['stroymsk_contact_nonce'] ) ||
         ! wp_verify_nonce( $_POST['stroymsk_contact_nonce'], 'stroymsk_contact_action' ) ) {
        wp_send_json_error( array( 'message' => 'Ошибка безопасности. Попробуйте снова.' ) );
    }

    $name    = isset( $_POST['name'] )    ? sanitize_text_field( $_POST['name'] )        : '';
    $phone   = isset( $_POST['phone'] )   ? sanitize_text_field( $_POST['phone'] )       : '';
    $email   = isset( $_POST['email'] )   ? sanitize_email( $_POST['email'] )            : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

    if ( empty( $name ) || empty( $phone ) ) {
        wp_send_json_error( array( 'message' => 'Пожалуйста, заполните обязательные поля.' ) );
    }

    stroymsk_save_submission( 'contact', array(
        'name'    => $name,
        'phone'   => $phone,
        'email'   => $email,
        'message' => $message,
    ) );

    $to      = get_option( 'admin_email' );
    $subject = 'Новая заявка с сайта СтройМСК от ' . $name;
    $body    = "Имя: {$name}\nТелефон: {$phone}\nEmail: {$email}\nСообщение:\n{$message}\n";
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    if ( ! empty( $email ) ) {
        $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
    }

    $sent = wp_mail( $to, $subject, $body, $headers );

    if ( $sent ) {
        wp_send_json_success( array( 'message' => 'Спасибо! Мы свяжемся с вами в ближайшее время.' ) );
    } else {
        wp_send_json_error( array( 'message' => 'Ошибка отправки. Попробуйте позже.' ) );
    }
}
add_action( 'wp_ajax_stroymsk_contact',        'stroymsk_ajax_handle_contact_form' );
add_action( 'wp_ajax_nopriv_stroymsk_contact', 'stroymsk_ajax_handle_contact_form' );

function stroymsk_ajax_handle_quiz_form() {
    if ( ! isset( $_POST['stroymsk_quiz_nonce'] ) ||
         ! wp_verify_nonce( $_POST['stroymsk_quiz_nonce'], 'stroymsk_quiz_action' ) ) {
        wp_send_json_error( array( 'message' => 'Ошибка безопасности. Попробуйте снова.' ) );
    }

    $name    = isset( $_POST['name'] )    ? sanitize_text_field( $_POST['name'] )  : '';
    $phone   = isset( $_POST['phone'] )   ? sanitize_text_field( $_POST['phone'] ) : '';
    $answers = isset( $_POST['answers'] ) ? $_POST['answers']                       : array();

    if ( empty( $phone ) ) {
        wp_send_json_error( array( 'message' => 'Пожалуйста, укажите телефон.' ) );
    }

    $sanitized_answers = array();
    if ( is_array( $answers ) ) {
        foreach ( $answers as $key => $value ) {
            $sanitized_answers[ sanitize_text_field( $key ) ] = sanitize_text_field( $value );
        }
    }

    stroymsk_save_submission( 'quiz', array(
        'name'    => $name,
        'phone'   => $phone,
        'answers' => $sanitized_answers,
    ) );

    $to      = get_option( 'admin_email' );
    $subject = 'Новая заявка из квиза — СтройМСК';
    $body    = "Имя: {$name}\nТелефон: {$phone}\n\nОтветы:\n";
    foreach ( $sanitized_answers as $question => $answer ) {
        $body .= "— {$question}: {$answer}\n";
    }
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    wp_mail( $to, $subject, $body, $headers );

    wp_send_json_success( array( 'message' => 'Спасибо! Мы подберём оптимальный вариант и свяжемся с вами.' ) );
}
add_action( 'wp_ajax_stroymsk_quiz',        'stroymsk_ajax_handle_quiz_form' );
add_action( 'wp_ajax_nopriv_stroymsk_quiz', 'stroymsk_ajax_handle_quiz_form' );

/* --------------------------------------------------------------------------
 * 9. Customizer Settings
 * ----------------------------------------------------------------------- */
function stroymsk_customize_register( $wp_customize ) {

    /* --- Site Identity: Phone, Email, Address --- */
    $wp_customize->add_setting( 'stroymsk_phone', array(
        'default'           => STROYMSK_PHONE,
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'stroymsk_phone', array(
        'label'   => 'Номер телефона',
        'section' => 'title_tagline',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'stroymsk_email', array(
        'default'           => 'info@stroymsk.ru',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'stroymsk_email', array(
        'label'   => 'Email',
        'section' => 'title_tagline',
        'type'    => 'email',
    ) );

    $wp_customize->add_setting( 'stroymsk_address', array(
        'default'           => 'г. Москва, ул. Строителей, д. 1',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'stroymsk_address', array(
        'label'   => 'Адрес',
        'section' => 'title_tagline',
        'type'    => 'text',
    ) );

    /* --- Social Links --- */
    $wp_customize->add_section( 'stroymsk_social', array(
        'title'    => 'Социальные сети',
        'priority' => 32,
    ) );

    $socials = array(
        'vk'       => 'ВКонтакте',
        'telegram' => 'Telegram',
        'whatsapp' => 'WhatsApp',
        'youtube'  => 'YouTube',
        'dzen'     => 'Яндекс Дзен',
    );

    foreach ( $socials as $key => $label ) {
        $wp_customize->add_setting( "stroymsk_social_{$key}", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( "stroymsk_social_{$key}", array(
            'label'   => $label,
            'section' => 'stroymsk_social',
            'type'    => 'url',
        ) );
    }

    /* --- Hero Section --- */
    $wp_customize->add_section( 'stroymsk_hero', array(
        'title'    => 'Секция Hero',
        'priority' => 30,
    ) );

    $wp_customize->add_setting( 'stroymsk_hero_headline', array(
        'default'           => 'Строим дома мечты под ключ',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'stroymsk_hero_headline', array(
        'label'   => 'Заголовок',
        'section' => 'stroymsk_hero',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'stroymsk_hero_subtitle', array(
        'default'           => 'Премиальное строительство в Москве и МО',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'stroymsk_hero_subtitle', array(
        'label'   => 'Подзаголовок',
        'section' => 'stroymsk_hero',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'stroymsk_hero_description', array(
        'default'           => 'Проектирование, строительство и отделка загородных домов с гарантией качества и фиксированной ценой.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'stroymsk_hero_description', array(
        'label'   => 'Описание',
        'section' => 'stroymsk_hero',
        'type'    => 'textarea',
    ) );

    /* --- CTA Button Texts --- */
    $wp_customize->add_section( 'stroymsk_cta', array(
        'title'    => 'Кнопки CTA',
        'priority' => 33,
    ) );

    $wp_customize->add_setting( 'stroymsk_cta_primary', array(
        'default'           => 'Рассчитать стоимость',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'stroymsk_cta_primary', array(
        'label'   => 'Основная кнопка CTA',
        'section' => 'stroymsk_cta',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'stroymsk_cta_secondary', array(
        'default'           => 'Смотреть проекты',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'stroymsk_cta_secondary', array(
        'label'   => 'Вторичная кнопка CTA',
        'section' => 'stroymsk_cta',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'stroymsk_cta_header', array(
        'default'           => 'Связаться',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'stroymsk_cta_header', array(
        'label'   => 'Кнопка CTA в шапке',
        'section' => 'stroymsk_cta',
        'type'    => 'text',
    ) );

    /* --- Trust Bar --- */
    $wp_customize->add_section( 'stroymsk_trust_bar', array(
        'title'    => 'Показатели доверия',
        'priority' => 31,
    ) );

    for ( $i = 1; $i <= 4; $i++ ) {
        $wp_customize->add_setting( "stroymsk_trust_value_{$i}", array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "stroymsk_trust_value_{$i}", array(
            'label'   => "Показатель #{$i} — значение",
            'section' => 'stroymsk_trust_bar',
            'type'    => 'text',
        ) );

        $wp_customize->add_setting( "stroymsk_trust_label_{$i}", array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "stroymsk_trust_label_{$i}", array(
            'label'   => "Показатель #{$i} — подпись",
            'section' => 'stroymsk_trust_bar',
            'type'    => 'text',
        ) );
    }

    /* --- Yandex Metrika (additional customizer ID) --- */
    $wp_customize->add_section( 'stroymsk_analytics', array(
        'title'    => 'Аналитика',
        'priority' => 160,
    ) );

    $wp_customize->add_setting( 'stroymsk_ym_counter', array(
        'default'           => '107155846',
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'stroymsk_ym_counter', array(
        'label'       => 'ID счётчика Яндекс Метрики',
        'description' => 'Введите числовой ID счётчика.',
        'section'     => 'stroymsk_analytics',
        'type'        => 'number',
    ) );
}
add_action( 'customize_register', 'stroymsk_customize_register' );

/* --------------------------------------------------------------------------
 * 10. Helper Functions
 * ----------------------------------------------------------------------- */

/**
 * Get formatted phone number for display.
 */
function stroymsk_phone() {
    return esc_html( get_theme_mod( 'stroymsk_phone', STROYMSK_PHONE ) );
}

/**
 * Get raw phone number (digits + plus only) for tel: links.
 */
function stroymsk_phone_raw() {
    $phone = get_theme_mod( 'stroymsk_phone', STROYMSK_PHONE );
    return preg_replace( '/[^+\d]/', '', $phone );
}

/**
 * Get full S3 URL for a filename.
 */
function stroymsk_s3_url( $filename ) {
    return trailingslashit( STROYMSK_S3_BASE ) . ltrim( $filename, '/' );
}

/* --------------------------------------------------------------------------
 * 11. JSON-LD Schema — LocalBusiness (output in head)
 * ----------------------------------------------------------------------- */
function stroymsk_jsonld_schema() {
    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'LocalBusiness',
        'name'        => 'СтройМСК',
        'description' => get_bloginfo( 'description' ),
        'url'         => home_url( '/' ),
        'telephone'   => stroymsk_phone(),
        'email'       => get_theme_mod( 'stroymsk_email', 'info@stroymsk.ru' ),
        'address'     => array(
            '@type'           => 'PostalAddress',
            'streetAddress'   => get_theme_mod( 'stroymsk_address', 'г. Москва, ул. Строителей, д. 1' ),
            'addressLocality' => 'Москва',
            'addressRegion'   => 'Московская область',
            'addressCountry'  => 'RU',
        ),
        'geo' => array(
            '@type'     => 'GeoCoordinates',
            'latitude'  => '55.7558',
            'longitude' => '37.6173',
        ),
        'openingHours' => 'Mo-Fr 09:00-19:00',
        'priceRange'   => '₽₽₽',
    );

    if ( has_custom_logo() ) {
        $logo_id  = get_theme_mod( 'custom_logo' );
        $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
        if ( $logo_url ) {
            $schema['logo'] = $logo_url;
        }
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'stroymsk_jsonld_schema' );

/* --------------------------------------------------------------------------
 * 12. Fallback Menu
 * ----------------------------------------------------------------------- */
function stroymsk_fallback_menu() {
    $sections = array(
        '#about'    => 'О компании',
        '#projects' => 'Проекты',
        '#services' => 'Услуги',
        '#process'  => 'Этапы',
        '#cases'    => 'Кейсы',
        '#reviews'  => 'Отзывы',
        '#contacts' => 'Контакты',
    );
    echo '<ul class="main-nav__list">';
    foreach ( $sections as $href => $label ) {
        echo '<li class="main-nav__item"><a href="' . esc_url( home_url( '/' ) . $href ) . '" class="main-nav__link">' . esc_html( $label ) . '</a></li>';
    }
    echo '</ul>';
}
