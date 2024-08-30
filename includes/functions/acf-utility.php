<?php

function vendi_get_sub_field_boolean(string $sub_field, ?bool $default = null): bool
{
    return 'true' === vendi_constrain_item_to_list(get_sub_field($sub_field), ['true', 'false'], $default);
}

function vendi_get_sub_field_range_int(string $sub_field, int $min, int $max, $default = null): null|int|string
{
    return vendi_get_sub_field_constrained_to_list($sub_field, array_map('strval', range($min, $max)), $default);
}

function vendi_get_sub_field_constrained_to_list(string $sub_field, array $options, $default = null): null|int|string
{
    return vendi_constrain_item_to_list(get_sub_field($sub_field), $options, $default);
}

function vendi_get_component_settings(string $name, mixed $default_value = null): mixed
{
    // If there is a way to do this without loop, I haven't found it yet.
    if (vendi_have_settings()) {
        while (vendi_have_settings()) {
            the_setting();
            if ($value = get_sub_field($name)) {

                // This needs more testing, but I believe this is the correct
                // way to "finish" the loop early.
                acf_remove_loop();

                return $value;
            }
        }
    }

    return $default_value;
}

function vendi_have_settings($post_id = false): bool
{
    return have_rows('layout_settings', $post_id);
}
