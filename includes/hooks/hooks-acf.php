<?php

use Vendi\Theme\VendiComponentsAcfMagicFolderEnum;

/**
 * We have not yet identified a way to save _and_ load using subfolders
 * and an obvious and consistent pattern. Loading is easy, as long as we
 * limit the folders to a pre-defined list. Saving, however, would require
 * either creating a hook per field group, which is a lot, or performing some
 * sort of logic, possibly in acf/json/save_path.
 *
 * The fix for now is to just include the folder name in the filename. Unfortunately,
 * ACF routes this name through WordPress's sanitization engine so we can't just
 * use a slash. Instead, we use a magic string that we can later convert back, and
 * ACF will just concatenate it into a full path for us.
 */
const VENDI_COMPONENTS_ACF_MAGIC_FOLDER_PATH_IDENTIFIER = '|/|';


/**
 * These two hooks synchronize ACF fields to JSON files on disk in order to
 * support storing them in source control.
 */
add_filter(
    'acf/settings/save_json',
    static function ($paths) {
        return VENDI_CUSTOM_THEME_PATH . '/.acf-json';
    },
);

add_filter(
    'acf/settings/load_json',
    static function ($paths) {
        return [
            VENDI_CUSTOM_THEME_PATH . '/.acf-json',
            //            VENDI_CUSTOM_THEME_PATH . '/.acf-json/' . VendiComponentsAcfMagicFolderEnum::COMPONENTS->value,
            VENDI_CUSTOM_THEME_PATH . '/.acf-json/' . VendiComponentsAcfMagicFolderEnum::ENTITIES->value,
            VENDI_CUSTOM_THEME_PATH . '/.acf-json/' . VendiComponentsAcfMagicFolderEnum::BACKGROUNDS->value,
            VENDI_CUSTOM_THEME_PATH . '/.acf-json/' . VendiComponentsAcfMagicFolderEnum::FIELDSETS->value,
            VENDI_CUSTOM_THEME_PATH . '/.acf-json/' . VendiComponentsAcfMagicFolderEnum::THEME_SETTINGS->value,
            VENDI_CUSTOM_THEME_PATH . '/.acf-json/' . VendiComponentsAcfMagicFolderEnum::BASE_COMPONENT->value,
            VENDI_CUSTOM_THEME_PATH . '/.acf-json/' . VendiComponentsAcfMagicFolderEnum::FIELDS->value,
            VENDI_CUSTOM_THEME_PATH . '/.acf-json/' . VendiComponentsAcfMagicFolderEnum::ENTITY_FIELDS->value,
            VENDI_CUSTOM_THEME_PATH . '/.acf-json/' . VendiComponentsAcfMagicFolderEnum::OPTION_PAGES->value,
            VENDI_CUSTOM_THEME_PATH . '/.acf-json/' . VendiComponentsAcfMagicFolderEnum::SETTINGS_MODAL->value,
            //            VENDI_CUSTOM_THEME_PATH . '/.acf-json/' . VendiComponentsAcfMagicFolderEnum::SUBCOMPONENTS->value,
        ];
    },
);

add_filter(
    'sanitize_file_name',
    static function ($filename, $filename_raw) {
        if ( ! str_contains($filename_raw, VENDI_COMPONENTS_ACF_MAGIC_FOLDER_PATH_IDENTIFIER)) {
            return $filename;
        }

        $parts = explode(VENDI_COMPONENTS_ACF_MAGIC_FOLDER_PATH_IDENTIFIER, $filename_raw);
        if (count($parts) !== 2) {
            return $filename;
        }

        $knownGoodFolders = [
            VendiComponentsAcfMagicFolderEnum::COMPONENTS->value,
            VendiComponentsAcfMagicFolderEnum::ENTITIES->value,
            VendiComponentsAcfMagicFolderEnum::BACKGROUNDS->value,
            VendiComponentsAcfMagicFolderEnum::FIELDSETS->value,
            VendiComponentsAcfMagicFolderEnum::THEME_SETTINGS->value,
            VendiComponentsAcfMagicFolderEnum::BASE_COMPONENT->value,
            VendiComponentsAcfMagicFolderEnum::FIELDS->value,
            VendiComponentsAcfMagicFolderEnum::ENTITY_FIELDS->value,
            VendiComponentsAcfMagicFolderEnum::OPTION_PAGES->value,
            VendiComponentsAcfMagicFolderEnum::SETTINGS_MODAL->value,
            VendiComponentsAcfMagicFolderEnum::SUBCOMPONENTS->value,
        ];

        if ( ! in_array($parts[0], $knownGoodFolders, true)) {
            return $filename;
        }

        return $parts[0] . '/' . $parts[1];
    },
    accepted_args: 2,
);

add_action(
    'admin_enqueue_scripts',
    static function () {
        ?>
        <style>
            [data-layout~=grid] > .acf-fields {
                background-color: #cbe5ff;

                .acf-input .button[data-name~=add-layout] {
                    background-color: #64b3fb;
                    color: white;
                }

                [data-layout~=row] > .acf-fields {
                    background-color: #e8efa9;

                    .acf-input .button[data-name~=add-layout] {
                        background-color: #9ea359;
                        color: white;
                    }

                    [data-layout~=components] > .acf-fields {
                        background-color: #e2bef1;

                        .acf-input .button[data-name~=add-layout] {
                            background-color: #cc56fd;
                            color: white;
                        }
                    }
                }
            }
        </style>
        <?php
    },
);

add_action(
    'admin_enqueue_scripts',
    static function () {
        //wp-content/themes/iana-theme/admin-features/js/admin-button-styles.js
        wp_enqueue_script(
            'vendi-admin-button-styles',
            VENDI_CUSTOM_THEME_URL . '/admin-features/js/admin-button-styles.js',
            [],
            null,
            true,
        );
    },
);
