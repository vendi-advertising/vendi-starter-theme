<?php

if (have_rows('hero')) {
    while (have_rows('hero')) {
        the_row();
        vendi_load_component_v3(['hero', get_row_layout()]);
    }
}
