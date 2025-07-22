<?php

namespace Vendi\Theme;

use JsonException;
use Symfony\Component\Filesystem\Path;
use Vendi\Theme\Exception\UnknownComponentClassException;

final class ComponentUtility
{
    private static ?self $instance = null;

    private static array $registry = [];

    private array $componentStack = [];

    private function __construct()
    {
        // NOOP
    }

    public static function resetRegistry(): void
    {
        self::$registry = [];
    }

    public function getCurrentComponentStack(): array
    {
        return $this->componentStack;
    }

    public static function get_instance(): self
    {
        if ( ! self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get_next_id_for_component(string $componentName): int
    {
        if ( ! array_key_exists($componentName, self::$registry)) {
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

    public static function get_new_component_instance(string $className): VendiComponent
    {
//        global $vendi_theme_test_mode;
//        global $vendi_theme_test_data;

        if ( ! class_exists($className)) {
            throw new UnknownComponentClassException($className);
        }

//        if (true === $vendi_theme_test_mode) {
//        $reflect = new ReflectionClass($className);
//            $testClassName = $reflect->getNamespaceName() . '\\Test' . $reflect->getShortName();

//            if ( ! class_exists($testClassName)) {
//                throw new UnknownComponentClassException($testClassName);
//            }

//            $ret = new $testClassName($vendi_theme_test_data);
//        } else {
        $ret = new $className();
//        }

        if ($ret instanceof ComponentAwareOfGlobalStateInterface) {
            global $vendi_component_object_state;
            if (is_array($vendi_component_object_state)) {
                $ret->setGlobalState($vendi_component_object_state);
            }
        }

        return $ret;
    }

    public function loadTemplate(array|string $name, ?array $objectState = null, ?string &$errorMessage = null, ?ComponentInterface $component = null): bool
    {
        return $this->loadItem(VENDI_CUSTOM_THEME_TEMPLATE_FOLDER_NAME, $name, $objectState, $errorMessage, $component);
    }

    public function loadComponent(array|string $name, ?array $objectState = null, ?string &$errorMessage = null, ?ComponentInterface $component = null): bool
    {
        return $this->loadItem(VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME, $name, $objectState, $errorMessage, $component);
    }

    /**
     * @throws JsonException
     */
    private function loadItem(string $subPath, array|string $name, ?array $objectState = null, ?string &$errorMessage = null, ?ComponentInterface $component = null): bool
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

        $componentDirectory    = Path::join(...$fullPathParts);
        $componentDirectoryUrl = Path::join(...$fullUrlParts);

        // The terminal component name which is used for the folder/subfolder, as well as all
        // magically named files
        $trueComponentName = end($name);

        // This can help when debugging subcomponents
        $this->componentStack[] = $trueComponentName;

        // We no longer automatically generate component directories or files because this
        // ended up with weird edge-case things created on PROD servers
        if ( ! is_dir($componentDirectory)) {
            $componentStackMessage = implode(' -> ', $this->componentStack);
            $errorMessage          = 'Could not locate component directory: ' . $componentStackMessage;

            if (defined('WP_DEBUG') && WP_DEBUG) {
                trigger_error($errorMessage, E_USER_WARNING);
            }

            return false;
        }

        // For basic-copy, we're ultimately looking for something like:
        // vendi-theme-parts/components/basic-copy/basic-copy.php
        $componentFile = Path::join($componentDirectory, $trueComponentName . '.php');

        if ( ! is_readable($componentFile)) {
            $componentStackMessage = implode(' -> ', $this->componentStack);
            $errorMessage          = 'Could not locate component file: ' . $componentStackMessage;

            if (defined('WP_DEBUG') && WP_DEBUG) {
                trigger_error($errorMessage, E_USER_WARNING);
            }

            return false;
        }

        $optionalFiles = [
            // Autoload and hooks
            'autoload'   => $trueComponentName . '.autoload.php',
            'hooks'      => $trueComponentName . '.hooks.php',

            // Helper class and base class
            'class-base' => $trueComponentName . '.base.php',
            'class'      => $trueComponentName . '.class.php',
        ];

//        $previewCssFile    = Path::join($componentDirectory, $trueComponentName . '.preview.css');
        $previewBeforeFile = Path::join($componentDirectory, $trueComponentName . '.preview.before.php');
        $previewAfterFile  = Path::join($componentDirectory, $trueComponentName . '.preview.after.php');

        foreach ($optionalFiles as $key => $file) {
            $optionalFile = Path::join($componentDirectory, $file);

            if ( ! is_readable($optionalFile)) {
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
                );
            }
        }

        if ($component) {
            $objectState['component'] = $component;
        }

        // We still occasionally have a need to pass state to components. This is almost exclusively
        // used for subcomponents. The below code backs up the global state, sets the new state, and
        // invokes the component and then restores it.
        global $vendi_component_object_state;
        $backup_state = $vendi_component_object_state;

        if ($objectState && count($objectState)) {
            $vendi_component_object_state = $objectState;
        } else {
            $vendi_component_object_state = null;
        }

        do_action(
            'vendi/component-loader/loading-layout',
            $trueComponentName,
            $componentDirectory,
            $componentDirectoryUrl,
            $objectState,
        );

        $this->maybeEnqueueComponentAssetStyles($trueComponentName, $componentDirectoryUrl, $componentDirectory);
        $this->maybeEnqueueComponentStyle($trueComponentName, $componentDirectoryUrl, $componentDirectory, $enqueueKey);
        $this->maybeEnqueueComponentScript($trueComponentName, $componentDirectoryUrl, $componentDirectory, $enqueueKey);
        $this->maybeEnqueueComponentScripts($trueComponentName, $componentDirectoryUrl, $componentDirectory);
        $this->maybeEnqueueComponentStyles($trueComponentName, $componentDirectoryUrl, $componentDirectory);

        global $is_preview;

        if (true === $is_preview) {
            $this->maybeEnqueueComponentStyle($trueComponentName, $componentDirectoryUrl, $componentDirectory, $enqueueKey . '-preview', 'preview.css');
        }

        if (true === $is_preview && is_readable($previewBeforeFile)) {
            include $previewBeforeFile;
        }

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
            );
        } finally {
            array_pop($this->componentStack);
        }

        if (true === $is_preview && is_readable($previewAfterFile)) {
            include $previewAfterFile;
        }

        $vendi_component_object_state = $backup_state;

        return true;
    }

