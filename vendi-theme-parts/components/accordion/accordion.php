<?php

use Vendi\Theme\ComponentUtility;
use Vendi\Theme\VendiComponentLoader;

if ((!$accordion_items = get_sub_field('accordion_items')) || !is_array($accordion_items) || !count($accordion_items)) {
    return;
}

$componentName = 'component-accordion';
$componentIndex = ComponentUtility::get_instance()->get_next_id_for_component($componentName);

$rootClasses = [
    $componentName,
];

?>
<section
    <?php vendi_render_class_attribute($rootClasses); ?>
    <?php vendi_render_component_data_name_and_index_attributes($componentName, $componentIndex); ?>
    data-role="accordion"
    <?php if ('show' === get_sub_field('expand_collapse_all')): ?>
        data-expand-collapse-available
    <?php endif; ?>
    <?php vendi_render_row_id_attribute() ?>
>
    <div class="region">

        <?php
        vendi_render_headline('intro_heading');
        VendiComponentLoader::get_instance()->loadComponent('accordion/accordion-controls', object_state: ['accordion_items' => $accordion_items]);

        while (have_rows('accordion_items')) {
            the_row();
            VendiComponentLoader::get_instance()->loadComponent(['accordion', get_row_layout()]);
        }

        ?>

    </div>
</section>
