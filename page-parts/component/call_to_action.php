<?php
// NOTE: This component is intentionally a div, not a section, because its place in the document outline
// is not clear.

//heading_level
//include_in_document_outline
?>
<div
    <?php vendi_render_class_attribute('component-call-to-action', include_grid_settings: true, include_box_control_settings: true); ?>
    <?php vendi_render_css_styles_for_box_control(); ?>
    <?php vendi_render_row_id_attribute() ?>
>
    <div class="region">
        <?php vendi_render_headline('heading'); ?>

        <?php if ($copy = get_sub_field('copy')): ?>
            <?php echo wp_kses_post($copy); ?>
        <?php endif; ?>

        <?php if (have_rows('calls_to_action')): ?>
            <?php while (have_rows('calls_to_action')): the_row(); ?>
                <?php vendi_load_component_component(get_row_layout(), 'call-to-action'); ?>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>
