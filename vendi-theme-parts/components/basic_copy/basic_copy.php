<?php

use Vendi\Theme\Component\BasicCopy;

$component = new BasicCopy();

if ( ! $component->renderComponentWrapperStart() ) {
    return;
}

vendi_render_headline( 'heading', with_dots: true );

echo get_sub_field( 'copy' );

$component->renderComponentWrapperEnd();
