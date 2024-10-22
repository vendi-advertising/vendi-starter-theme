<?php

use Symfony\Component\Filesystem\Path;
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
        return VENDI_CUSTOM_THEME_PATH.'/.acf-json';
    },
);

add_filter(
    'acf/settings/load_json',
    static function ($paths) {
        return [
            VENDI_CUSTOM_THEME_PATH.'/.acf-json',
            VENDI_CUSTOM_THEME_PATH.'/.acf-json/'.VendiComponentsAcfMagicFolderEnum::COMPONENTS->value,
            VENDI_CUSTOM_THEME_PATH.'/.acf-json/'.VendiComponentsAcfMagicFolderEnum::ENTITIES->value,
            VENDI_CUSTOM_THEME_PATH.'/.acf-json/'.VendiComponentsAcfMagicFolderEnum::BACKGROUNDS->value,
            VENDI_CUSTOM_THEME_PATH.'/.acf-json/'.VendiComponentsAcfMagicFolderEnum::FIELDSETS->value,
            VENDI_CUSTOM_THEME_PATH.'/.acf-json/'.VendiComponentsAcfMagicFolderEnum::THEME_SETTINGS->value,
            VENDI_CUSTOM_THEME_PATH.'/.acf-json/'.VendiComponentsAcfMagicFolderEnum::BASE_COMPONENT->value,
            VENDI_CUSTOM_THEME_PATH.'/.acf-json/'.VendiComponentsAcfMagicFolderEnum::FIELDS->value,
            VENDI_CUSTOM_THEME_PATH.'/.acf-json/'.VendiComponentsAcfMagicFolderEnum::ENTITY_FIELDS->value,
            VENDI_CUSTOM_THEME_PATH.'/.acf-json/'.VendiComponentsAcfMagicFolderEnum::OPTION_PAGES->value,
        ];
    },
);

add_filter(
    'sanitize_file_name',
    static function ($filename, $filename_raw) {
        if (!str_contains($filename_raw, VENDI_COMPONENTS_ACF_MAGIC_FOLDER_PATH_IDENTIFIER)) {
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
        ];

        if (!in_array($parts[0], $knownGoodFolders, true)) {
            return $filename;
        }

        return $parts[0].'/'.$parts[1];
    },
    accepted_args: 2,
);

