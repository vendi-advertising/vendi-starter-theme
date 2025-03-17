<?php

/**
 * This logic needs some extensive testing. There are known possible edge cases
 * that might happen related to get_field and get_sub_field, which is why
 * `current_post` is passed in as a state variable to be used in components.
 * However, this is a little bit of a hack, and it would be better to solve this
 * by properly setting of "the post".
 *
 * The problem might only be related to relavanssi and/or CLI-related tasks, however.
 */

$shared_content_post = get_sub_field('reusable_content')[0] ?? null;

if (!$shared_content_post instanceof WP_Post) {
    return;
}
// begin content components flexible content
// check if the flexible content field has rows of data


if (have_rows(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, $shared_content_post)) {

    // This wrapper is a maybe, depending on if we use grid for the outer container.
    // Right now we have a `display: contents` on it to offset it, just in case.
    ?>
    <div class="component-reusable-content">
        <?php

        // loop through the content components rows of data
        while (have_rows(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, $shared_content_post)) {

            the_row();
            vendi_load_component_v3(get_row_layout());

        }

        ?>
    </div>
    <?php
    
}
