<?php

function vendi_get_field_or_default($block, $field_name, $default_name = null)
{
    if ($value = get_field($field_name)) {
        return $value;
    }

    return $block[$default_name ?? $field_name] ?? '';
}
