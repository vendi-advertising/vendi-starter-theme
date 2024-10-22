<?php

add_filter(
    'vendi/component-loader/get-layout-folder',
    static function () {
        return VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME;
    },
);

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
