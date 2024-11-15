<?php


use Vendi\Theme\ComponentInterface;
use Vendi\Theme\PrimaryHeadingInterface;
use Vendi\Theme\Elements\HeadingElement;

function vendi_render_heading(PrimaryHeadingInterface $component, array|string $additional_css_classes = 'header'): void
{
    if ( ! $component->shouldRenderHeadingTag() || ! $component->getHeadingText()) {
        return;
    }

    $heading_tag  = $component->getHeadingTag();
    $heading_text = $component->getHeadingText();
    if (is_string($additional_css_classes)) {
        $additional_css_classes = explode(' ', $additional_css_classes);
    }

    $additional_css_classes = apply_filters('vendi/heading/classes', $additional_css_classes, $component);
    if ($component instanceof ComponentInterface) {
        $additional_css_classes = apply_filters('vendi/heading/classes/' . $component->getComponentName(), $additional_css_classes, $component);
    }

    $headingElement = new HeadingElement($heading_tag, $heading_text, $additional_css_classes);
    $headingElement->render();
}
