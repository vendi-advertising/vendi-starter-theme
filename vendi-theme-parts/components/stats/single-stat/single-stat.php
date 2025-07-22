<?php

use Vendi\Theme\Component\SingleStat;
use Vendi\Theme\ComponentUtility;

/** @var SingleStat $component */
$component = ComponentUtility::get_new_component_instance(SingleStat::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}

?>
    <div class="single-stat">
        <div class="stat"><?php esc_html_e($component->getSubField('stat')); ?></div>
        <div class="details"><?php esc_html_e($component->getSubField('details')); ?></div>
        <?php if ($description = $component->getSubField('description')): ?>
            <div class="description"><?php esc_html_e($component->getSubField('description')); ?></div>
        <?php endif; ?>
    </div>

<?php
$component->renderComponentWrapperEnd();
