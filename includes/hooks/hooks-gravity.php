<?php

// add placeholder visibility to Gravity Forms
add_filter('gform_enable_field_label_visibility_settings', '__return_true');

// add Gravity form confirmation anchor
add_filter('gform_confirmation_anchor', '__return_true');

add_filter(
    'gform_field_content',
    static function ($field_content, $field) {
        if ($field->type === 'hidden') {
            $label = sprintf(' data-field-label="%1$s" ', esc_attr($field->label));

            return str_replace('type=', $label.'type=', $field_content);
        }

        return $field_content;
    },
    10,
    2
);

add_filter(
    'gform_ip_address',
    static function ($provided_ip) {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe

                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return $provided_ip;
    }
);
