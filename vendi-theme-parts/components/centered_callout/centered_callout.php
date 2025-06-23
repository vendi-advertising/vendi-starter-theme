<?php

use Vendi\Theme\Component\CenteredCallout;
use Vendi\Theme\ComponentUtility;

/** @var CenteredCallout $component */
$component = ComponentUtility::get_new_component_instance(CenteredCallout::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}


?>

    <div class="content">
        <?php
        $component->maybeRenderComponentHeading();
        echo wp_kses_post($component->getSubField('copy'));
        ?>
    </div>

<?php

$component->maybeRenderCallsToAction();

$component->renderComponentWrapperEnd();
