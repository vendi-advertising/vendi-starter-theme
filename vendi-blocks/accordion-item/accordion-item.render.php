<?php $display_mode = get_field('display_mode') ?>
<details
    class="single-accordion-item"
    <?php echo in_array($display_mode, ['always-open', 'open'], true) ? 'open' : ''; ?>
    <?php echo $display_mode === 'always-open' ? 'data-always-open' : ''; ?>
    <?php vendi_maybe_get_row_id_attribute(get_field('component_row_id')); ?>
>

    <summary>

        <span class="expand-collapse-single-item"><?php vendi_get_svg('images/starter-content/plus-minus.svg'); ?></span>

        <div>
            <?php esc_html_e(vendi_get_field_or_default($block, 'title')); ?>
        </div>
    </summary>
    <div class="copy">
        <?php

        $allowed_blocks = array(
            'core/paragraph',
            'core/heading',
            'core/list',
            'core/image',
            'core/list-item',
        );

        $inner_blocks_template = [
            [
                'core/paragraph',
                [
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
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

    </div>

</details>
