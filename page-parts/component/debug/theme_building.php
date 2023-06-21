<?php

global $vendi_component_object_state;
$short_path = $vendi_component_object_state['short_path'] ?? null;
$source_url = $vendi_component_object_state['source_url'] ?? null;

if (!$short_path || !$source_url) {
    vendi_load_component_component('error_template', 'debug');
    exit;
}


$all_fields = get_field(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME);
$this_field = null;
if (is_array($all_fields)) {
    foreach ($all_fields as $field) {
        if ($field['acf_fc_layout'] === get_row_layout()) {
            $this_field = $field;
            break;
        }
    }
}

if (!$this_field) {
    vendi_load_component_component_with_state('unknown_template', ['short_path' => $short_path, 'source_url' => $source_url], 'debug');
    exit;
}

unset($this_field['acf_fc_layout']);
vendi_load_component_component_with_state('known_template', ['short_path' => $short_path, 'source_url' => $source_url, 'field' => $this_field], 'debug');
