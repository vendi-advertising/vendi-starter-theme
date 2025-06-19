<?php

use Symfony\Component\Filesystem\Path;

add_action(
    'acfe/init',
    static function () {

        // We are using ACF's, not ACFE's
        acfe_update_setting( 'modules/post_types', false );
        acfe_update_setting( 'modules/taxonomies', false );

        // Please register all options in PHP (for now)
        acfe_update_setting( 'modules/options_pages', false );

        // We don't use these
        acfe_update_setting( 'modules/block_types', false );
        acfe_update_setting( 'modules/forms', false );
        acfe_update_setting( 'modules/scripts', false );
//        acf_update_setting('acfe/modules/scripts/demo', true);
    }
);

add_filter(
    'acfe/flexible/thumbnail',
    static function ( $thumbnail, $field, $layout ) {

        $relativeSharedThumbnailPaths = [
            VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME . '/%1$s/%1$s.thumbnail.png',
            VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME . '/%1$s/%1$s.png',
            'images/acf/component-thumbnails/%1$s.png',
        ];
        foreach ( $relativeSharedThumbnailPaths as $relativeSharedThumbnailPath ) {

            $relativePath = sprintf( $relativeSharedThumbnailPath, $layout['name'] );

            $filePathToTest = Path::join( VENDI_CUSTOM_THEME_PATH, $relativePath );
            if ( ! is_readable( $filePathToTest ) ) {
                continue;
            }

            return Path::join( VENDI_CUSTOM_THEME_URL, $relativePath );
        }

        return $thumbnail;
    },
    10,
    3
);

if ( is_admin() ) {
    add_action(
        'after_setup_theme',
        static function () {
            add_image_size( 'admin-icon', 40, 40 );
        }
    );
}

