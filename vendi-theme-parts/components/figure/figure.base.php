<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponent;
use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\Component\Figure\FigureImageConstraintEnum;

abstract class FigureBase extends BaseComponentWithPrimaryHeading
{
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

    public function getImageConstraintMode(): ?string
    {
        return vendi_constrain_item_to_enum_cases($this->getSubField('constrain_image'), FigureImageConstraintEnum::cases());
    }

    public function setComponentCssProperties(): void
    {
        if ($image_constraint = $this->getImageConstraintMode()) {
            if ($image_constraint === FigureImageConstraintEnum::maxWidth->value || $image_constraint === FigureImageConstraintEnum::both->value) {
                $this->componentStyles->addCssProperty('--local-max-width', $this->getSubField('max_width') . 'rem');
            }

            if ($image_constraint === FigureImageConstraintEnum::maxHeight->value || $image_constraint === FigureImageConstraintEnum::both->value) {
                $this->componentStyles->addCssProperty('--local-max-height', $this->getSubField('max_height') . 'rem');
            }
        }

        $this->componentStyles->addCssProperty('--local-object-fit', $this->getSubField('object_fit'));
        $this->componentStyles->addCssProperty('--local-text-color', $this->getSubField('text_color'));
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
