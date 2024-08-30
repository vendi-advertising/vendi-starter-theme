<?php

if ( have_rows( 'hero' ) ) {
    while ( have_rows( 'hero' ) ) {
        the_row();
        vendi_load_modern_component( [ 'hero', get_row_layout() ] );
    }
}
