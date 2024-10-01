<?php

use Symfony\Component\Filesystem\Path;
use Vendi\Theme\ComponentUtility;

function vendi_load_modern_template(array|string $name): void
{
    if (!vendi_maybe_load_template_v2($name)) {
        dd('Could not locate template: ', $name);
    }
}

function vendi_load_modern_component(array|string $name, ?array $object_state = null): void
{
    ComponentUtility::get_instance()->loadComponent($name, $object_state);
}

function vendi_maybe_load_template_v2(array|string $name): bool
{
    $localName = is_string($name) ? explode('/', $name) : $name;

    // Remove blanks, just in case
    $localName = array_filter($localName);

    $filename = end($localName);

    $componentDirectory = Path::join(VENDI_CUSTOM_THEME_PATH, VENDI_CUSTOM_THEME_TEMPLATE_FOLDER_NAME, ...$localName);

    if (!is_dir($componentDirectory)) {
        return false;
    }

    // This code intentionally leaves in the class as well as style featured that the component system
    // uses, however we've commented it out for now, although we think it will be used in the future.

    $componentFile = Path::join($componentDirectory, $filename.'.php');
//    $componentClassFile = Path::join( $componentDirectory, $filename . '.class.php' );

    if (is_readable($componentFile)) {
//        // Optional helper class
//        if ( is_readable( $componentClassFile ) ) {
//            require_once $componentClassFile;
//        }

        // Main part of the component
        include $componentFile;

//        // On the first invocation of this component, also load CSS and JS if they exist
//        if ( count( $localName ) === 1 ) {
//            vendi_maybe_enqueue_component_global_styles_by_name( $filename );
//            vendi_maybe_enqueue_component_style_by_name( $filename );
//            vendi_maybe_enqueue_component_script_by_name( $filename );
//            vendi_maybe_enqueue_component_scripts_by_name( $filename );
//        }

        return true;
    }

    return false;
}

function vendi_maybe_load_component_component_v2(array|string $name, ?array $object_state = null): bool
{
    $localName = is_string($name) ? explode('/', $name) : $name;

    // Remove blanks, just in case
    $localName = array_filter($localName);

    if (count($localName) > 1) {
        $filename = array_pop($localName);
    } else {
        $filename = $localName[0];
    }

    $componentDirectory = Path::join(VENDI_CUSTOM_THEME_PATH, VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME, ...$localName);
    if (!is_dir($componentDirectory)) {
        return false;
    }

    $componentFile = Path::join($componentDirectory, $filename.'.php');
    $componentClassFile = Path::join($componentDirectory, $filename.'.class.php');

    if (is_readable($componentFile)) {
        // Optional helper class
        if (is_readable($componentClassFile)) {
            require_once $componentClassFile;
        }

        // Main part of the component
        vendi_load_layout_based_component($name, $object_state);

        // On the first invocation of this component, also load CSS and JS if they exist
        if (count($localName) === 1) {
            vendi_maybe_enqueue_component_global_styles_by_name($filename);
            vendi_maybe_enqueue_component_style_by_name($filename);
            vendi_maybe_enqueue_component_script_by_name($filename);
            vendi_maybe_enqueue_component_scripts_by_name($filename);
        }

        return true;
    }

    return false;
}

function vendi_iterate_over_possible_asset_filenames(string $name, array $possibleFileNames, callable $callback): bool
{
    $componentDirectoryUrl = Path::join(VENDI_CUSTOM_THEME_URL_WITH_NO_TRAILING_SLASH, VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME, $name);
    $componentDirectory = Path::join(VENDI_CUSTOM_THEME_PATH, VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME, $name);

    foreach ($possibleFileNames as $possibleFileName) {
        $assetPath = Path::join($componentDirectory, $possibleFileName);
        $assetUrl = Path::join($componentDirectoryUrl, $possibleFileName);
        if (is_readable($assetPath)) {
            $callback($name, $assetUrl, $assetPath);

            return true;
        }
    }

    return false;
}

function vendi_maybe_enqueue_component_style_by_name(string $name): bool
{
    return vendi_iterate_over_possible_asset_filenames(
        $name,
        [$name.'.css', 'render.css'],
        static function ($name, $assetUrl, $assetPath) {
            wp_enqueue_style('component-'.$name, $assetUrl, [], filemtime($assetPath));
        },
    );
}

function vendi_maybe_enqueue_component_script_by_name(string $name): bool
{
    return vendi_iterate_over_possible_asset_filenames(
        $name,
        [$name.'.js'],
        static function ($name, $assetUrl, $assetPath) {
            wp_enqueue_script('component-'.$name, $assetUrl, [], filemtime($assetPath), true);
        },
    );
}

function vendi_maybe_enqueue_component_scripts_by_name(string $name): bool
{
    return vendi_iterate_over_possible_asset_filenames(
        $name,
        [$name.'.scripts.json'],
        static function ($name, $assetUrl, $assetPath) {
            $scripts = json_decode(file_get_contents($assetPath), true, 512, JSON_THROW_ON_ERROR);
            if (!$scripts = $scripts['scripts'] ?? null) {
                return;
            }
            $assetUrlBase = Path::getDirectory($assetUrl);
            foreach ($scripts as $script) {
                $url = Path::join($assetUrlBase, $script);

                // The filemtime is wrong, it is calculating based on the JSON file, not the actual script files
                wp_enqueue_script('component-'.$name.'-'.$script, $url, [], filemtime($assetPath), true);
            }
        },
    );
}

function vendi_maybe_enqueue_component_global_styles_by_name(string $name): bool
{
    return vendi_iterate_over_possible_asset_filenames(
        $name,
        [$name.'.styles.json'],
        static function ($name, $assetUrl, $assetPath) {
            $styles = json_decode(file_get_contents($assetPath), true, 512, JSON_THROW_ON_ERROR);
            if (!$styles = $styles['global-styles'] ?? null) {
                return;
            }

            foreach ($styles as $style) {
                $assetPath = Path::join(VENDI_CUSTOM_THEME_PATH, 'css', $style);
                $url = Path::join(VENDI_CUSTOM_THEME_URL_WITH_NO_TRAILING_SLASH, 'css', $style);
                $assetName = basename($style, '.css');
                wp_enqueue_style('component-'.$assetName, $url, [], filemtime($assetPath));
            }
        },
    );
}
