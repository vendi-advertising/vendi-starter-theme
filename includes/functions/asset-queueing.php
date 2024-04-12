<?php

/**
 * Enqueue a style and automatically call filemtime on the file to get the version number.
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
 */
function vendi_theme_enqueue_script(string $handle, string $srcRelativeToThemeRoot, array|bool|null $deps = [], bool|array $in_footer = false): void
{
    if (!is_array($in_footer)) {
        $in_footer = [
            'in_footer' => $in_footer,
        ];
    }
    wp_enqueue_script(
        handle: $handle,
        src: VENDI_CUSTOM_THEME_URL.$srcRelativeToThemeRoot,
        deps: $deps,
        ver: filemtime(VENDI_CUSTOM_THEME_PATH.$srcRelativeToThemeRoot),
        args: $in_footer
    );
}
