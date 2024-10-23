<?php

add_filter(
    'vendi/component-loader/get-layout-folder',
    static function () {
        return VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME;
    },
);

/**
 * Although this actually causes every component to be loaded twice, the performance is usually negligible,
 * especially with caching, and it allows all CSS and JS to be queued properly so that minification can
 * be done correctly.
 */
add_action(
    'wp_enqueue_scripts',
    static function () {
        ob_start();

        vendi_load_component_v3('header');

        vendi_load_component_v3('main');

        vendi_load_component_v3('footer');

        ob_end_clean();
    },
);