    private function maybeEnqueueComponentStyle($componentName, $componentDirectoryUrl, $componentDirectory, $enqueueKey, string|array $extensions = ['inline-head.css', 'css']): void
    {
        if (is_string($extensions)) {
            $extensions = [$extensions];
        }

        $this->maybeInvokeCallbackIfComponentFileExists(
            $componentName,
            $extensions,
            $componentDirectory,
            $componentDirectoryUrl,
            function ($componentName, $assetUrl, $assetPath, $extension) use ($enqueueKey) {
                $id = 'component-' . $enqueueKey;
                if ('inline-head.css' === $extension) {
                    $fsPathRelative = Path::makeRelative($assetUrl, VENDI_CUSTOM_THEME_URL);
                    $fsPathAbsolute = Path::join(VENDI_CUSTOM_THEME_PATH, $fsPathRelative);
                    $this->addStyleToHead($assetUrl, $fsPathAbsolute, $id);
                } else {
                    wp_enqueue_style($id, $assetUrl, [], filemtime($assetPath));
                }
            },
        );
    }

    private function maybeEnqueueComponentScript($componentName, $componentDirectoryUrl, $componentDirectory, $enqueueKey): void
    {
        $this->maybeInvokeCallbackIfComponentFileExists(
            $componentName,
            ['min.js', 'js'],
            $componentDirectory,
            $componentDirectoryUrl,
            static function ($componentName, $assetUrl, $assetPath) use ($enqueueKey) {
                wp_enqueue_script('component-' . $enqueueKey, $assetUrl, [], filemtime($assetPath), true);
            },
        );
    }

    private function maybeEnqueueComponentAssetFilesItems($componentName, $componentDirectoryUrl, $componentDirectory, $assetKey, $callback): void
    {
        $this->maybeInvokeCallbackIfComponentFileExists(
            $componentName,
            '.assets.json',
            $componentDirectory,
            $componentDirectoryUrl,
            static function ($componentName, $assetUrl, $assetPath) use ($assetKey, $callback) {
                $items = json_decode(file_get_contents($assetPath), true, 512, JSON_THROW_ON_ERROR);

                if ( ! $items = $items[$assetKey] ?? null) {
                    return;
                }

                $callback($componentName, $assetUrl, $assetPath, $items);
            },
        );
    }

    /**
     * @throws JsonException
     */
    private function maybeEnqueueComponentAssetStyles($componentName, $componentDirectoryUrl, $componentDirectory): void
    {
        $this->maybeEnqueueComponentAssetFilesItems(
            $componentName,
            $componentDirectoryUrl,
            $componentDirectory,
            'global-styles',
            function ($componentName, $assetUrl, $assetPath, $items) {
                // TODO: This needs to be tested, and is probably wrong
                foreach ($items as $style) {
                    if (str_starts_with($style, '@')) {
                        do_action(
                            'vendi/component-loader/load-custom-style-asset',
                            $style,
                        );
                        continue;
                    }

                    $assetPath = Path::join(VENDI_CUSTOM_THEME_PATH, 'css', $style);
                    $url       = Path::join(VENDI_CUSTOM_THEME_URL, 'css', $style);
                    $assetName = basename($style, '.css');
                    wp_enqueue_style('component-' . $assetName, $url, [], filemtime($assetPath));
                }
            },
        );
    }

