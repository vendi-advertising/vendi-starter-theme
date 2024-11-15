<?php

namespace Vendi\Theme\Component;

class ImageGallery extends ImageGalleryBase
{
    public function getHeadingText(): ?string
    {
        return $this->getSubField('header');
    }
}
