<?php

// Setup some theme-level constants to make life easier elsewhere
const VENDI_CUSTOM_THEME_FILE = __FILE__;
const VENDI_CUSTOM_THEME_PATH = __DIR__;
define( 'VENDI_CUSTOM_THEME_URL', get_bloginfo( 'template_directory' ) );
define( 'VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME', 'content_components' );
define( 'VENDI_CUSTOM_THEME_FEATURE_PATH', VENDI_CUSTOM_THEME_PATH . '/features' );

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
