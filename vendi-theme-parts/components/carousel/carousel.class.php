<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;


class Carousel extends BaseComponentWithPrimaryHeading implements ColorSchemeAwareInterface
{

    use ColorSchemeTrait;

    public function __construct()
    {
        parent::__construct('component-carousel');
        $this->setColorScheme();
    }

    protected function initComponent(): void
    {
        parent::initComponent();
        $this->setColorScheme();
    }

    protected function getAdditionalRootAttributes(): array
    {
        $ret = parent::getAdditionalRootAttributes();
        //data-role="vendi-carousel"
        $ret['data-role'] = 'vendi-carousel';

        return $ret;
    }
}
