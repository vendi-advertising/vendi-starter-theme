<?php

use Vendi\Theme\Component\Group;
use Vendi\Theme\ComponentUtility;

/** @var Group $component */
$component = ComponentUtility::get_new_component_instance(Group::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}

$component->maybeRenderComponentHeading();

if (have_rows('group_components_content_components')) {
    // loop through the content components rows of data
    while (have_rows('group_components_content_components')) {
        the_row();

        vendi_load_component_v3(get_row_layout());
    }
}

$component->renderComponentWrapperEnd();
