<?php
$classes = ['component-basic-copy'];

$classes = array_merge($classes, vendi_maybe_get_grid_classes());
$classes = array_merge($classes, vendi_get_css_classes_for_box_control());

$styles = vendi_get_css_styles_for_box_control();

?>
<section
    <?php vendi_render_class_attribute($classes, include_grid_settings: true); ?>
    <?php vendi_render_css_styles($styles); ?>
    <?php vendi_render_row_id_attribute() ?>
>
    <div class="region">
        <?php echo get_sub_field('copy') ?>
    </div>
</section>
