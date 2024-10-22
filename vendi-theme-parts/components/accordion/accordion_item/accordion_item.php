<?php $display_mode = get_sub_field( 'display_mode' ); ?>
<details
    class="single-accordion-item"
    <?php echo in_array( $display_mode, [ 'always-open', 'open' ], true ) ? 'open' : ''; ?>
    <?php echo $display_mode === 'always-open' ? 'data-always-open' : ''; ?>
    <?php vendi_maybe_get_row_id_attribute( get_sub_field( 'component_row_id' ) ); ?>
    data-transition-duration-in-ms="200"
>

    <summary class="padding-small">

        <div>
            <?php esc_html_e( get_sub_field( 'title' ) ); ?>
        </div>

        <span class="expand-collapse-single-item"><?php vendi_get_svg( 'images/starter-content/plus-minus.svg' ); ?></span>

    </summary>
    <div class="copy padding-inline-small">
        <?php
        while ( have_rows( 'content' ) ) {
            the_row();
            vendi_load_component_v3( get_row_layout() );
        }
        ?>
    </div>

</details>
