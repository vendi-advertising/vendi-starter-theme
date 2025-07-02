<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;
use Vendi\Theme\ComponentInterfaces\ImageSettingsAwareInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;
use Vendi\Theme\Traits\ImageSettingsTrait;

class Figure extends BaseComponentWithPrimaryHeading implements ColorSchemeAwareInterface, ImageSettingsAwareInterface
{
    use ColorSchemeTrait;
    use ImageSettingsTrait;

    public function __construct()
    {
        parent::__construct('component-figure');
    }

    public function getImage()
    {
        return $this->getSubField('image');
    }

    public function getCaption()
    {
        return $this->getSubField('caption');
    }

    public function getPhotoCredit()
    {
        return $this->getSubField('photo_credit');
    }

    protected function abortRender(): bool
    {
        $image = $this->getImage();

        return ! $image || ! is_array($image);
    }

}
