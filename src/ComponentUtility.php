<?php

namespace Vendi\Theme;

use JsonException;
use ReflectionClass;
use Symfony\Component\Filesystem\Path;
use Vendi\Theme\Exception\UnknownComponentClassException;

final class ComponentUtility
{
    private static ?ComponentUtility $instance = null;

    private static array $registry = [];

    private array $componentStack = [];

    private function __construct()
    {
        // NOOP
    }

    public function getCurrentComponentStack(): array
    {
        return $this->componentStack;
    }

    public static function get_instance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get_next_id_for_component(string $componentName): int
    {
        if (!array_key_exists($componentName, self::$registry)) {
            self::$registry[$componentName] = [];
        }

        $nextId = count(self::$registry[$componentName]);

        self::$registry[$componentName][] = $nextId;

        return $nextId;
    }

    public function get_next_id_for_component_with_component_name(string $componentName): string
    {
        $id = $this->get_next_id_for_component($componentName);

        return sprintf('%1$s-%2$d', $componentName, $id);
    }

    public static function get_new_component_instance(string $className): BaseComponent
    {
        global $vendi_theme_test_mode;
        global $vendi_theme_test_data;

        if (!class_exists($className)) {
            throw new UnknownComponentClassException($className);
        }

        if (true === $vendi_theme_test_mode) {
            $reflect = new ReflectionClass($className);
            $testClassName = $reflect->getNamespaceName().'\\Test'.$reflect->getShortName();

            if (!class_exists($testClassName)) {
                throw new UnknownComponentClassException($testClassName);
            }

            return new $testClassName($vendi_theme_test_data);
        }

        return new $className();
    }

    public function loadTemplate(array|string $name, ?array $objectState = null, ?string &$errorMessage = null): bool
    {
        return $this->loadItem(VENDI_CUSTOM_THEME_TEMPLATE_FOLDER_NAME, $name, $objectState, $errorMessage);
    }

    public function loadComponent(array|string $name, ?array $objectState = null, ?string &$errorMessage = null): bool
    {
        return $this->loadItem(VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME, $name, $objectState, $errorMessage);
    }

    /**
     * @throws JsonException
     */
    private function loadItem(string $subPath, array|string $name, ?array $objectState = null, ?string &$errorMessage = null): bool
    {
        // We support ['header'/'thing'] and 'header/thing' for names
        $name = is_string($name) ? explode('/', $name) : $name;

        // If two subcomponents use the same name, we need to differentiate them in the enqueue key
        // otherwise the second one will overwrite the first.
        $enqueueKey = implode('-', $name);

        // Just in case we get nulls/empties
        $localPathParts = array_filter($name);

        // Build the full path and URL
        $fullPathParts = [
            VENDI_CUSTOM_THEME_PATH,
            $subPath,
            ...$localPathParts,
        ];
        $fullPathParts = array_filter($fullPathParts);

        $fullUrlParts = [
            VENDI_CUSTOM_THEME_URL,
            $subPath,
            ...$localPathParts,
        ];
        $fullUrlParts = array_filter($fullUrlParts);

        $componentDirectory = Path::join(...$fullPathParts);
        $componentDirectoryUrl = Path::join(...$fullUrlParts);

        // The terminal component name which is used for the folder/subfolder, as well as all
        // magically named files
        $trueComponentName = end($name);

        // This can help when debugging subcomponents
        $this->componentStack[] = $trueComponentName;

        // We no longer automatically generate component directories or files because this
        // ended up with weird edge-case things created on PROD servers
        if (!is_dir($componentDirectory)) {
            $componentStackMessage = implode(' -> ', $this->componentStack);
            $errorMessage = 'Could not locate component directory: '.$componentStackMessage;

            if (defined('WP_DEBUG') && WP_DEBUG) {
                trigger_error($errorMessage, E_USER_WARNING);
            }

            return false;
        }

        // For basic-copy, we're ultimately looking for something like:
        // vendi-theme-parts/components/basic-copy/basic-copy.php
        $componentFile = Path::join($componentDirectory, $trueComponentName.'.php');

        if (!is_readable($componentFile)) {
            $componentStackMessage = implode(' -> ', $this->componentStack);
            $errorMessage = 'Could not locate component file: '.$componentStackMessage;

            if (defined('WP_DEBUG') && WP_DEBUG) {
                trigger_error($errorMessage, E_USER_WARNING);
            }

            return false;
        }

        $optionalFiles = [
            'class-base' => $trueComponentName.'.base.php',

            // Helper class
            'class' => $trueComponentName.'.class.php',

            // Autoload file
            'autoload' => $trueComponentName.'.autoload.php',

            // Test class
            'test' => 'test/'.$trueComponentName.'.test.class.php',
        ];

        foreach ($optionalFiles as $key => $file) {
            $optionalFile = Path::join($componentDirectory, $file);

            if (!is_readable($optionalFile)) {
                continue;
            }

            try {
                require_once $optionalFile;
            } catch (\Exception $e) {
                // We are intentionally not swallowing this exception because something is very wrong
                trigger_error(
                    sprintf(
                        'Could not load optional file "%1$s" for component "%2$s". The error was: %3$s',
                        $file,
                        $trueComponentName,
                        $e->getMessage(),
                    ),
                    E_USER_ERROR,
                );
            }
        }

        // We still occasionally have a need to pass state to components. This is almost exclusively
        // used for subcomponents. The below code backs up the global state, sets the new state, and
        // invokes the component and then restores it.
        global $vendi_layout_component_object_state;
        $backup_state = $vendi_layout_component_object_state;

        if ($objectState && count($objectState)) {
            $vendi_layout_component_object_state = $objectState;
        } else {
            $vendi_layout_component_object_state = null;
        }

        do_action(
            'vendi/component-loader/loading-layout',
            $trueComponentName,
            $componentDirectory,
            $componentDirectoryUrl,
            $objectState,
        );

        $this->maybeEnqueueComponentGlobalStyles($trueComponentName, $componentDirectoryUrl, $componentDirectory);
        $this->maybeEnqueueComponentStyle($trueComponentName, $componentDirectoryUrl, $componentDirectory, $enqueueKey);
        $this->maybeEnqueueComponentScript($trueComponentName, $componentDirectoryUrl, $componentDirectory, $enqueueKey);
        $this->maybeEnqueueComponentScripts($trueComponentName, $componentDirectoryUrl, $componentDirectory);

        try {
            include $componentFile;
        } catch (\Throwable $e) {
            $componentStackMessage = implode(' -> ', $this->componentStack);
            trigger_error(
                sprintf(
                    'Could not load component "%1$s". The error was: %2$s',
                    $componentStackMessage,
                    $e->getMessage(),
                ),
                E_USER_ERROR,
            );
        } finally {
            array_pop($this->componentStack);
        }

        $vendi_layout_component_object_state = $backup_state;

        return true;
    }

