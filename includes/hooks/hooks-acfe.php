<?php

use Symfony\Component\Filesystem\Path;

add_action(
    'acfe/init',
    static function () {

        // We are using ACF's, not ACFE's
        acfe_update_setting('modules/post_types', false);
        acfe_update_setting('modules/taxonomies', false);

        // Please register all options in PHP (for now)
        acfe_update_setting('modules/options_pages', false);

        // We don't use these
        acfe_update_setting('modules/block_types', false);
        acfe_update_setting('modules/forms', false);
        acfe_update_setting('modules/scripts', false);
//        acf_update_setting('acfe/modules/scripts/demo', true);
    }
);

add_filter(
    'acfe/flexible/thumbnail',
    static function ($thumbnail, $field, $layout) {

        $relativeSharedThumbnailPath = 'images/acf/component-thumbnails';

        // See if we have a special layout directory
        $absoluteSharedThumbnailPath = Path::join(VENDI_CUSTOM_THEME_PATH, $relativeSharedThumbnailPath);
        if (!is_dir($absoluteSharedThumbnailPath)) {
            return $thumbnail;
        }

        $componentThumbnailPath = Path::join($absoluteSharedThumbnailPath, $layout['name'].'.png');
        if (!is_readable($componentThumbnailPath)) {
            return $thumbnail;
        }

        return Path::join(VENDI_CUSTOM_THEME_URL, $relativeSharedThumbnailPath, $layout['name'].'.png');
    },
    10,
    3
);


add_filter(
    'acfe/flexible/render/template',
    static function ($file, $field, $layout, $is_preview) {
        if (!$layout = $layout['name'] ?? null) {
            return $file;
        }

        $filePathToTest = Path::join(VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'components', $layout, $layout.'.php');
        if (is_readable($filePathToTest)) {
            return $filePathToTest;
        }

        return $file;
    },
    accepted_args: 4
);


add_filter(
    'acfe/flexible/render/style',
    static function ($file, $field, $layout, $is_preview) {
        if (!$layout = $layout['name'] ?? null) {
            return $file;
        }

        if ($is_preview) {
            $filePathToTest = Path::join(VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'components', $layout, $layout.'.preview.css');
            if (is_readable($filePathToTest)) {
                return $filePathToTest;
            }
        }

        $filePathToTest = Path::join(VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'components', $layout, $layout.'.css');
        if (is_readable($filePathToTest)) {
            return $filePathToTest;
        }


        return $file;
    },
    accepted_args: 4
);

add_action(
    'init',
    static function () {
        add_rewrite_rule('^vendi-theme-preview/css/(.+)?$', 'index.php?vendi-theme-preview=css&vendi-theme-preview-file=$matches[1]', 'top');
        add_rewrite_rule('^vendi-theme-preview/shared-css/(.+)?$', 'index.php?vendi-theme-preview=shared-css&vendi-theme-preview-file=$matches[1]', 'top');
    }
);

add_filter(
    'query_vars',
    static function ($query_vars) {
        $query_vars[] = 'vendi-theme-preview';
        $query_vars[] = 'vendi-theme-preview-file';

        return $query_vars;
    }
);

add_filter(
    'template_include',
    static function ($template) {
        if (('shared-css' === get_query_var('vendi-theme-preview')) && $file = get_query_var('vendi-theme-preview-file')) {
            $fileToTest = Path::join(VENDI_CUSTOM_THEME_PATH, 'css', $file.'.css');
            if (is_readable($fileToTest)) {
                header('Content-Type: text/css');
                $css = file_get_contents($fileToTest);

                echo <<<EOT
.acfe-flexible-placeholder.-preview {
    $css
}
EOT;
                exit;
            }
        }

        if (('css' === get_query_var('vendi-theme-preview')) && $file = get_query_var('vendi-theme-preview-file')) {
            $fileToTest = Path::join(VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'components', $file, $file.'.css');
            if (is_readable($fileToTest)) {
                $css = file_get_contents($fileToTest);
                header('Content-Type: text/css');
                echo <<<EOT
.acfe-flexible-placeholder.-preview {
    $css
}
EOT;
                exit;
            }
        }

        return $template;
    }
);
