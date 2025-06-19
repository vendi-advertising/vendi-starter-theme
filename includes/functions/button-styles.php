<?php

use Vendi\Theme\ThemeSettings\AbstractNamedThemeSetting;
use Vendi\Theme\ThemeSettings\ButtonStyle;


function vendi_get_button_styles(bool $echo = false): ?string
{
    return AbstractNamedThemeSetting::getThemeSettings(
        wordPressOptionName: VENDI_OPTION_NAME_BUTTON_STYLES,
        className: ButtonStyle::class,
        echo: $echo,
    );
}

function vendi_render_button_styles(): void
{
    vendi_get_button_styles(true);
}
