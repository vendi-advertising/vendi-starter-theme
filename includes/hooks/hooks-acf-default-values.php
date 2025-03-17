<?php

// Currently <component>.hooks.php is not automatically called in the admin, however we want to keep
// hooks as near to the component as possible. Eventually we might scan the file system for this,
// but until then, we'll just require it here.
require VENDI_CUSTOM_THEME_PATH . VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME . '/callout/callout.hooks.php';
require VENDI_CUSTOM_THEME_PATH . VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME . '/spacer/spacer.hooks.php';

/*
 * Component: Callout
 * Field: Line Color
 */
add_filter(
    'acf/load_field/key=field_66a9423dbe65a',
    static function ($field) {
        if (isset($field['sub_fields'][0]['default_value'])) {
            $field['sub_fields'][0]['default_value'] = '#c3c3c3';
        }

        return $field;
    },
);

/*
 * Component: Callout
 * Field: Text Color
 */
add_filter(
    'acf/load_field/key=field_66a941c034b91',
    static function ($field) {
        if (isset($field['sub_fields'][0]['default_value'])) {
            $field['sub_fields'][0]['default_value'] = '#013183';
        }

        return $field;
    },
);

/*
 * Component: Showcase
 */
add_filter(
    'acf/load_field/key=field_66ba7e5ac16a9',
    static function ($field) {
        $contentAreaSettings = $field['sub_fields'][0]['sub_fields'] ?? null;
        if ( ! is_array($contentAreaSettings)) {
            return $field;
        }

        foreach ($contentAreaSettings as $idx => $contentAreaSetting) {
            if (in_array($contentAreaSetting['name'], ['content_max_width', 'content_placement', 'content_horizontal_padding'])) {
                $field['sub_fields'][0]['sub_fields'][$idx]['disabled'] = true;
            }
        }

        return $field;
    },
);


/*
 * Component: Columns
 */
add_filter(
    'acf/load_field/key=field_66bb996160b63',
    static function ($field) {
        $contentAreaSettings = $field['sub_fields'][0]['sub_fields'] ?? null;
        if ( ! is_array($contentAreaSettings)) {
            return $field;
        }

        foreach ($contentAreaSettings as $idx => $contentAreaSetting) {
            if ($contentAreaSetting['name'] === 'content_horizontal_padding') {
                $field['sub_fields'][0]['sub_fields'][$idx]['default_value'] = 'none';
                $field['sub_fields'][0]['sub_fields'][$idx]['allow_null']    = true;
                $field['sub_fields'][0]['sub_fields'][$idx]['disabled']      = true;
                $field['sub_fields'][0]['sub_fields'][$idx]['readonly']      = true;
            }
        }

        return $field;
    },
    priority: 999999999,
);
