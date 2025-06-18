<?php

namespace Vendi\Theme\ComponentInterfaces;

use Vendi\Theme\ComponentStyles;

interface ImageSettingsAwareInterface
{
    public function getImageLoading(): string;

    public function getImageFetchPriority(): string;

    public function getImageConstraintMode(): ?string;

    public function setComponentCssPropertiesForImageSettings(
        ComponentStyles $componentStyles,
        string $variableForLocalMaxWidth = '--local-max-width',
        string $variableForLocalMaxHeight = '--local-max-height',
        string $variableForLocalObjectFit = '--local-object-fit',
    ): void;

    public function getImageHtml(int $imageId, array|string $size, $crop = null, $attr = []): string;
}
