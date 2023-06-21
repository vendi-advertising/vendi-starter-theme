<?php

//Please avoid using global functions if possible!

require_once VENDI_CUSTOM_THEME_PATH.'/includes/functions/utility.php';

/**
 * Enqueue a style and automatically call filemtime on the file to get the version number.
 *
 * @param string $handle
 * @param string $srcRelativeToThemeRoot
 * @param array|bool|null $deps
 * @param string $media
 * @return void
 */
function vendi_theme_enqueue_style(string $handle, string $srcRelativeToThemeRoot, array|bool|null $deps = [], string $media = 'all'): void
{
    wp_enqueue_style(
        handle: $handle,
        src: VENDI_CUSTOM_THEME_URL.$srcRelativeToThemeRoot,
        deps: $deps,
        ver: filemtime(VENDI_CUSTOM_THEME_PATH.$srcRelativeToThemeRoot),
        media: $media
    );
}

/**
 * Enqueue a script and automatically call filemtime on the file to get the version number.
 *
 * @param string $handle
 * @param string $srcRelativeToThemeRoot
 * @param array|bool|null $deps
 * @param bool $in_footer
 * @return void
 */
function vendi_theme_enqueue_script(string $handle, string $srcRelativeToThemeRoot, array|bool|null $deps = [], bool $in_footer = false): void
{
    wp_enqueue_script(
        handle: $handle,
        src: VENDI_CUSTOM_THEME_URL.$srcRelativeToThemeRoot,
        deps: $deps,
        ver: filemtime(VENDI_CUSTOM_THEME_PATH.$srcRelativeToThemeRoot),
        in_footer: $in_footer
    );
}
