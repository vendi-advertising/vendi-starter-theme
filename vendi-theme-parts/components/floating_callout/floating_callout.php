<?php

use Vendi\Theme\Component\FloatingCallout;
use Vendi\Theme\ComponentUtility;

/** @var FloatingCallout $component */
$component = ComponentUtility::get_new_component_instance(FloatingCallout::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}


?>

<?php if ('background-shield' === $component->getSubField('image_settings')): ?>
    <?php if ($image_link = $component->getSubField('image_link')): ?>
        <?php vendi_load_component_v3('acf-advanced-link', ['link' => $image_link, 'do_not_render_tag_contents' => true, 'do_not_render_closing_tag' => true]); ?>
        <div class="image"></div>
        <?php vendi_load_component_v3('acf-advanced-link', ['link' => $image_link, 'do_not_render_tag_contents' => true, 'do_not_render_opening_tag' => true]); ?>
    <?php else: ?>
        <div class="image"></div>
    <?php endif; ?>
<?php else: ?>
    <?php if ($image_link = $component->getSubField('image_link')): ?>
        <div class="image">
            <?php vendi_load_component_v3('acf-advanced-link', ['link' => $image_link, 'do_not_render_tag_contents' => true, 'do_not_render_closing_tag' => true]); ?>
            <?php if ($image = $component->getSubField('image')): ?>
                <?php echo bis_get_attachment_image($image['ID'], 'full') ?>
            <?php endif; ?>
            <?php vendi_load_component_v3('acf-advanced-link', ['link' => $image_link, 'do_not_render_tag_contents' => true, 'do_not_render_opening_tag' => true]); ?>
        </div>
    <?php else: ?>
        <div class="image">
            <?php if ($image = $component->getSubField('image')): ?>
                <?php echo bis_get_attachment_image($image['ID'], 'full') ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>


    <div class="content-and-cta-wrap">
        <div class="content">

            <?php $component->maybeRenderComponentHeader(); ?>

            <?php echo wp_kses_post($component->getSubField('copy')); ?>

        </div>

        <?php $component->maybeRenderCallsToAction(); ?>
    </div>

<?php

$component->renderComponentWrapperEnd();
