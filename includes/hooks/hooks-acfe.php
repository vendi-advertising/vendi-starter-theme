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

if (is_admin()) {
    add_action(
        'after_setup_theme',
        static function () {
            add_image_size('admin-icon', 40, 40);
        }
    );
}

