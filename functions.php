<?php

// Do not move these to constants.php,
const VENDI_CUSTOM_THEME_FILE = __FILE__;
const VENDI_CUSTOM_THEME_PATH = __DIR__;
define('VENDI_CUSTOM_THEME_URL', get_bloginfo('template_directory'));

define('VENDI_CUSTOM_THEME_URL_WITH_NO_TRAILING_SLASH', untrailingslashit(get_bloginfo('template_directory')));

require_once VENDI_CUSTOM_THEME_PATH.'/includes/constants.php';
require_once VENDI_CUSTOM_THEME_PATH.'/includes/theme-validity-test.php';
require_once VENDI_CUSTOM_THEME_PATH.'/includes/autoload.php';

// Generally shared across all themes
require_once VENDI_CUSTOM_THEME_PATH.'/includes/vendi-base.php';
require_once VENDI_CUSTOM_THEME_PATH.'/includes/vendi-auto-asset-loader.php';

// Global functions
require_once VENDI_CUSTOM_THEME_PATH.'/includes/global-functions.php';

// General hooks
require_once VENDI_CUSTOM_THEME_PATH.'/includes/hooks.php';

require_once VENDI_CUSTOM_THEME_PATH.'/includes/error-handling.php';
