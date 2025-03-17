<?php

namespace Vendi\Theme\Component\ActionCards;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\Traits\ImageSettingsTrait;

class CarouselImageSlide extends BaseComponentWithPrimaryHeading
{
    use ImageSettingsTrait;

    public function __construct()
    {
        parent::__construct('component-carousel-image-slide');
    }

    protected function initComponent(): void
    {
        parent::initComponent();
        $this->addRootClass($this->getCaptionPlacement());
        $this->addRootClass($this->getHeadingPlacement());
    }

    private function getHeadingPlacement(): string
    {
        return $this->getSubFieldConstrainedToList('heading_placement', ['heading-on-left', 'heading-on-right'], 'heading-on-left');
    }

    private function getCaptionPlacement(): string
    {
        return $this->getSubFieldConstrainedToList('caption_placement', ['caption-below-image', 'caption-below-heading'], 'caption-below-heading');
    }

    protected function abortRender(): bool
    {
        if ( ! $this->getImage()) {
            return true;
        }

        return parent::abortRender();
    }

    public function setComponentCssProperties(): void
    {
        $this->setComponentCssPropertiesForImageSettings($this->componentStyles);
    }

    public function getImage(): ?array
    {
        return $this->getSubField('image');
    }

    public function getRootTag(): string
    {
        return 'div';
    }

    public function getImageHtml(int $imageId, array|string $size, $crop = null, $attr = []): string
    {
        $attr['loading']       = $this->getImageLoading();
        $attr['fetchpriority'] = $this->getImageFetchPriority();

        return parent::getImageHtml($imageId, $size, $crop, $attr);
    }
}
