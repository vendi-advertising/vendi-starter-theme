<?php

use Vendi\Theme\Component\BasicCopy;
use Vendi\Theme\ComponentUtility;

$component = ComponentUtility::get_new_component_instance( BasicCopy::class );

if ( ! $component->renderComponentWrapperStart() ) {
    return;
}

vendi_render_headline( $component, with_dots: true );

echo $component->getCopy();

$component->renderComponentWrapperEnd();
