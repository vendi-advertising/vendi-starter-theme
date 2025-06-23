<?php

/** @var LinkRow $component */

use Vendi\Theme\Component\LinkRow;
use Vendi\Theme\ComponentUtility;

/** @var LinkRow $component */
$component = ComponentUtility::get_new_component_instance(LinkRow::class);
if ( ! $component->renderComponentWrapperStart()) {
    return;
}

?>
    <style>

    </style>
    <div class="link-row">
        <div class="image">
            <?php vendi_get_svg(__DIR__ . '/images/' . get_sub_field('theme_image'), pathIsAbsolute: true); ?>
        </div>
        <div class="text">
            <h3 class="heading"><?php esc_html_e(get_sub_field('heading')); ?></h3>
            <div class="copy"><?php echo wp_kses_post(get_sub_field('copy')); ?></div>
        </div>

        <div class="link">
            <?php
            if ($link = get_sub_field('link')) {
                vendi_load_component_v3(
                    'buttons/button',
                    [
                        'link'         => $link,
                        'button_style' => $component->getSubField('global-button-style'),
                    ],
                );
            }
            ?>

        </div>
    </div>

<?php

$component->renderComponentWrapperEnd();
