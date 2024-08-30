<?php
get_header();
?>

    <main>
        <a id="main-content" tabindex="-1"></a>

        <?php
        if ( post_password_required() ) {
            echo get_the_password_form();

        } else {
            vendi_load_modern_component( 'hero' );

            if ( ! is_404() && ! is_front_page() ) {
                vendi_load_modern_component( 'breadcrumbs' );
            }

            if ( is_404() ) {
                vendi_load_modern_template( '404' );
            } elseif ( is_search() ) {
                vendi_load_modern_template( 'search' );
            } elseif ( is_singular( '_background' ) ) {
                vendi_load_modern_template( [ 'preview-only', 'background' ] );
            }

            if ( ! is_search() && ! is_404() ) {
                vendi_load_modern_component( 'components' );
            }
        }
        ?>

    </main>
<?php
get_footer();
