<?php $display_mode = get_field('display_mode') ?>
<details
    class="single-accordion-item"
    <?php echo in_array($display_mode, ['always-open', 'open'], true) ? 'open' : ''; ?>
    <?php echo $display_mode === 'always-open' ? 'data-always-open' : ''; ?>
    <?php vendi_maybe_get_row_id_attribute(get_field('component_row_id')); ?>
>

    <summary>
        <span class="expand-collapse-single-item"><?php vendi_get_svg('images/starter-content/plus-minus.svg'); ?></span>

        <div>
            <?php esc_html_e(get_field('title')); ?>
        </div>
    </summary>
    <div class="copy">
        <?php
        while (have_rows('content')) {
            the_row();
            vendi_load_component_component(get_row_layout());
        }
        ?>
    </div>

</details>
