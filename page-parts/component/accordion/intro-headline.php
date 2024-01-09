<?php

if (!$intro_headline = get_sub_field('intro_heading')) {
    return;
}

$intro_headline_level = vendi_constrain_h1_through_h6(get_sub_field('heading_level'));
$intro_headline_tag = 'no' === get_sub_field('include_in_document_outline') ? 'div' : $intro_headline_level;

echo sprintf('<%1$s class="%2$s">%3$s</%1$s>', $intro_headline_tag, $intro_headline_level, esc_html($intro_headline));
