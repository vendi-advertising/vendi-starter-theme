<?php

use Vendi\Theme\ThemeSettings\AbstractNamedThemeSetting;
use Vendi\Theme\ThemeSettings\ColorScheme;

function vendi_get_color_schemes(bool $echo = false): ?string
{
    return AbstractNamedThemeSetting::getThemeSettings(
        VENDI_OPTION_NAME_COLOR_SCHEMES,
        ColorScheme::class,
        $echo,
    );
}

function vendi_render_color_schemes(): void
{
    vendi_get_color_schemes(true);
}
