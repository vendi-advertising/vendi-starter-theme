<?php

add_filter(
    'acf/load_field/key=field_6841e53be98fa',
    static function ($field) {
        if (($field['sub_fields'][0]['__name'] ?? null) === 'color') {
            $field['sub_fields'][0]['default_value'] = '#003057';
            $field['sub_fields'][0]['instructions']  = 'The selected color will always be rendered at 85% opacity.';
        }

        return $field;
    },
);
