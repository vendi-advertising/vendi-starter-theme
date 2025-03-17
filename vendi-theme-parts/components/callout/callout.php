<?php

use Vendi\Theme\Component\Callout;
use Vendi\Theme\ComponentUtility;
use Vendi\Theme\DTO\SimpleLink;

/** @var Callout $component */
$component = ComponentUtility::get_new_component_instance(Callout::class);

$component->renderComponentWrapperStart();

?>

    <div class="image">
        <?php echo $component->getCalloutImageHtml() ?>
        <div class="pattern"></div>
    </div>

    <div class="content">
        <?php vendi_render_heading($component); ?>
        <?php echo $component->getCopy(); ?>

        <?php vendi_load_component_v3(['buttons']); ?>
    </div>

    <div class="pattern"></div>
<?php

$component->renderComponentWrapperEnd();
