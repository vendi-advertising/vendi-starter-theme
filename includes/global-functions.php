<?php

//Please avoid using global functions if possible!

require_once VENDI_CUSTOM_THEME_PATH.'/includes/functions/utility.php';

function vendi_get_global_javascript(?string $location): array
{
    static $global_javascript = null;

    if (null === $global_javascript) {
        $global_javascript = get_field('global_javascript', 'options');
    }

    if (!$global_javascript || !is_array($global_javascript)) {
        // Ensure we don't look it update again
        $global_javascript = [];

        return [];
    }

    $ret = [];

    foreach ($global_javascript as $item) {
        if (isset($item['global_javascript_code_block']) && $location === ($item['global_javascript_location'])) {
            $ret[] = $item['global_javascript_code_block'];
        }
    }

    return $ret;
}

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
function vendi_theme_enqueue_script(string $handle, string $srcRelativeToThemeRoot, array|bool|null $deps = [], bool|array $in_footer = false): void
{
    if (!is_array($in_footer)) {
        $in_footer = [
            'in_footer' => $in_footer,
        ];
    }
    wp_enqueue_script(
        $handle,
        VENDI_CUSTOM_THEME_URL.$srcRelativeToThemeRoot,
        $deps,
        filemtime(VENDI_CUSTOM_THEME_PATH.$srcRelativeToThemeRoot),
        $in_footer
    );
}
