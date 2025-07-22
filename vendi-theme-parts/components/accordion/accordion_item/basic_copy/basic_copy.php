<?php

use Vendi\Theme\Component\AccordionItemBasicCopy;
use Vendi\Theme\ComponentUtility;

/** @var AccordionItemBasicCopy $component */
$component = ComponentUtility::get_new_component_instance(AccordionItemBasicCopy::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}

echo $component->getCopy();

$component->renderComponentWrapperEnd();
