<?php

global $vendi_component_object_state;
$short_path = $vendi_component_object_state['short_path'] ?? null;
$source_url = $vendi_component_object_state['source_url'] ?? null;
?>
<div style="border: 2px solid red; padding: 40px;">
    <h1 style="font-size: 48px;">
        This is an auto-generated file for the template.
    </h1>
    <code style="background-color: black; color: white; padding: 10px; margin: 10px 0; display: block;"><?php esc_html_e($short_path); ?></code>
    <!-- Template call first found at <?php esc_html_e($source_url); ?> -->
</div>
