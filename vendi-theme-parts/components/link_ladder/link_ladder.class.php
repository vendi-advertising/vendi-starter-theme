<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\ComponentInterfaces\IntroCopyInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;
use Vendi\Theme\Traits\IntroCopyTrait;

class LinkLadder extends BaseComponentWithPrimaryHeading implements IntroCopyInterface
{
    use ColorSchemeTrait;
    use IntroCopyTrait;

    public function __construct()
    {
        parent::__construct('component-link-ladder');
    }

    protected function initComponent(): void
    {
        parent::initComponent();
        $this->setColorScheme();
        if ($separator_color = $this->getSubField('separator_color')) {
            $this->componentStyles->addStyle('--local-separator-color', $separator_color);
        }
    }
}
