<?php
$classes = ['component-basic-copy'];

$classes = array_merge($classes, vendi_maybe_get_grid_classes());
?>
<section
    <?php vendi_render_class_attribute($classes, include_grid_settings: true); ?>
    <?php vendi_maybe_get_row_id_attribute_from_subfield(); ?>
>
    <div class="region">
        <?php echo get_sub_field('copy') ?>
    </div>
</section>
