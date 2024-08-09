<?php

/**
 * Carousel Block Template.
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @var array $context The context provided to the block by the post or it's parent block.
 *
 * @package wpdev
 */

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
    <?php
    vendi_render_class_attribute(['component-accordion', vendi_get_css_class_for_preview_mode($is_preview)], include_grid_settings: false, include_box_control_settings: false); ?>
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
