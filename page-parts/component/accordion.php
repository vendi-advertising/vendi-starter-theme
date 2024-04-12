<?php
if ((!$accordion_items = get_sub_field('accordion_items')) || !is_array($accordion_items) || !count($accordion_items)) {
    return;
}

?>
<section
    <?php vendi_render_class_attribute('component-accordion', include_grid_settings: true, include_box_control_settings: true); ?>
    <?php vendi_render_css_styles_for_box_control(); ?>
    data-role="accordion"
    <?php if ('show' === get_sub_field('expand_collapse_all')): ?>
        data-expand-collapse-available
    <?php endif; ?>
    <?php vendi_render_row_id_attribute() ?>
>
    <div class="region">

        <?php vendi_load_component_component('intro-headline', 'accordion'); ?>

        <?php vendi_load_component_component_with_state('controls', ['accordion_items' => $accordion_items], 'accordion'); ?>

        <?php vendi_load_component_component_with_state('items', ['accordion_items' => $accordion_items], 'accordion'); ?>

    </div>
</section>
