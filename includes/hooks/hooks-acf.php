<?php

/**
 * These two hooks synchronize ACF fields to JSON files on disk in order to
 * support storing them in source control.
 */

add_filter(
    'acf/settings/save_json',
    static function ($paths) {
        return VENDI_CUSTOM_THEME_PATH.'/.acf-json';
    }
);

add_filter(
    'acf/settings/load_json',
    static function ($paths) {
        return [VENDI_CUSTOM_THEME_PATH.'/.acf-json'];
    }
);

if (function_exists('acf_add_options_page')) {
    acf_add_options_page(
        [
            'page_title' => 'Theme Settings',
            'menu_title' => 'Theme Settings',
            'menu_slug' => 'theme_settings',
            'capability' => 'manage_options',
        ]
    );
}
