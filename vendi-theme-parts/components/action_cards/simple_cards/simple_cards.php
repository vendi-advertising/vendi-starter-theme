<?php

use Vendi\Theme\Component\ActionCards\SimpleCard;
use Vendi\Theme\ComponentUtility;

/** @var SimpleCard $component */
$component = ComponentUtility::get_new_component_instance(SimpleCard::class);
if (!$component->renderComponentWrapperStart()) {
    return;
}

static $arrowRight = vendi_get_svg('images/icons/arrow-right', echo: false);
?>


<?php if ($heading = $component->getSubField('heading')): ?>
    <h3 class="heading"><?php echo esc_html($heading); ?></h3>
<?php endif; ?>

<?php if ($copy = $component->getSubField('copy')): ?>
    <p class="copy"><?php echo esc_html($copy); ?></p>
<?php endif; ?>

<?php if ($link = $component->getSubField('link')): ?>
    <?php $link['aria-label'] = $link['title'] ?>
    <?php $link['title'] = $arrowRight; ?>
    <?php $link['title_escaped'] = true; ?>
    <?php vendi_load_component_v3('acf-advanced-link', ['link' => $link]); ?>
<?php endif; ?>

<?php $component->renderComponentWrapperEnd();
