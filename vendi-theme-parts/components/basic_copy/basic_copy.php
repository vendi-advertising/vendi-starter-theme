<?php

use Vendi\Theme\Component\BasicCopy;
use Vendi\Theme\ComponentUtility;

/** @var BasicCopy $component */
$component = ComponentUtility::get_new_component_instance(BasicCopy::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}

vendi_render_heading($component);

echo $component->getCopy();

$component->renderComponentWrapperEnd();
