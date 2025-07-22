<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\ComponentInterfaces\CallsToActionAwareInterface;
use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;
use Vendi\Theme\Traits\CommonCallsToActionTrait;
use Vendi\Theme\Traits\ImageSettingsTrait;
use Vendi\Theme\Traits\PrimaryTextColorSettingsTrait;

class Callout extends BaseComponentWithPrimaryHeading implements CallsToActionAwareInterface, ColorSchemeAwareInterface
{
    use PrimaryTextColorSettingsTrait;
    use ImageSettingsTrait;
    use ColorSchemeTrait;
    use CommonCallsToActionTrait;

    public function __construct()
    {
        parent::__construct('component-callout');
    }

    public function setComponentCssProperties(): void
    {
        $this->setComponentCssPropertiesForImageSettings($this->componentStyles);

        if (($image = $this->getSubField('image')) && 'image' === $this->getDisplayMode()) {
            if ($focal_point = sanitize_focal_point(get_post_meta($image['ID'], 'focal_point', true))) {
                [$x, $y] = $focal_point;
                $this->componentStyles->addCssProperty('--local-object-position', sprintf('%s%% %s%%', $x * 100, $y * 100));
            }
        }
    }

    private function getPattern(): ?string
    {
        return $this->getSubField('pattern');
    }

    protected function initDefaultRootClasses(): void
    {
        parent::initDefaultRootClasses();
        $this->addRootClass('display-mode-'.$this->getDisplayMode());
        if ($imagePlacement = $this->getImagePlacement()) {
            $this->addRootClass('image-placement-'.$imagePlacement);
        }

        if (($this->getSubField('image')) && 'pluses' === $this->getPattern()) {
            $this->addRootClass('has-pattern pattern-pluses');
        }
    }

    public function getDisplayMode(): ?string
    {
        return $this->getSubField('display_mode');
    }

    public function getCalloutImageHtml(): ?string
    {
        if ('image' !== $this->getDisplayMode()) {
            return null;
        }

        if (!$image = $this->getSubField('image')) {
            return null;
        }

        return $this->getImageHtml(
            $image['ID'],
            'full',
            attr: [
                'loading' => $this->getImageLoading(),
                'fetchpriority' => $this->getImageFetchPriority(),
            ],
        );
    }

    private function getImagePlacement(): ?string
    {
        return 'image' === $this->getDisplayMode() ? $this->getSubField('image_placement') : null;
    }

    public function getCopy(): ?string
    {
        return $this->getSubField('copy');
    }

}
