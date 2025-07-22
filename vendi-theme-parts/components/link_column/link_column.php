<?php

use Vendi\Theme\Component\LinkColumn;
use Vendi\Theme\ComponentUtility;

/** @var LinkColumn $component */
$component = ComponentUtility::get_new_component_instance(LinkColumn::class);

if (!$component->renderComponentWrapperStart()) {
    return;
}

vendi_load_component_v3('mantle');

?>

    <div class="content">

        <div class="copy sub-component-basic-copy">

            <?php vendi_render_heading($component); ?>

            <?php echo wp_kses_post($component->getSubField('copy')) ?>
        </div>


        <ul class="links">
            <?php foreach ($component->getLinksSimplified() as $link): ?>

                <li>
                    <?php
                    vendi_load_component_v3(
                        'acf-advanced-link',
                        [
                            'link' => $link,
                            'html_after_link' => vendi_get_svg('images/icons/arrow-right-two.svg', echo: false),
                        ],
                    );
                    ?>
                </li>

            <?php endforeach; ?>
        </ul>
    </div>
<?php


$component->renderComponentWrapperEnd();
