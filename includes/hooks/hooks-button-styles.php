<?php


use Vendi\Theme\ThemeSettings\ButtonStyle;

const VENDI_OPTION_NAME_BUTTON_STYLES = 'vendi-theme-button-styles';

add_action(
    'wp_head',
    static function () {
        vendi_render_button_styles();
    },
);

/**
 * We don't want to constantly parse the color schemes, so we'll only do it when someone
 * saves the options page and then store that in the global options table.
 */
add_action(
    'acfe/save_option/slug=button-styles',
    static function () {
        $acfButtonStyles = get_field('button_styles', 'option');
        if ( ! $acfButtonStyles || ! is_array($acfButtonStyles) || ! count($acfButtonStyles)) {
            return;
        }

        $vendiButtonStyles = [];
        foreach ($acfButtonStyles as $acfButtonStyle) {
            try {
                $bs                           = ButtonStyle::fromArray($acfButtonStyle);
                $vendiButtonStyles[$bs->slug] = $bs;
            } catch (Exception $e) {
                // Silently fail for now
            }
        }

        update_option(
            option: VENDI_OPTION_NAME_BUTTON_STYLES,
            value: $vendiButtonStyles,
            autoload: true,
        );
    },
);

add_filter(
    'acf/load_field/name=global-button-style',
    static function ($field) {
        // Nothing should be in here, but just in case
        $field['choices'] = [];

        $vendiButtonStyles = get_option(VENDI_OPTION_NAME_BUTTON_STYLES);
        if ( ! $vendiButtonStyles || ! is_array($vendiButtonStyles) || ! count($vendiButtonStyles)) {
            return $field;
        }

        foreach ($vendiButtonStyles as $vendiButtonStyle) {
            if ( ! $vendiButtonStyle instanceof ButtonStyle) {
                continue;
            }

            $field['choices'][$vendiButtonStyle->slug] = $vendiButtonStyle->name;
        }

        return $field;
    },
);

add_action(
    'acfe/render_choice/name=global-button-style',
    static function ($input, $value, $label, $field) {
        echo $input;
        echo $label;

        vendi_render_button_styles();
        vendi_load_component_v3(
            'buttons/button',
            [
                'link'              => [
                    'url'   => '#',
                    'title' => 'Sample Button',
                ],
                'button_style'      => $value,
                'html_tag_for_link' => 'span',
            ],
        );
        ?>

        <?php
    },
    accepted_args: 4,
);
