<?php

/**
 * These two hooks synchronize ACF fields to JSON files on disk in order to
 * support storing them in source control.
 */

add_filter(
    'acf/settings/save_json',
    static function ($paths) {
        return VENDI_CUSTOM_THEME_PATH.'/.acf-json';
    }
);

add_filter(
    'acf/settings/load_json',
    static function ($paths) {
        return [VENDI_CUSTOM_THEME_PATH.'/.acf-json'];
    }
);

//if (function_exists('acf_add_options_page')) {
//    acf_add_options_page(
//        [
//            'page_title' => 'Theme Settings',
//            'menu_title' => 'Theme Settings',
//            'menu_slug' => 'theme_settings',
//            'capability' => 'manage_options',
//        ]
//    );
//}

add_filter(
    'acf/json/save_file_name',
    static function ($filename, $post, $load_path) {
        static $mapping = [
            'group_6599ece5e2264.json' => 'component-accordion.json',
            'group_66195515aacef.json' => 'accordion-item-shared.json',

            'group_659c0a262f75a.json' => 'component-basic-copy.json',
            'group_659c754d6a1d2.json' => 'component-blockquote.json',
            'group_659c272fb8f1f.json' => 'component-callout.json',
            'group_659c6e7fc386c.json' => 'component-figure.json',
            'group_659c445704039.json' => 'component-reusable-content.json',
            'group_6599ae10d9002.json' => 'component-testimonial.json',
            'group_6616dc2346a45.json' => 'component-grid.json',
            'group_6618398196a9f.json' => 'component-grid-empty-cell.json',
            'group_66196f1f7658c.json' => 'component-call-to-action.json',
            'group_661989c1d0e74.json' => 'component-stepwise.json',
            'group_661d782329c85.json' => 'component-image-grid.json',

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

            'group_6599abaa59f84.json' => 'entity-testimonial.json',
            'group_65f357e01af5b.json' => 'entity-alert.json',

            'group_61d8a393c5415.json' => 'theme-settings-fields.json',
            'group_62348a640fc68.json' => 'theme-settings-tab-global-javascript.json',

            'group_661435d8a956b.json' => 'theme-settings-sso.json',
            'group_6615bc1d65360.json' => 'theme-settings-sso-provider-github.json',
            'group_66143631ddd8b.json' => 'theme-settings-sso-provider-azure.json',
            'group_6619abc83577b.json' => 'theme-settings-sso-provider-google.json',

            'post_type_6599ab6f6b61a.json' => 'cpt-testimonial.json',
            'post_type_659c2c3567923.json' => 'cpt-reusable-content.json',
            'post_type_65f3571fbc97e.json' => 'cpt-alert.json',

            'ui_options_page_66143503e5c73.json' => 'option-page-theme-settings.json',
            'ui_options_page_661435b551158.json' => 'option-page-theme-settings-sso.json',

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
    }
);
