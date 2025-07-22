<?php

use Symfony\Component\Filesystem\Path;
use Vendi\Theme\ComponentInterface;
use Vendi\Theme\ComponentUtility;

function vendi_load_component_v3( array|string $name, ?array $object_state = null, ?ComponentInterface $component = null ): void {
    ComponentUtility::get_instance()->loadComponent( $name, $object_state, component: $component );
}

/**
 *
 * @internal This function is for internal use only. It should not be used for theme code!
 *
 */
function _vendi_maybe_get_template_name( string $name, string $filename ): ?string {
    $localName = explode( '/', $name );

    // Remove blanks, just in case
    $localName = array_filter($localName);

    $trueFileName = $filename;

    $componentDirectory = Path::join( VENDI_CUSTOM_THEME_PATH, VENDI_CUSTOM_THEME_TEMPLATE_FOLDER_NAME, ...$localName );

    if ( ! is_dir( $componentDirectory ) ) {
        return null;
    }

    $componentFile = Path::join( $componentDirectory, $trueFileName );

    if ( is_readable( $componentFile ) ) {
        return $componentFile;
    }

    return null;
}

function vendi_base_component_setting_color_set_default_value(&$field, string $defaultColor, ?string $instructions = null): void
{
    if ( ! is_array($field['sub_fields'] ?? null)) {
        return;
    }

    foreach ($field['sub_fields'] as &$sub_field) {
        if (($sub_field['__name'] ?? null) === 'color') {
            $sub_field['default_value'] = $defaultColor;
            if ($instructions) {
                $sub_field['instructions'] = $instructions;
            }
        }
    }
}

function vendi_base_component_setting_content_width_disable_values(&$field, array $widthsToDisable): void
{
    if ( ! is_array($field['sub_fields'] ?? null)) {
        return;
    }

    foreach ($field['sub_fields'] as &$sub_field) {
        if (($sub_field['__name'] ?? null) !== 'base_component_-_tab_-_content_settings') {
            continue;
        }

        foreach ($sub_field['sub_fields'] as &$sub_sub_field) {
            if (($sub_sub_field['__name'] ?? null) === 'content_width') {
                foreach ($widthsToDisable as $width) {
                    unset($sub_sub_field['choices'][$width]);
                }
            }
        }
    }
}

function vendi_base_component_setting_content_placement_disable_values(&$field, array $placementsToDisable): void
{
    if ( ! is_array($field['sub_fields'] ?? null)) {
        return;
    }

    foreach ($field['sub_fields'] as &$sub_field) {
        if (($sub_field['__name'] ?? null) !== 'base_component_-_tab_-_content_settings') {
            continue;
        }

        foreach ($sub_field['sub_fields'] as &$sub_sub_field) {
            if (($sub_sub_field['__name'] ?? null) === 'content_placement') {
                foreach ($placementsToDisable as $placement) {
                    unset($sub_sub_field['choices'][$placement]);
                }
            }
        }
    }
}
