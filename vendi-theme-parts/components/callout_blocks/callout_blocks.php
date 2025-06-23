<?php

use Vendi\Theme\Component\CalloutBlocks;
use Vendi\Theme\ComponentUtility;

/** @var CalloutBlocks $component */
$component = ComponentUtility::get_new_component_instance(CalloutBlocks::class);

if (!$component->renderComponentWrapperStart()) {
    return;
}

$backgroundSvg = $component->getBackgroundPatternShieldWithColor(get_sub_field('background_shield_color'), true);

$props = [
    '--local-background-pattern' => $backgroundSvg,
    '--local-background-color' => get_sub_field('content_background_color'),
];

$propsEncoded = '';
foreach ($props as $prop => $value) {
    $propsEncoded .= "$prop: $value;";
}

?>

    <div class="content-and-cta-wrap shields custom-colors shield-size-small vertical-padding-alternative-target" style="<?php esc_attr_e($propsEncoded); ?>">
        <div class="content-and-cta sub-component-basic-copy">
            <div class="wrap">
                <div class="content">

                    <?php $component->maybeRenderComponentHeading(); ?>

                    <?php echo wp_kses_post($component->getSubField('copy')); ?>

                </div>

                <?php $component->maybeRenderCallsToAction(); ?>
            </div>
        </div>
    </div>

    <div class="image-wrap vertical-padding-alternative-target">

        <div class="overlay-edge left"></div>
        <div class="overlay">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1221.7 717">
                <path fill="currentColor" d="M0 717h1222V0H0Zm866-343a394 394 0 0 1-5 59 288 288 0 0 1-14 56 279 279 0 0 1-53 94 227 227 0 0 1-38 36 270 270 0 0 1-43 27 240 240 0 0 1-203 0 254 254 0 0 1-82-63 296 296 0 0 1-53-94 355 355 0 0 1-19-115V66h510Z"/>
            </svg>
        </div>
        <div class="overlay-edge right"></div>
    </div>

<?php

$component->renderComponentWrapperEnd();
