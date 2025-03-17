<?php

use Vendi\Theme\Component\Button;
use Vendi\Theme\ComponentUtility;

/** @var Button $component */
$component = ComponentUtility::get_new_component_instance(Button::class);

if ($link = $component->tryCreateSimpleLink()) {
    echo $link->toHtml(cssClasses: $component->getButtonClasses());
}
