<?php

add_filter(
    'acfe/load_field/key=field_67b6328b88067',
    static function ($field) {
        if ( ! is_array($field) || ! array_key_exists('sub_fields', $field) || ! is_array($field['sub_fields'])) {
            return $field;
        }

        foreach ($field['sub_fields'] as &$sub_field) {
            $toHide = ['content_width', 'content_placement'];

            if (in_array($sub_field['name'], $toHide, true)) {
                $sub_field['conditional_logic'] = [
                    [
                        [
                            'field'    => 'field_66fc4a1aa34ee',
                            'operator' => '==',
                            'value'    => 'cheese',
                        ],
                    ],
                ];
            }
        }

        return $field;
    },
);
