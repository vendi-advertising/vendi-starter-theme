<?php

global $vendi_component_object_state;

$this_field = $vendi_component_object_state['field'] ?? null;
$short_path = $vendi_component_object_state['short_path'] ?? null;
$source_url = $vendi_component_object_state['source_url'] ?? null;

if (!$this_field) {
    vendi_load_component_component('error_template', 'debug');
    exit;
}
?>
<div style="border: 2px solid red; padding: 40px;">
    <h1 style="font-size: 48px;">
        This is an auto-generated file for the <em><?php esc_html_e(get_row_layout()); ?></em> template.
    </h1>
    <code style="background-color: black; color: white; padding: 10px; margin: 10px 0; display: block;"><?php esc_html_e($short_path); ?></code>
    <h2>The fields for this template include:</h2>
    <table style="min-width: 600px;">
        <thead>
        <tr>
            <td>Key</td>
            <td>Type</td>
            <td>Sample value</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this_field as $key => $value): ?>
            <tr>
                <td style="font-family: monospace;"><?php esc_html_e($key); ?></td>
                <td><?php esc_html_e(get_debug_type($value)); ?></td>
                <td>
                    <?php if (is_scalar($value)): ?>
                        <?php esc_html_e($value) ?>
                    <?php else: ?>
                        <?php var_dump($value); ?>
                    <?php endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Template call first found at <?php esc_html_e($source_url); ?> -->
</div>
