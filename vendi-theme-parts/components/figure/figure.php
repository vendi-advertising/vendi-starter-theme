<?php

use Vendi\Theme\Component\Figure;
use Vendi\Theme\ComponentUtility;

/** @var Figure $component */
$component = ComponentUtility::get_new_component_instance(Figure::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}

$caption      = $component->getCaption();
$photo_credit = $component->getPhotoCredit();

?>

<?php $component->maybeRenderComponentHeader(); ?>

    <figure>
        <?php if ($image = $component->getImage()): ?>
            <?php echo $component->getImageHtml($image['ID'], 'full'); ?>
            <?php if ($caption || $photo_credit) : ?>
                <figcaption>
                    <?php if ($caption): ?>
                        <p class="caption"><?php esc_html_e($caption); ?></p>
                    <?php endif; ?>
                    <?php if ($photo_credit): ?>
                        <p class="photo-credit"><strong>Photo credit:</strong> <?php esc_html_e($photo_credit); ?></p>
                    <?php endif; ?>
                </figcaption>
            <?php endif; ?>
        <?php endif; ?>
    </figure>


<?php

$component->renderComponentWrapperEnd();
