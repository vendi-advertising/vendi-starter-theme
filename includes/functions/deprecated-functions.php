<?php

function vendi_maybe_get_row_id_attribute_from_subfield(string $sub_field_key = 'component_row_id', bool $echo = true, bool $id_only_no_attribute = false): ?string
{
    return vendi_render_row_id_attribute($sub_field_key, $echo, $id_only_no_attribute);
}
