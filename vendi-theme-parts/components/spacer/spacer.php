<?php

use Vendi\Theme\Component\Spacer;
use Vendi\Theme\ComponentUtility;

/** @var Spacer $component */
$component = ComponentUtility::get_new_component_instance(Spacer::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}

$component->renderComponentWrapperEnd();
