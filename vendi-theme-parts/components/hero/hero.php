<?php

if (have_rows('hero')) {
    while (have_rows('hero')) {
        the_row();

        $layout = get_row_layout();

        // Legacy
        if ($layout === 'home') {
            $layout = 'image';
        }

        vendi_load_component_v3(['hero', $layout]);
    }
} else {
    vendi_load_component_v3(['hero', 'simple']);
}
