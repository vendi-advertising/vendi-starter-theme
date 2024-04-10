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
            'group_6599eed2bffc0.json' => 'accordion-item-simple.json',
            'group_659c0a262f75a.json' => 'component-basic-copy.json',
            'group_659c754d6a1d2.json' => 'component-blockquote.json',
            'group_659c272fb8f1f.json' => 'component-callout.json',
            'group_659c6e7fc386c.json' => 'component-figure.json',
            'group_659c445704039.json' => 'component-reusable-content.json',
            'group_6599ae10d9002.json' => 'component-testimonial.json',

            'group_6599af50880a8.json' => 'components-shared-component-settings-tab.json',
            'group_61dc8e97982a7.json' => 'content-components.json',

            'group_6599abaa59f84.json' => 'entity-testimonial.json',
            'group_65f357e01af5b.json' => 'entity-alert.json',

            'group_61d8a393c5415.json' => 'theme-settings-fields.json',
            'group_62348a640fc68.json' => 'theme-settings-tab-global-javascript.json',

            'group_661435d8a956b.json' => 'theme-settings–sso.json',
            'group_6615bc1d65360.json' => 'theme-settings–sso–github-provider.json',
            'group_66143631ddd8b.json' => 'theme-settings–sso–azure-provider.json',

            'post_type_6599ab6f6b61a.json' => 'cpt-testimonial.json',
            'post_type_659c2c3567923.json' => 'cpt-reusable-content.json',
            'post_type_65f3571fbc97e.json' => 'cpt-alert.json',

            'ui_options_page_66143503e5c73.json' => 'option-page-theme-settings.json',
            'ui_options_page_661435b551158.json' => 'option-page-theme-settings–sso.json',
        ];

        if (array_key_exists($filename, $mapping)) {
            return $mapping[$filename];
        }

        return $filename;
    },
    accepted_args: 3,
);
