<?php

use Vendi\Theme\Component\SimpleLinkCard;
use Vendi\Theme\ComponentUtility;

/** @var SimpleLinkCard $component */
$component = ComponentUtility::get_new_component_instance(SimpleLinkCard::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}

vendi_load_component_v3('acf-advanced-link', ['link' => get_sub_field('link')]);

$component->renderComponentWrapperEnd();
