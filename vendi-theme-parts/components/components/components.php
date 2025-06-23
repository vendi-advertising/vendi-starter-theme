<?php

global $vendi_component_object_state;
$objectIdForComponents = $vendi_component_object_state['object-id-for-components'] ?? get_queried_object_id();
?>


<section class="content-components" id="main-content">
    <?php

    // We always render the section, even if there are no components
    if (have_rows(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, $objectIdForComponents) ) {
        // loop through the content components rows of data
        while (have_rows(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, $objectIdForComponents)) {
            the_row();

            vendi_load_component_v3(get_row_layout());
        }
    }
    ?>
</section>
