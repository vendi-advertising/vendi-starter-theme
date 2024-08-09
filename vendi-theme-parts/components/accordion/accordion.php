<?php

use Vendi\Theme\VendiComponentLoader;

if ((!$accordion_items = get_sub_field('accordion_items')) || !is_array($accordion_items) || !count($accordion_items)) {
    return;
}

?>
<section
    <?php vendi_render_class_attribute('component-accordion'); ?>
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

            if ('accordion_item' !== get_row_layout()) {
                $errorText = 'The accordion component no longer supports '.esc_html(get_row_layout());
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    echo sprintf('<h1>%s</h1>', esc_html($errorText));
                } else {
                    echo sprintf('<!-- %s -->', esc_html($errorText));
                }
            } else {
                VendiComponentLoader::get_instance()->loadComponent(['accordion', get_row_layout()]);
            }
        }

        ?>

    </div>
</section>
