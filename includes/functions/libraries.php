<?php

use Symfony\Component\Filesystem\Path;

function vendi_enqueue_slick(string $jsFile, array|bool $args = true): void
{
    wp_enqueue_style(VENDI_LIBRARY_ASSET_HANDLE_SLICK_THEME_CSS);
    wp_enqueue_script(VENDI_LIBRARY_ASSET_HANDLE_SLICK_JS, args: $args);
    wp_enqueue_script($jsFile, Path::join(VENDI_CUSTOM_THEME_URL, $jsFile), [VENDI_LIBRARY_ASSET_HANDLE_SLICK_JS], filemtime(Path::join(VENDI_CUSTOM_THEME_PATH, $jsFile)), args: $args);
}

function vendi_enqueue_baguetteBox(): void
{
    wp_enqueue_style(VENDI_LIBRARY_ASSET_HANDLE_BAGUETTE_BOX_CSS);
    wp_enqueue_script(VENDI_LIBRARY_ASSET_HANDLE_BAGUETTE_BOX_JS);
}
