<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\Enums\FigureImageConstraintEnum;
use Vendi\Theme\Traits\ImageSettingsTrait;
use Vendi\Theme\Traits\LinkColorSettingsTrait;
use Vendi\Theme\Traits\PrimaryTextColorSettingsTrait;

class Figure extends BaseComponentWithPrimaryHeading
{
    use PrimaryTextColorSettingsTrait;
    use LinkColorSettingsTrait;
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

    public function getImageHtml(int $imageId, array|string $size, $crop = null, $attr = []): string
    {
        $attr['loading']       = $this->getImageLoading();
        $attr['fetchpriority'] = $this->getImageFetchPriority();

        return parent::getImageHtml($imageId, $size, $crop, $attr);
    }

    public function setComponentCssProperties(): void
    {
        $this->setComponentCssPropertiesForImageSettings($this->componentStyles);

        if ($primary_text_color = $this->getPrimaryTextColor()) {
            $this->componentStyles->addCssProperty('--local-text-color', $primary_text_color);
        }

        if ($linkColor = $this->getPrimaryTextLinkColor()) {
            $this->componentStyles->addCssProperty('--local-link-color', $linkColor);
        }
    }

    protected function initComponent(): void
    {
        if ($image_constraint = $this->getImageConstraintMode()) {
            $this->addRootClass('constrain-image-' . $image_constraint);
        }
    }

    public function jsonSerialize(): array
    {
        $ret = parent::jsonSerialize();

        $ret['image']        = $this->getImage();
        $ret['caption']      = $this->getCaption();
        $ret['photo_credit'] = $this->getPhotoCredit();

        return $ret;
    }
}
