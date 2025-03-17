<?php

//This code is called at theme boot up
add_action(
    'after_setup_theme',
    static function () {
        //Add featured image support
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('html5', ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script']);

        //Register some menus
        register_nav_menus(
            [
                'primary_navigation' => 'Primary navigation',
                'footer_navigation'  => 'Footer navigation',
                'utility_navigation' => 'Utility navigation',
            ],
        );
    },
);

/*  Add responsive container to embeds
/* ------------------------------------ */
function alx_embed_html($html)
{
    return '<div class="video-container">' . $html . '</div>';
}

add_filter('embed_oembed_html', 'alx_embed_html', 10, 3);
add_filter('video_embed_html', 'alx_embed_html');

add_action(
    'wp_enqueue_scripts',
    static function () {
        wp_dequeue_style('wp-block-library');
    },
);

add_action(
    'wp_enqueue_scripts',
    static function () {
        // Register but don't enqueue
        wp_register_style(VENDI_LIBRARY_ASSET_HANDLE_SLICK_CSS, VENDI_CUSTOM_THEME_URL . '/libraries/slick/slick.min.css', [], filemtime(VENDI_CUSTOM_THEME_PATH . '/libraries/slick/slick.min.css'), 'all');
        wp_register_style(VENDI_LIBRARY_ASSET_HANDLE_SLICK_THEME_CSS, VENDI_CUSTOM_THEME_URL . '/libraries/slick/slick-theme.min.css', [VENDI_LIBRARY_ASSET_HANDLE_SLICK_CSS], filemtime(VENDI_CUSTOM_THEME_PATH . '/libraries/slick/slick-theme.min.css'), 'all');
        wp_register_script(VENDI_LIBRARY_ASSET_HANDLE_SLICK_JS, VENDI_CUSTOM_THEME_URL . '/libraries/slick/slick.min.js', ['jquery'], filemtime(VENDI_CUSTOM_THEME_PATH . '/libraries/slick/slick.min.js'), true);

        wp_register_style(VENDI_LIBRARY_ASSET_HANDLE_BAGUETTE_BOX_CSS, VENDI_CUSTOM_THEME_URL . '/libraries/baguetteBox/baguetteBox.min.css', [], filemtime(VENDI_CUSTOM_THEME_PATH . '/libraries/baguetteBox/baguetteBox.min.css'), 'all');
        wp_register_script(VENDI_LIBRARY_ASSET_HANDLE_BAGUETTE_BOX_JS, VENDI_CUSTOM_THEME_URL . '/libraries/baguetteBox/baguetteBox.min.js', [], filemtime(VENDI_CUSTOM_THEME_PATH . '/libraries/baguetteBox/baguetteBox.min.js'), true);
    },
);

// Remove WP Block
add_action(
    'admin_init',
    static function () {
        remove_submenu_page('themes.php', 'site-editor.php?path=/patterns');
    },
);

add_action(
    'wp_print_styles',
    static function () {
        if ( ! is_admin() && ! is_admin_bar_showing()) {
            wp_deregister_style('dashicons');
        }
    },
    100,
);
