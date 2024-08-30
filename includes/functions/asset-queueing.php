<?php

use Symfony\Component\Filesystem\Path;

/**
 * Enqueue a style and automatically call filemtime on the file to get the version number.
 */
function vendi_theme_enqueue_style(string $handle, string $srcRelativeToThemeRoot, array|bool|null $deps = [], string $media = 'all', ?string $when = 'wp_enqueue_scripts'): void
{
    $func = static function () use ($handle, $srcRelativeToThemeRoot, $deps, $media) {
        wp_enqueue_style(
            handle: $handle,
            src: Path::join(VENDI_CUSTOM_THEME_URL_WITH_NO_TRAILING_SLASH, $srcRelativeToThemeRoot),
            deps: $deps,
            ver: filemtime(Path::join(VENDI_CUSTOM_THEME_PATH, $srcRelativeToThemeRoot)),
            media: $media
        );
    };

    if (!$when) {
        $when = 'wp_enqueue_scripts';
    }

    if (did_action($when)) {
        $func();
    } else {
        add_action($when, $func);

    }
}

/**
 * Enqueue a script and automatically call filemtime on the file to get the version number.
 */
function vendi_theme_enqueue_script(string $handle, string $srcRelativeToThemeRoot, array|bool|null $deps = [], bool|array $in_footer = false, ?string $when = 'wp_enqueue_scripts'): void
{
    if (!is_array($in_footer)) {
        $in_footer = [
            'in_footer' => $in_footer,
        ];
    }

    if (!$when) {
        $when = 'wp_enqueue_scripts';
    }

    $func = static function () use ($handle, $srcRelativeToThemeRoot, $deps, $in_footer) {
        wp_enqueue_script(
            handle: $handle,
            src: Path::join(VENDI_CUSTOM_THEME_URL_WITH_NO_TRAILING_SLASH, $srcRelativeToThemeRoot),
            deps: $deps,
            ver: filemtime(Path::join(VENDI_CUSTOM_THEME_PATH, $srcRelativeToThemeRoot)),
            args: $in_footer
        );
    };

    if (did_action($when)) {
        $func();
    } else {
        add_action($when, $func);
    }

}
