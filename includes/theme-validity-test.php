<?php

define('VENDI_EARLY_EXIT_ERROR_CODE_MISSING_AUTOLOAD', 1);
define('VENDI_EARLY_EXIT_ERROR_CODE_MISSING_PLUGIN_ACF_PRO', 2);
define('VENDI_EARLY_EXIT_ERROR_CODE_PHP_VERSION', 3);
define('VENDI_EARLY_EXIT_ERROR_CODE_MISSING_PLUGIN_FLY', 4);
define('VENDI_EARLY_EXIT_ERROR_CODE_MISSING_PLUGIN_CLASSIC_EDITOR', 5);

define('VENDI_MINIMUM_PHP_VERSION_ID', 80100);

if (PHP_VERSION_ID < VENDI_MINIMUM_PHP_VERSION_ID) {
    vendi_do_early_exit(VENDI_EARLY_EXIT_ERROR_CODE_PHP_VERSION);

    return;
}

if (!is_readable(VENDI_CUSTOM_THEME_PATH.'/vendor/autoload.php')) {
    vendi_do_early_exit(VENDI_EARLY_EXIT_ERROR_CODE_MISSING_AUTOLOAD);

    return;
}

if (!function_exists('get_field')) {
    vendi_do_early_exit(VENDI_EARLY_EXIT_ERROR_CODE_MISSING_PLUGIN_ACF_PRO);

    return;
}

if (!function_exists('fly_get_attachment_image_src')) {
    vendi_do_early_exit(VENDI_EARLY_EXIT_ERROR_CODE_MISSING_PLUGIN_FLY);

    return;
}

require_once ABSPATH.'wp-admin/includes/plugin.php';
if (function_exists('is_plugin_active') && !is_plugin_active('classic-editor/classic-editor.php')) {
    vendi_do_early_exit(VENDI_EARLY_EXIT_ERROR_CODE_MISSING_PLUGIN_CLASSIC_EDITOR);

    return;
}

function vendi_do_early_exit(int $code): void
{
    define('VENDI_EARLY_EXIT_ERROR_CODE', $code);
    require_once VENDI_CUSTOM_THEME_PATH.'/vendi-theme-parts/early-exit/early-exit.php';
    if (!defined('WP_CLI')) {
        exit;
    }
}
