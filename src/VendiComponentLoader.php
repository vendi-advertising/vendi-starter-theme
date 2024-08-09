<?php

namespace Vendi\Theme;

use Symfony\Component\Filesystem\Path;

class VendiComponentLoader
{
    private static self $instance;

    private function __construct()
    {
    }

    public static function get_instance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function loadComponent(array|string $name, ?array $object_state = null): void
    {
        if (!$this->loadComponent2024($name, object_state: $object_state)) {
            if (is_array($name)) {
                $name = implode('/', $name);
            }
            throw new \RuntimeException('Could not load component: '.$name);
        }
    }

    private function loadComponent2024(array|string $name, ?array $object_state = null): bool
    {
        $localName = is_string($name) ? explode('/', $name) : $name;

        // Remove blanks, just in case
        $localName = array_filter($localName);

        if (count($localName) > 1) {
            $filename = array_pop($localName);
        } else {
            $filename = $localName[0];
        }

        $componentDirectory = Path::join(VENDI_CUSTOM_THEME_PATH, VENDI_CUSTOM_THEME_LAYOUT_FOLDER_NAME, ...$localName);
        if (!is_dir($componentDirectory)) {
            return false;
        }

        // Allow basic_copy_block.php instead of just component.php
        $possibleFileNames = [$filename.'.php'];
        foreach ($possibleFileNames as $possibleFileName) {
            $componentFile = Path::join($componentDirectory, $possibleFileName);
            if (is_readable($componentFile)) {
                vendi_load_layout_based_component($name, $object_state);

                if (count($localName) === 1) {
                    $this->enqueueComponentGlobalStylesByName($filename);
                    $this->vendi_maybe_enqueue_component_style_by_name($filename);
                    $this->vendi_maybe_enqueue_component_script_by_name($filename);
                    $this->vendi_maybe_enqueue_component_scripts_by_name($filename);
                }

                return true;
            }
        }

        return false;
    }

    private function vendi_iterate_over_possible_asset_filenames(string $name, array $possibleFileNames, callable $callback): bool
    {
        $componentDirectoryUrl = Path::join(VENDI_CUSTOM_THEME_URL, VENDI_CUSTOM_THEME_LAYOUT_FOLDER_NAME, $name);
        $componentDirectory = Path::join(VENDI_CUSTOM_THEME_PATH, VENDI_CUSTOM_THEME_LAYOUT_FOLDER_NAME, $name);

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

    private function vendi_maybe_enqueue_component_style_by_name(string $name): bool
    {
        return $this->vendi_iterate_over_possible_asset_filenames(
            $name,
            [$name.'.css', 'render.css'],
            static function ($name, $assetUrl, $assetPath) {
                wp_enqueue_style('component-'.$name, $assetUrl, [], filemtime($assetPath));
            }
        );
    }

    private function vendi_maybe_enqueue_component_script_by_name(string $name): bool
    {
        return $this->vendi_iterate_over_possible_asset_filenames(
            $name,
            [$name.'.js'],
            static function ($name, $assetUrl, $assetPath) {
                wp_enqueue_script('component-'.$name, $assetUrl, [], filemtime($assetPath), true);
            }
        );
    }

    private function vendi_maybe_enqueue_component_scripts_by_name(string $name): bool
    {
        return $this->vendi_iterate_over_possible_asset_filenames(
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
            }
        );
    }

    private function enqueueComponentGlobalStylesByName(string $name): bool
    {
        return $this->vendi_iterate_over_possible_asset_filenames(
            $name,
            [$name.'.styles.json'],
            static function ($name, $assetUrl, $assetPath) {
                $styles = json_decode(file_get_contents($assetPath), true, 512, JSON_THROW_ON_ERROR);
                if (!$styles = $styles['global-styles'] ?? null) {
                    return;
                }

                foreach ($styles as $style) {
                    $assetPath = Path::join(VENDI_CUSTOM_THEME_PATH, 'css', $style);
                    $url = Path::join(VENDI_CUSTOM_THEME_URL, 'css', $style);
                    $assetName = basename($style, '.css');
                    wp_enqueue_style('component-'.$assetName, $url, [], filemtime($assetPath));
                }
            }
        );
    }
}