add_filter(
    'acf/json/save_file_name',
    static function ($filename, $post, $load_path) {
        static $b = [
            VendiComponentsAcfMagicFolderEnum::COMPONENTS->value => [
                'group_6599ece5e2264.json' => 'accordion.json',
                'group_659c0a262f75a.json' => 'basic-copy.json',
                'group_659c754d6a1d2.json' => 'blockquote.json',
                'group_659c272fb8f1f.json' => 'callout.json',
                'group_659c6e7fc386c.json' => 'figure.json',
                'group_659c445704039.json' => 'reusable-content.json',
                'group_6599ae10d9002.json' => 'testimonial.json',
                'group_66196f1f7658c.json' => 'call-to-action.json',
                'group_661989c1d0e74.json' => 'stepwise.json',
                'group_661d782329c85.json' => 'image-grid.json',
                'group_6695804de3410.json' => 'columns.json',
                'group_66a9355627020.json' => 'stats.json',
                'group_66acef245de12.json' => 'image-gallery.json',
                'group_66aa473bce3b3.json' => 'form.json',
            ],
            VendiComponentsAcfMagicFolderEnum::ENTITIES->value => [
                'post_type_66ba8537bd649' => 'training-hub.json',

                'post_type_6599ab6f6b61a.json' => 'testimonial.json',


                'post_type_65f3571fbc97e.json' => 'alert.json',


                'post_type_659c2c3567923.json' => 'reusable-content.json',

                'post_type_6691845c67ba8.json' => 'reusable-background.json',


            ],
            VendiComponentsAcfMagicFolderEnum::BACKGROUNDS->value => [
                'group_66902182309cc.json' => 'background-color.json',
                'group_66901f65435d7.json' => 'background-image.json',
                'group_669022c556e50.json' => 'background-linear-gradient.json',
                'group_66918627662b4.json' => 'background-reusable-background.json',
                'group_66a80a6a0ee49.json' => 'background-video.json',
            ],
            VendiComponentsAcfMagicFolderEnum::FIELDSETS->value => [
                'group_66901f4b7739f.json' => 'backgrounds.json',
                'group_66ba1140c9524.json' => 'content-area-settings.json',
            ],
            VendiComponentsAcfMagicFolderEnum::THEME_SETTINGS->value => [
                'group_61d8a393c5415.json' => 'theme-settings-fields.json',

                'group_62348a640fc68.json' => 'theme-settings-tab-global-javascript.json',

                'group_661435d8a956b.json' => 'theme-settings-sso.json',
                'group_6615bc1d65360.json' => 'theme-settings-sso-provider-github.json',
                'group_66143631ddd8b.json' => 'theme-settings-sso-provider-azure.json',
                'group_6619abc83577b.json' => 'theme-settings-sso-provider-google.json',
            ],
            VendiComponentsAcfMagicFolderEnum::BASE_COMPONENT->value => [
                'group_66aced8597513.json' => 'base-component.json',
                'group_66fc4b4a790ac.json' => 'base-component-tab-header.json',
                'group_66fc4b1253bca.json' => 'base-component-tab-background.json',
                'group_66fc4a1a9dd82.json' => 'base-component-tab-content-settings.json',
            ],
            VendiComponentsAcfMagicFolderEnum::FIELDS->value => [
                'group_66a94415b1615.json' => 'color.json',
                'group_66aa465159585.json' => 'content-placement.json',
                'group_66aa45d83feeb.json' => 'content-width.json',
            ],
            VendiComponentsAcfMagicFolderEnum::ENTITY_FIELDS->value => [
                'group_6599abaa59f84.json' => 'testimonial-fields.json',
                'group_65f357e01af5b.json' => 'alert-fields.json',
                'group_6691856be84f6.json' => 'reusable-background-fields.json',
            ],
            VendiComponentsAcfMagicFolderEnum::OPTION_PAGES->value => [
                'ui_options_page_66143503e5c73.json' => 'option-page-theme-settings.json',
                'ui_options_page_661435b551158.json' => 'option-page-theme-settings-sso.json',
            ],
        ];

        foreach ($b as $folder => $mapping) {
            if (array_key_exists($filename, $mapping)) {
                return $folder.VENDI_COMPONENTS_ACF_MAGIC_FOLDER_PATH_IDENTIFIER.$mapping[$filename];
            }
        }

        static $mapping = [

            'group_66195515aacef.json' => 'accordion-item-shared.json',


            'group_661970fa6651c.json' => 'sub-component-call-to-action-button.json',
            'group_6616dde078cf0.json' => 'sub-component-grid-component-wrapper.json',
            'group_66198b6ededc6.json' => 'sub-component-stepwise-item-simple.json',
            'group_661d79b2aeb6d.json' => 'sub-component-image-grid-item.json',

            'group_6616f4a3c869c.json' => 'settings-modal-grid-settings.json',
            'group_6616f3340ebca.json' => 'settings-modal-grid-component.json',
            'group_6616f42278eda.json' => 'settings-modal-grid-row.json',
            'group_661809923e61a.json' => 'settings-modal-components.json',

            'group_6599af50880a8.json' => 'components-shared-component-settings-tab.json',
            'group_61dc8e97982a7.json' => 'content-components.json',


            'group_661983686d448.json' => 'tab-fields-headings.json',
        ];

        if (array_key_exists($filename, $mapping)) {
            return $mapping[$filename];
        }

        return $filename;
    },
    accepted_args: 3,
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