    private function maybeEnqueueComponentStyle($componentName, $componentDirectoryUrl, $componentDirectory, $enqueueKey): void
    {
        $this->maybeInvokeCallbackIfComponentFileExists(
            $componentName,
            'css',
            $componentDirectory,
            $componentDirectoryUrl,
            static function ($componentName, $assetUrl, $assetPath) use ($enqueueKey) {
                wp_enqueue_style('component-'.$enqueueKey, $assetUrl, [], filemtime($assetPath));
            },
        );
    }

    private function maybeEnqueueComponentScript($componentName, $componentDirectoryUrl, $componentDirectory, $enqueueKey): void
    {
        $this->maybeInvokeCallbackIfComponentFileExists(
            $componentName,
            'js',
            $componentDirectory,
            $componentDirectoryUrl,
            static function ($componentName, $assetUrl, $assetPath) use ($enqueueKey) {
                wp_enqueue_script('component-'.$enqueueKey, $assetUrl, [], filemtime($assetPath), true);
            },
        );
    }

    private function maybeEnqueueComponentAssetFilesItems($componentName, $componentDirectoryUrl, $componentDirectory, $assetKey, $callback): void
    {
        $this->maybeInvokeCallbackIfComponentFileExists(
        /**
         * @throws JsonException
         */ $componentName,
            '.assets.json',
            $componentDirectory,
            $componentDirectoryUrl,
            static function ($componentName, $assetUrl, $assetPath) use ($assetKey, $callback) {
                $items = json_decode(file_get_contents($assetPath), true, 512, JSON_THROW_ON_ERROR);

                if (!$items = $items[$assetKey] ?? null) {
                    return;
                }

                $callback($componentName, $assetUrl, $assetPath, $items);
            },
        );
    }

    /**
     * @throws JsonException
     */
    private function maybeEnqueueComponentGlobalStyles($componentName, $componentDirectoryUrl, $componentDirectory): void
    {
        $this->maybeEnqueueComponentAssetFilesItems(
            $componentName,
            $componentDirectoryUrl,
            $componentDirectory,
            'global-styles',
            function ($componentName, $assetUrl, $assetPath, $items) {
                // TODO: This needs to be tested, and is probably wrong
                foreach ($items as $style) {
                    $assetPath = Path::join(VENDI_CUSTOM_THEME_PATH, 'css', $style);
                    $url = Path::join(VENDI_CUSTOM_THEME_URL, 'css', $style);
                    $assetName = basename($style, '.css');
                    wp_enqueue_style('component-'.$assetName, $url, [], filemtime($assetPath));
                }
            },
        );
    }

    /**
     * @throws JsonException
     */
    private function maybeEnqueueComponentScripts($componentName, $componentDirectoryUrl, $componentDirectory): void
    {
        $this->maybeEnqueueComponentAssetFilesItems(
            $componentName,
            $componentDirectoryUrl,
            $componentDirectory,
            'scripts',
            function ($componentName, $assetUrl, $assetPath, $items) {
                $assetUrlBase = Path::getDirectory($assetUrl);
                foreach ($items as $script) {
                    $url = Path::join($assetUrlBase, $script);

                    // The filemtime is wrong, it is calculating based on the JSON file, not the actual script files
                    wp_enqueue_script('component-'.$componentName.'-'.$script, $url, [], filemtime($assetPath), true);
                }
            },
        );
    }

    private function maybeInvokeCallbackIfComponentFileExists(string $componentName, string $extension, string $componentDirectory, string $componentDirectoryUrl, callable $callback): void
    {
        $assetPath = Path::join($componentDirectory, $componentName.'.'.ltrim($extension, '.'));
        $assetUrl = Path::join($componentDirectoryUrl, $componentName.'.'.ltrim($extension, '.'));

        if (is_readable($assetPath)) {
            $callback($componentName, $assetUrl, $assetPath);
        }
    }
}
