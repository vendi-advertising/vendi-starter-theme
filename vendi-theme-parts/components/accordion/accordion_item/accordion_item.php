<?php

// If we're previewing just this subcomponent, and not the entire accrodion,
// we want to open it by default.
global $vendi_accordion_item_preview;

?>

<?php $display_mode = get_sub_field('display_mode'); ?>
<details
    class="single-accordion-item"
    <?php echo in_array($display_mode, ['always-open', 'open'], true) ? 'open' : ''; ?>
    <?php echo $display_mode === 'always-open' ? 'data-always-open' : ''; ?>
    <?php vendi_maybe_get_row_id_attribute(get_sub_field('component_row_id')); ?>
    data-transition-duration-in-ms="200"
    <?php if (true === $vendi_accordion_item_preview): ?>open<?php endif; ?>
>

    <summary>

        <h3>
            <?php esc_html_e(get_sub_field('title')); ?>
        </h3>

        <span class="expand-collapse-single-item"><?php vendi_get_svg('images/starter-content/plus-minus.svg'); ?></span>

    </summary>
    <div class="copy">
        <?php
        while (have_rows('content')) {
            the_row();
            if (get_row_layout() === 'figure') {
                vendi_load_component_v3('figure');
                continue;
            }
            vendi_load_component_v3(['accordion', 'accordion_item', get_row_layout()]);
        }
        ?>
    </div>

</details>
