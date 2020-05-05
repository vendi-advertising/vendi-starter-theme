<?php

//This code is called at theme boot up
add_action(
    'after_setup_theme',
    static function () {

        //Add featured image support
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('html5', ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption']);

        //Register some menus
        register_nav_menus(
            [
                'main_nav' => 'Primary navigation',
                'footer_nav' => 'Footer navigation',
            ]
        );

        if (function_exists('fly_add_image_size')) {
            //fly_add_image_size('NAME', 430, 560, true);
        }

    }
);
