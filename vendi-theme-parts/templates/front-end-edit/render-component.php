<?php

if (!$vendiPostId = get_query_var_int(VENDI_QUERY_STRING_FRONT_END_EDIT_KEYS::POST_ID->value)) {
    throw new Exception('No post ID provided');
}

if (!$vendiFieldObject = get_field_object(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, $vendiPostId, load_value: true)) {
    throw new Exception('No field object found');
}

if (null === $componentId = get_query_var_int(VENDI_QUERY_STRING_FRONT_END_EDIT_KEYS::COMPONENT_ID->value)) {
    throw new Exception('No component ID provided');
}

if (!$value = $vendiFieldObject['value'][$componentId]) {
    throw new Exception('No value found for component ID');
}

ob_start();

if (have_rows(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, $vendiPostId)) {
    while (have_rows(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, $vendiPostId)) {
        the_row();

        if (get_row_index() !== ($componentId + 1)) {
            continue;
        }

        vendi_load_component_v3(get_row_layout());
    }
}

$content = ob_get_clean();

$json = json_encode(
    [
        'content' => $content,
    ],
    JSON_THROW_ON_ERROR,
);

header('Content-Type: application/json');
header('Content-Length: '.strlen($json));
echo $json;
exit;
