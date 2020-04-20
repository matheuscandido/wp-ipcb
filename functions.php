<?php

require get_template_directory() . '/includes/utils.php';
require get_template_directory() . '/includes/customizer/customizer.php';
require get_template_directory() . '/includes/widgets/widget-example.php';
require get_template_directory() . '/includes/metaboxes/anuncios.php';


function theme_scripts()
{
    /**
     * COMMON
     */

    wp_enqueue_style('style', get_stylesheet_uri(), null, microtime(), all);
    wp_enqueue_script('main', get_theme_file_uri('/js/main.js'), null, microtime(), true);

    /**
     * FOR EACH PAGE
     */

    global $post;
    if (is_page() || is_single()) {
        switch ($post->post_name) {
            case 'example':
                wp_enqueue_script('page-example-js', get_theme_file_uri('/js/page-example.js'), null, microtime(), true);
                break;
        }
    }
}


add_action('wp_enqueue_scripts', 'theme_scripts');

// This WP Hook allows the use of all of Wordpress' JavaScript media APIs
function load_wp_media_files()
{
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'load_wp_media_files');

function theme_head()
{ ?>
    <!-- <link rel="shortcut icon" href="<?php echo get_theme_file_uri(); ?>/assets/icon.png"> -->
<?php
}

add_action('wp_head', 'theme_head');

// Remove aquela caixa feia de Custom Fields de todos os tipos de posts
add_action('do_meta_boxes', 'remove_default_custom_fields_meta_box', 1, 3);
function remove_default_custom_fields_meta_box($post_type, $context, $post)
{
    remove_meta_box('postcustom', $post_type, $context);
}

function create_custom_category($model_slug)
{
    register_taxonomy(
        $model_slug . '-category',
        'team',
        array(
            'label' => __('Category'),
            'rewrite' => array('slug' =>  $model_slug . '-category'),
            'hierarchical' => true,
        )
    );
}

function wp_register_custom_post_types()
{

    /**
     * FERRAMENTAS
     */

    create_custom_category("anuncios");

    $anuncios_labels = array(
        'name' => _x('Anúncios', 'Nome geral para o modelo de Anúncios'),
        'singular_name' => _x('Anúncio', 'Nome singularizado para o modelo de Ferramentas'),
    );

    $anuncios_args = array(
        'labels'              => $anuncios_labels,
        'description'         => 'Anúncios que serão inseridos no guia comunitário',
        'public'              => true,
        'has_archive'         => true,
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'menu_icon'           => 'dashicons-welcome-write-blog',
        'supports'            => array('title', 'thumbnail', 'excerpt', 'custom-fields'),
        'taxonomies'          => array('anuncios-category')
    );

    register_post_type('anuncios', $anuncios_args);
}
add_action('init', 'wp_register_custom_post_types');
