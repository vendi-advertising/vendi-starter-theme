<section class="content-components">
    <?php
    // We always render the section, even if there are no components
    if (have_rows(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, get_queried_object())) {
        // loop through the content components rows of data
        while (have_rows(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, get_queried_object())) {
            the_row();

            vendi_load_component_v3(get_row_layout());
        }
    }

    ?>
</section>
