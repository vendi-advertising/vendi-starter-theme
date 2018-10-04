<?php

//Setup some theme-level constants to make life easier elsewhere
define( 'VENDI_CUSTOM_THEME_FILE', __FILE__ );
define( 'VENDI_CUSTOM_THEME_PATH', __DIR__ );
define( 'VENDI_CUSTOM_THEME_URL',  get_bloginfo( 'template_directory' ) );

require_once VENDI_CUSTOM_THEME_PATH . '/includes/autoload.php';

//Generally shared across all themse
require_once VENDI_CUSTOM_THEME_PATH . '/includes/vendi-base.php';
require_once VENDI_CUSTOM_THEME_PATH . '/includes/vendi-auto-asset-loader.php';

//Global functions
require_once VENDI_CUSTOM_THEME_PATH . '/includes/global-functions.php';

//General hooks
require_once VENDI_CUSTOM_THEME_PATH . '/includes/hooks.php';

