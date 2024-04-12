<section
    <?php vendi_render_class_attribute('component-basic-copy', include_grid_settings: true, include_box_control_settings: true); ?>
    <?php vendi_render_css_styles_for_box_control(); ?>
    <?php vendi_render_row_id_attribute() ?>
>
    <div class="region">
        <?php echo get_sub_field('copy') ?>
    </div>
</section>
