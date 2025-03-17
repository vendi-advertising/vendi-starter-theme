<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\Traits\LinkColorSettingsTrait;
use Vendi\Theme\Traits\PrimaryTextColorSettingsTrait;

class Carousel extends BaseComponentWithPrimaryHeading
{
    use PrimaryTextColorSettingsTrait;
    use LinkColorSettingsTrait;

    public function __construct()
    {
        parent::__construct('component-carousel');
    }

    public function setComponentCssProperties(): void
    {
        $this->setComponentCssPropertiesForLinkColorSettings($this->componentStyles);
    }

    protected function getAdditionalRootAttributes(): array
    {
        $ret = parent::getAdditionalRootAttributes();
        //data-role="vendi-carousel"
        $ret['data-role'] = 'vendi-carousel';

        return $ret;
    }
}
