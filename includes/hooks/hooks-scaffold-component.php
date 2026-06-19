<?php

/**
 * When a new layout is added to the content_components flexible content field group
 * and the field group is saved, automatically scaffold the component directory
 * and stub files for any layout that doesn't yet have a component directory.
 */
add_action(
    'acf/update_field_group',
    static function (array $field_group): void {

        // This should only happen on development environments
        if (!WP_DEBUG) {
            return;
        }

        if (!is_user_logged_in()) {
            return;
        }

        // Only act on the Content Components field group
        if ('group_61dc8e97982a7' !== ($field_group['key'] ?? '')) {
            return;
        }

        $fields = acf_get_fields($field_group['key']);
        if (empty($fields)) {
            return;
        }

        // Find the content_components flexible content field
        $flex_field = null;
        foreach ($fields as $field) {
            if (VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME === ($field['name'] ?? '')) {
                $flex_field = $field;
                break;
            }
        }

        if (null === $flex_field || empty($flex_field['layouts'])) {
            return;
        }

        $components_path = VENDI_CUSTOM_THEME_PATH . VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME;

        foreach ($flex_field['layouts'] as $layout) {
            $layout_name = $layout['name'] ?? '';
            if (empty($layout_name)) {
                continue;
            }

            $component_dir = $components_path . '/' . $layout_name;
            if (is_dir($component_dir)) {
                continue;
            }

            vendi_scaffold_component($component_dir, $layout_name);
        }
    }
);

/**
 * Scaffold a new v3 component directory with stub files.
 *
 * Creates:
 *   - {name}.php          Main template (stub)
 *   - {name}.class.php    Component class extending BaseComponent
 *   - {name}.css          Empty stylesheet
 *   - readme.md           Documentation stub with required front matter
 *
 * @param string $component_dir Absolute path to the component directory to create.
 * @param string $layout_name   Snake_case layout name (e.g. "my_component").
 */
function vendi_scaffold_component(string $component_dir, string $layout_name): void
{
    if (!wp_mkdir_p($component_dir)) {
        return;
    }

    $class_name     = str_replace('_', '', ucwords($layout_name, '_'));
    $css_class      = 'component-' . str_replace('_', '-', $layout_name);
    $human_name     = ucwords(str_replace('_', ' ', $layout_name));
    $example_prefix = str_replace('_', '-', $layout_name);

    // {name}.php
    file_put_contents(
        $component_dir . '/' . $layout_name . '.php',
        '<?php' . "\n\n" .
        'use Vendi\Theme\Component\\' . $class_name . ';' . "\n" .
        'use Vendi\Theme\ComponentUtility;' . "\n\n" .
        '/** @var ' . $class_name . ' $component */' . "\n" .
        '$component = ComponentUtility::get_new_component_instance(' . $class_name . '::class);' . "\n\n" .
        'if (!$component->renderComponentWrapperStart()) {' . "\n" .
        '    return;' . "\n" .
        '}' . "\n\n" .
        '?>' . "\n\n" .
        '<?php' . "\n\n" .
        '$component->renderComponentWrapperEnd();' . "\n"
    );

    // {name}.class.php
    file_put_contents(
        $component_dir . '/' . $layout_name . '.class.php',
        '<?php' . "\n\n" .
        'namespace Vendi\Theme\Component;' . "\n\n" .
        'use Vendi\Theme\BaseComponent;' . "\n\n" .
        'class ' . $class_name . ' extends BaseComponent' . "\n" .
        '{' . "\n" .
        '    public function __construct()' . "\n" .
        '    {' . "\n" .
        "        parent::__construct('" . $css_class . "');\n" .
        '    }' . "\n" .
        '}' . "\n"
    );

    // {name}.css
    file_put_contents(
        $component_dir . '/' . $layout_name . '.css',
        '.' . $css_class . " {\n}\n"
    );

    // readme.md
    file_put_contents(
        $component_dir . '/readme.md',
        "---\n" .
        "role: component\n" .
        "example-prefix: {$example_prefix}\n" .
        "parent-field: content_components\n" .
        "---\n\n" .
        "## {$human_name}\n\n" .
        "### Overview\n\n" .
        "TODO: Describe the {$human_name} component.\n"
    );
}
