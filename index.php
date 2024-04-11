<?php
get_header();
?>

    <main>
        <a id="main-content" tabindex="-1"></a>

        <?php
        if (post_password_required() ) {
            echo get_the_password_form();

        } else {
            vendi_load_site_component('hero');
            vendi_load_site_component('breadcrumbs');

            if (is_404()) {
                vendi_load_page_component('404');
            } elseif (is_search()) {
                vendi_load_page_component('search');
            }

            if (!is_search() && !is_404()) {
                vendi_load_site_component('components');
            }
        }
        
        ?>

    </main>
<?php
get_footer();
