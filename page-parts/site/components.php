<?php

// PhpStorm
assert(function_exists('have_rows'));
assert(function_exists('the_row'));
assert(function_exists('get_row_layout'));

// begin content components flexible content
// check if the flexible content field has rows of data
if (have_rows('content_components', get_queried_object())) {

    ?>
    <section class="content-components">
        <?php

        // loop through the content components rows of data
        while (have_rows('content_components', get_queried_object())) {
            the_row();

            vendi_load_component_component(get_row_layout());
        }

        ?>
    </section>
    <?php
}
