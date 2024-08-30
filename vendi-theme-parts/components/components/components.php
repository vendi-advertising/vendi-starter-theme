<?php

// begin content components flexible content
// check if the flexible content field has rows of data
if ( have_rows( VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, get_queried_object() ) ) {

    ?>
    <section class="content-components">
        <?php

        // loop through the content components rows of data
        while ( have_rows( VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, get_queried_object() ) ) {
            the_row();

            vendi_load_modern_component( get_row_layout() );
        }

        ?>
    </section>
    <?php
}