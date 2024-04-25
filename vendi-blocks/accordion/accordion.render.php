<?php

$accordion_items = get_field('accordion_items');

$p = new WP_HTML_Tag_Processor($content);
$allAccordionIds = [];
while ($p->next_tag('details')) {
    if ($id = $p->get_attribute('id')) {
        $allAccordionIds[] = $id;
    }
}

?>
<section
    <?php vendi_render_class_attribute('component-accordion', include_grid_settings: false, include_box_control_settings: false); ?>
    <?php //vendi_render_css_styles_for_box_control(); ?>
    data-role="accordion"
    <?php if ('show' === get_field('expand_collapse_all')): ?>
        data-expand-collapse-available
    <?php endif; ?>
    <?php vendi_render_row_id_attribute() ?>
>
    <div class="region">

        <?php
        vendi_render_headline('intro_heading');
        ?>
        <ul class="accordion-controls list-as-nav" aria-label="section controls" data-role="accordion-controls">
            <li>
                <button
                    aria-controls="<?php esc_attr_e(implode(' ', $allAccordionIds)); ?>"
                    class="expand-all"
                    data-action="expand-all"
                >
                    <span>Expand all</span>
                </button>
            </li>
            <li>
                <button
                    aria-controls="<?php esc_attr_e(implode(' ', $allAccordionIds)); ?>"
                    class="collapse-all"
                    data-action="collapse-all"
                >
                    <span>Collapse all</span>
                </button>
            </li>
        </ul>
        <?php

        $allowed_blocks = array(
            'vendi/accordion-item',
        );

        $inner_blocks_template = [
            [
                'vendi/accordion-item',
                [
                    'title' => 'Accordion Item #1',
                ],
                [],
            ],
            [
                'vendi/accordion-item',
                [
                    'title' => 'Accordion Item #2',
                ],
                [],
            ],
        ];
        ?>

        <InnerBlocks
            allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"
            class="swiper-wrapper wp-block-wpe-slider__innerblocks"
            orientation="horizontal"
            template="<?php echo esc_attr(wp_json_encode($inner_blocks_template)); ?>"
        />
        <?php
        //
        //        while (have_rows('accordion_items')) {
        //            the_row();
        //
        //            if ('accordion_item' !== get_row_layout()) {
        //                $errorText = 'The accordion component no longer supports '.esc_html(get_row_layout());
        //                if (defined('WP_DEBUG') && WP_DEBUG) {
        //                    echo sprintf('<h1>%s</h1>', esc_html($errorText));
        //                } else {
        //                    echo sprintf('<!-- %s -->', esc_html($errorText));
        //                }
        //            } else {
        //                vendi_load_component_component(get_row_layout(), 'accordion');
        //            }
        //        }

        ?>

    </div>
</section>
