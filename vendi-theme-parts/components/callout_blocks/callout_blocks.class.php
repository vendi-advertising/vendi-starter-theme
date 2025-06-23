<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\ComponentInterfaces\CallsToActionAwareInterface;
use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;
use Vendi\Theme\Traits\CommonCallsToActionTrait;
use Vendi\Theme\Traits\ImageSettingsTrait;

class CalloutBlocks extends BaseComponentWithPrimaryHeading implements ColorSchemeAwareInterface, CallsToActionAwareInterface
{
    use ImageSettingsTrait;
    use ColorSchemeTrait;
    use CommonCallsToActionTrait;

    public function __construct()
    {
        parent::__construct('component-callout-blocks');
    }

    public function setComponentCssProperties(): void
    {
        parent::setComponentCssProperties();

        $component_layout = $this->getSubField('component_layout');

        $this->addRootClass('component-layout-' . $component_layout);
        $this->addRootClass('always-full-width');

        if ($image_overlay_color = $this->getSubField('image_overlay_color')) {
            $this->componentStyles->addCssProperty('image-overlay-color', $image_overlay_color);
        }

        if ($image_settings = $this->getSubField('image_settings')) {
            $this->addRootClass('image-settings-' . $image_settings);
        }

        if (($image = $this->getSubField('image')) && $src = (bis_get_attachment_image_src($image['ID'], 'full')['src'] ?? null)) {
            $this->addRootClass('has-image');
            $this->componentStyles->addCssPropertyUrl('callout-image-background-src', $src);

            $custom_background_position_x = filter_var($this->getSubField('custom_background_position_x'), FILTER_VALIDATE_INT);
            if (is_int($custom_background_position_x)) {
                $backgroundPositionX = $custom_background_position_x;
                $backgroundPositionY = 0;

                if ('image-copy' === $component_layout) {
                    $this->componentStyles->addCssProperty('callout-image-background-position', "right {$backgroundPositionX}px top {$backgroundPositionY}px");
                } else {
                    $this->componentStyles->addCssProperty('callout-image-background-position', "left {$backgroundPositionX}px top {$backgroundPositionY}px");
                }
            } else {
                [$backgroundPositionX, $backgroundPositionY] = sanitize_focal_point(get_post_meta($image['ID'], 'focal_point', true));
                $backgroundPositionX *= 100;
                $backgroundPositionY *= 100;
                $this->componentStyles->addCssProperty('callout-image-background-position', "{$backgroundPositionX}% {$backgroundPositionY}%");
            }


            // TODO
//            $this->componentStyles->addCssProperty('callout-image-background-size', 'cover');
        }

        if ($column_widths = $this->getSubField('column_widths')) {
            $column_widths = implode(' ', explode('-', $column_widths));
            $this->componentStyles->addCssProperty('callout-column-width', $column_widths);
        }

//        if($image = get_sub_field('image')){
//            $this->componentStyles->addCssPropertyUrl('callout-image-background-src', bis_get_attachment_image_src($image['ID'], 'full')['src']);
//        }
    }
}
