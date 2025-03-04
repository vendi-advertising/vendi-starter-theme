<?php

use Vendi\Theme\Component\Callout;
use Vendi\Theme\ComponentUtility;
use Vendi\Theme\DTO\SimpleLink;

/** @var Callout $component */
$component = ComponentUtility::get_new_component_instance(Callout::class);

$component->renderComponentWrapperStart();

?>

<?php vendi_render_heading($component); ?>
<?php echo $component->getCopy(); ?>

<?php vendi_load_component_v3(['buttons']); ?>

<?php

$component->renderComponentWrapperEnd();
