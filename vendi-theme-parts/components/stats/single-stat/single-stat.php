<?php

use Vendi\Theme\Component\SingleStat;
use Vendi\Theme\ComponentUtility;

/** @var SingleStat $component */
$component = ComponentUtility::get_new_component_instance(SingleStat::class);

if (!$component->renderComponentWrapperStart()) {
    return;
}

?>
    <div class="single-stat">
        <div class="stat"><?php echo $component->getSubField('stat'); ?></div>
        <div class="details"><?php echo $component->getSubField('details'); ?></div>
    </div>

<?php
$component->renderComponentWrapperEnd();
