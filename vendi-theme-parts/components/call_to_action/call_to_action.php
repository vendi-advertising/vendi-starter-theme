<?php

use Vendi\Theme\VendiComponentLoader;

// NOTE: This component is intentionally a div, not a section, because its place in the document outline
// is not clear.

//heading_level
//include_in_document_outline
?>
<div
    <?php

    vendi_render_class_attribute('component-call-to-action'); ?>
    <?php vendi_render_row_id_attribute() ?>
>
    <div class="region">
        <?php vendi_render_headline('heading'); ?>

        <?php if ($copy = get_sub_field('copy')): ?>
            <?php echo wp_kses_post($copy); ?>
        <?php endif; ?>

        <?php if (have_rows('calls_to_action')): ?>
            <?php while (have_rows('calls_to_action')): the_row(); ?>
                <?php VendiComponentLoader::get_instance()->loadComponent(['call_to_action', get_row_layout()]); ?>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>
