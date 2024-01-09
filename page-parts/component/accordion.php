<?php
if ((!$accordion_items = get_sub_field('accordion_items')) || !is_array($accordion_items) || !count($accordion_items)) {
    return;
}
?>
<section
    class="component-accordion"
    data-role="accordion"
    <?php if ('show' === get_sub_field('expand_collapse_all')): ?>
        data-expand-collapse-available
    <?php endif; ?>
    <?php vendi_maybe_get_row_id_attribute_from_subfield() ?>
>
    <div class="region">

        <?php vendi_load_component_component('intro-headline', 'accordion'); ?>

        <?php vendi_load_component_component_with_state('controls', ['accordion_items' => $accordion_items], 'accordion'); ?>

        <?php vendi_load_component_component_with_state('items', ['accordion_items' => $accordion_items], 'accordion'); ?>

    </div>
</section>
