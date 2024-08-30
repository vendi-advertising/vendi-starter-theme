<?php

use Symfony\Component\Filesystem\Path;

function vendi_perform_feature(string $featureName, string $actionName, array $args = []): void
{
    $fileName = Path::join(VENDI_CUSTOM_THEME_FEATURE_PATH, $featureName, "{$featureName}.{$actionName}.php");
    if (!is_readable($fileName)) {
        if (WP_DEBUG) {
            throw new InvalidArgumentException(sprintf('Feature %1$s with action %2$s does not exist', $featureName, $actionName));
        }

        return;
    }

    //This will hold a backup copy of the object state.
    //NOTE: If we aren't given an object state, we will intentionally be setting
    //the global state to null to avoid accidental usage which could lead to bad
    //programming practice.
    global $vendi_feature_object_state;
    $backup_state = $vendi_feature_object_state;

    if ($args && count($args)) {
        $vendi_feature_object_state = $args;
    } else {
        $vendi_feature_object_state = null;
    }

    if (function_exists('do_action')) {
        do_action('vendi/theme-features/pre-performing-feature', $featureName, $actionName, $args);
    }

    require $fileName;

    if (function_exists('do_action')) {
        do_action('vendi/theme-features/post-performing-feature', $featureName, $actionName, $args);
    }

    $vendi_feature_object_state = $backup_state;
}

function vendi_render_feature(string $featureName, array $args): void
{
    vendi_perform_feature($featureName, 'render', $args);
}

function vendi_feature_register_autoload(string $featureNamespace, string $path): void
{
    spl_autoload_register(
        static function ($class) use ($featureNamespace, $path) {

            $absoluteFeatureNamespace = 'Vendi\\Theme\\Feature\\'.$featureNamespace;

            // does the class use the namespace prefix?
            $len = strlen($absoluteFeatureNamespace);
            if (0 !== strncmp($absoluteFeatureNamespace, $class, $len)) {
                // no, move to the next registered prefix
                return;
            }

            // get the relative class name
            $relative_class = substr($class, $len);
            // replace the namespace prefix with the base directory, replace namespace
            // separators with directory separators in the relative class name, append
            // with .php
            $file = $path.str_replace('\\', '/', $relative_class).'.php';

            // if the file exists, require it
            if (file_exists($file)) {
                require_once $file;
            }
        }

    );
}

function vendi_feature_enqueue_style(string $featureName, array|bool|null $deps = [], string $media = 'all', ?string $when = null): void
{
    vendi_theme_enqueue_style(
        handle: "vendi-feature-css-{$featureName}",
        srcRelativeToThemeRoot: Path::join(VENDI_CUSTOM_THEME_FEATURE_FOLDER_NAME, $featureName, "{$featureName}.css"),
        deps: $deps,
        media: $media,
        when: $when,
    );
}

function vendi_feature_enqueue_script(string $featureName, array|bool|null $deps = [], bool|array $in_footer = false, ?string $when = null): void
{
    vendi_theme_enqueue_script(
        handle: "vendi-feature-js-{$featureName}",
        srcRelativeToThemeRoot: Path::join(VENDI_CUSTOM_THEME_FEATURE_FOLDER_NAME, $featureName, "{$featureName}.js"),
        deps: $deps,
        in_footer: $in_footer,
        when: $when,
    );
}

function vendi_feature_register_acf(array $mapping, string $dir): void
{
    foreach ($mapping as $key => $value) {
        $key = str_replace('.json', '', $key);
        add_filter(
            "acf/settings/save_json/key={$key}",
            static function ($paths) use ($dir) {
                return $dir.'/.acf-json';
            }
        );
    }

    add_filter(
        'acf/settings/load_json',
        static function ($paths) use ($dir) {
            $paths[] = $dir.'/.acf-json';

            return $paths;
        }
    );

    add_filter(
        'acf/json/save_file_name',
        static function ($filename) use ($mapping) {
            if (array_key_exists($filename, $mapping)) {
                return $mapping[$filename];
            }

            return $filename;
        }
    );
}