    /**
     * @throws JsonException
     */
    private function maybeEnqueueComponentStyles($componentName, $componentDirectoryUrl, $componentDirectory): void
    {
        $this->maybeEnqueueComponentAssetFilesItems(
            $componentName,
            $componentDirectoryUrl,
            $componentDirectory,
            'styles',
            function ($componentName, $assetUrl, $assetPath, $items) {
                $assetUrlBase = Path::getDirectory($assetUrl);
                foreach ($items as $item) {
                    $placement = 'head';
                    if (is_array($item)) {
                        if ( ! array_key_exists('file', $item)) {
                            continue;
                        }

                        $placement = $item['placement'] ?? 'head';
                        $item      = $item['file'];
                    }

                    $url = Path::join($assetUrlBase, $item);

                    if (str_starts_with($item, '@')) {
                        do_action(
                            'vendi/component-loader/load-custom-style-asset',
                            $item,
                        );
                        continue;
                    }

                    $fsPathRelative = Path::makeRelative($url, VENDI_CUSTOM_THEME_URL);
                    $fsPathAbsolute = Path::join(VENDI_CUSTOM_THEME_PATH, $fsPathRelative);


                    // Replace anything that isn't a letter or number with a dash
                    $id = 'component-' . $componentName . '-' . $item;
                    $id = preg_replace('/[^a-zA-Z0-9]/', '-', $id);

                    if ('head' === $placement) {
                        wp_enqueue_style($id, $url, [], filemtime($fsPathAbsolute));
                        continue;
                    }

                    if ('inline-head' === $placement) {
                        $this->addStyleToHead($url, $fsPathAbsolute, $id);
                    }
                }
            },
        );
    }

    private function addStyleToHead($url, $absolutePath, $id): void
    {
        $assetDir    = Path::getDirectory($url);
        $inlineStyle = file_get_contents($absolutePath);
        if ( ! $inlineStyle) {
            return;
        }

        // If we are inlining a style that has URLs, we need to replace them with the correct path
        if (preg_match_all('/url\(\s?(["\']?)(?<url>[^"\')]+)\1\)/', $inlineStyle, $matches)) {
            foreach ($matches['url'] as $cssUrl) {
                $inlineStyle = str_replace($cssUrl, Path::join($assetDir, $cssUrl), $inlineStyle);
            }
        }

        $inlineStyle = str_replace(["\r", "\n"], '', $inlineStyle);
        $inlineStyle = preg_replace('/\s+/', ' ', $inlineStyle);

        add_action(
            'wp_head',
            static function () use ($id, $inlineStyle) {
                echo '<style id="' . esc_attr($id) . '" type="text/css">';
                echo $inlineStyle;
                echo '</style>';
            },
        );

        do_action(
            'vendi/component-loader/style-inline',
            $inlineStyle,
            $id,
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
                    if (str_starts_with($script, '@')) {
                        do_action(
                            'vendi/component-loader/load-custom-script-asset',
                            $script,
                        );
                        continue;
                    }

                    $url            = Path::join($assetUrlBase, $script);
                    $fsPathRelative = Path::makeRelative($url, VENDI_CUSTOM_THEME_URL);
                    $fsPathAbsolute = Path::join(VENDI_CUSTOM_THEME_PATH, $fsPathRelative);

                    if ( ! str_ends_with($fsPathAbsolute, '.min.js')) {
                        $fsPathAbsoluteWithMin = Path::changeExtension($fsPathAbsolute, '.min.js');
                        if (is_readable($fsPathAbsoluteWithMin)) {
                            $fsPathAbsolute = $fsPathAbsoluteWithMin;
                            $url            = Path::changeExtension($url, '.min.js');
                        }
                    }

                    wp_enqueue_script('component-' . $componentName . '-' . $script, $url, [], filemtime($fsPathAbsolute), true);
                }
            },
        );
    }

    private function maybeInvokeCallbackIfComponentFileExists(string $componentName, string|array $extensions, string $componentDirectory, string $componentDirectoryUrl, callable $callback): void
    {
        if (is_string($extensions)) {
            $extensions = [$extensions];
        }

        foreach ($extensions as $extension) {
            $assetPath = Path::join($componentDirectory, $componentName . '.' . ltrim($extension, '.'));
            $assetUrl  = Path::join($componentDirectoryUrl, $componentName . '.' . ltrim($extension, '.'));

            if (is_readable($assetPath)) {
                $callback($componentName, $assetUrl, $assetPath, $extension);

                return;
            }
        }
    }
}
