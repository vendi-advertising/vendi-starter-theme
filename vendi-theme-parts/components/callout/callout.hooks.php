<?php

// Currently <component>.hooks.php is not automatically called in the admin. If you copy-and-paste this code,
// make sure to update hooks-acf-default-values.php to include the new file.
add_filter(
    'acfe/load_field/key=field_67813dcad74a1',
    static function ($field) {
        if (!is_array($field) || !array_key_exists('sub_fields', $field) || !is_array($field['sub_fields'])) {
            return $field;
        }

        foreach ($field['sub_fields'] as &$sub_field) {
            if ('constrain_image' !== ($sub_field['name'] ?? null)) {
                continue;
            }

            // This is a fake condition that should never be truthful, we're
            // using it allow for a shared/cloned field, but we don't want it to
            // ever show for this specific component.
            $sub_field['conditional_logic'] = [
                [
                    [
                        'field' => 'field_677f02bdf12b7',
                        'operator' => '==',
                        'value' => 'cheese',
                    ],
                ],
            ];
        }

        return $field;
    },
);
