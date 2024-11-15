<main>

    <?php

    vendi_load_component_v3('skip-link');

    if (post_password_required()) {
        echo get_the_password_form();
    } else {
        vendi_load_component_v3('hero');

        if ( ! is_404() && ! is_front_page()) {
            vendi_load_component_v3('breadcrumbs');
        }

        if (is_404()) {
            vendi_load_component_v3('main/404');
        } elseif (is_search()) {
            vendi_load_component_v3('main/search');
        } elseif (is_singular('_background')) {
            vendi_load_component_v3('main/preview-only/background');
        }

        if ( ! is_search() && ! is_404()) {
            vendi_load_component_v3('components');
        }
    }
    ?>

</main>
