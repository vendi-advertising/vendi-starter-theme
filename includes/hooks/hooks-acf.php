<?php

/**
 * These two hooks synchronize ACF fields to JSON file on disk in order to
 * support storing them in Git.
 */

add_filter(
    'acf/settings/save_json',
    static function ($paths) {
        return VENDI_CUSTOM_THEME_PATH . '/.acf-json';
    }
);

add_filter(
    'acf/settings/load_json',
    static function ($paths) {
        return [VENDI_CUSTOM_THEME_PATH . '/.acf-json'];
    }
);
