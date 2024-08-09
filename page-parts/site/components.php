<?php

// begin content components flexible content
// check if the flexible content field has rows of data
use Vendi\Theme\VendiComponentLoader;

if (have_rows(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, get_queried_object())) {

    ?>
    <section class="content-components">
        <?php

        // loop through the content components rows of data
        while (have_rows(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, get_queried_object())) {
            the_row();

            VendiComponentLoader::get_instance()->loadComponent(get_row_layout());
        }

        ?>
    </section>
    <?php
}
