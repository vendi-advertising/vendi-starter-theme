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
                'footer_navigation' => 'Footer navigation',
            ]
        );

    }
);

/*  Add responsive container to embeds
/* ------------------------------------ */
function alx_embed_html($html)
{
    return '<div class="video-container">'.$html.'</div>';
}

add_filter('embed_oembed_html', 'alx_embed_html', 10, 3);
add_filter('video_embed_html', 'alx_embed_html');

add_action(
    'wp_enqueue_scripts',
    static function () {
        wp_dequeue_style('wp-block-library');
    }
);
