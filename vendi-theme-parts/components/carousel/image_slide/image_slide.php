<?php

use Vendi\Theme\Component\ActionCards\CarouselImageSlide;
use Vendi\Theme\ComponentUtility;

/** @var CarouselImageSlide $component */
$component = ComponentUtility::get_new_component_instance(CarouselImageSlide::class);
if ( ! $component->renderComponentWrapperStart()) {
    return;
}

$component->maybeRenderComponentHeader();
?>

<?php if ($image = $component->getImage()): ?>
    <div class="image">
        <?php echo $component->getImageHtml($image['ID'], 'full', true); ?>
    </div>
<?php endif; ?>

<?php if ($caption = $component->getSubField('caption')): ?>
    <div class="caption">
        <?php esc_html_e($caption); ?>
    </div>
<?php endif; ?>

<?php

$component->renderComponentWrapperEnd();
