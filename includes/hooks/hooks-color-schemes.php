<?php

use Vendi\Theme\ThemeSettings\ColorScheme;

const VENDI_OPTION_NAME_COLOR_SCHEMES = 'vendi-theme-color-schemes';

add_action(
    'wp_head',
    static function () {
        vendi_render_color_schemes();
    },
);

/**
 * We don't want to constantly parse the color schemes, so we'll only do it when someone
 * saves the options page and then store that in the global options table.
 */
add_action(
    'acfe/save_option/slug=color-schemes',
    static function () {
        $colorSchemes = get_field('color_schemes', 'option');
        if ( ! $colorSchemes || ! is_array($colorSchemes) || ! count($colorSchemes)) {
            return;
        }

        $colors = [];
        foreach ($colorSchemes as $colorScheme) {
            try {
                $cs                = ColorScheme::fromArray($colorScheme);
                $colors[$cs->slug] = $cs;
            } catch (Exception $e) {
                // Silently fail for now
            }
        }

        update_option(
            option: VENDI_OPTION_NAME_COLOR_SCHEMES,
            value: $colors,
            autoload: true,
        );
    },
);

add_filter(
    'acf/load_field/name=global-color-scheme',
    static function ($field) {
        // Nothing should be in here, but just in case
        $field['choices'] = [];

        $colorSchemes = get_option(VENDI_OPTION_NAME_COLOR_SCHEMES);
        if ( ! $colorSchemes || ! is_array($colorSchemes) || ! count($colorSchemes)) {
            return $field;
        }

        $field['choices'][ColorScheme::DEFAULT_COLOR_SCHEME_KEY] = 'Default';

        foreach ($colorSchemes as $colorScheme) {
            if ( ! $colorScheme instanceof ColorScheme) {
                continue;
            }

            $field['choices'][$colorScheme->slug] = $colorScheme->name;
        }

        return $field;
    },
);
