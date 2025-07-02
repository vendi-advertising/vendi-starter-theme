<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;


class Form extends BaseComponentWithPrimaryHeading implements ColorSchemeAwareInterface
{
    use ColorSchemeTrait;


    public function __construct()
    {
        parent::__construct('component-form');
    }

    protected function initComponent(): void
    {
        parent::initComponent();
        $this->setColorScheme();
    }

}
