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
