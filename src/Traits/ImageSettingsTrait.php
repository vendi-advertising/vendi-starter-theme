<?php

namespace Vendi\Theme\Traits;

use Vendi\Theme\ComponentStyles;
use Vendi\Theme\Enums\FigureImageConstraintEnum;
use Vendi\Theme\VendiComponent;

trait ImageSettingsTrait
{
    public function getImageLoading(): string
    {
        if ( ! $this instanceof VendiComponent) {
            throw new \RuntimeException('The trait ImageSettingsTrait can only be used by classes that extend VendiComponent');
        }

        return $this->getSubField('image_loading') ?: 'lazy';
    }

    public function getImageFetchPriority(): string
    {
        if ( ! $this instanceof VendiComponent) {
            throw new \RuntimeException('The trait ImageSettingsTrait can only be used by classes that extend VendiComponent');
        }

        return $this->getSubField('fetch_priority') ?: 'auto';
    }

    public function getImageConstraintMode(): ?string
    {
        if ( ! $this instanceof VendiComponent) {
            throw new \RuntimeException('The trait ImageSettingsTrait can only be used by classes that extend VendiComponent');
        }

        return vendi_constrain_item_to_enum_cases($this->getSubField('constrain_image'), FigureImageConstraintEnum::cases());
    }

    public function setComponentCssPropertiesForImageSettings(
        ComponentStyles $componentStyles,
        string $variableForLocalMaxWidth = '--local-max-width',
        string $variableForLocalMaxHeight = '--local-max-height',
        string $variableForLocalObjectFit = '--local-object-fit',
    ): void {
        if ($image_constraint = $this->getImageConstraintMode()) {
            if ($image_constraint === FigureImageConstraintEnum::maxWidth->value || $image_constraint === FigureImageConstraintEnum::both->value) {
                $componentStyles->addCssProperty($variableForLocalMaxWidth, $this->getSubField('max_width') . 'rem');
            }

            if ($image_constraint === FigureImageConstraintEnum::maxHeight->value || $image_constraint === FigureImageConstraintEnum::both->value) {
                $componentStyles->addCssProperty($variableForLocalMaxHeight, $this->getSubField('max_height') . 'rem');
            }
        }

        $componentStyles->addCssProperty($variableForLocalObjectFit, $this->getSubField('object_fit'));
    }
}
