<?php

use Vendi\Theme\Component\BasicCopy;

$component = new BasicCopy();

if (!$component->renderComponentWrapperStart()) {
    return;
}

vendi_render_headline('heading');

echo get_sub_field('copy');

$component->renderComponentWrapperEnd();
