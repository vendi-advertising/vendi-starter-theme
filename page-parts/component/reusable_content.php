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

    // loop through the content components rows of data
    while (have_rows(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, $shared_content_post)) {

        the_row();
        //Load component
        vendi_load_component_component_with_state(get_row_layout(), ['current_post' => $shared_content_post]);
    }
}
