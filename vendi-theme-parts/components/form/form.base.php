<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;

abstract class FormBase extends BaseComponentWithPrimaryHeading
{
    public function __construct()
    {
        parent::__construct('component-form');
    }

    public function setComponentCssProperties(): void
    {
        $this->componentStyles->addStyle('--local-text-color', $this->getSubField('text_color'));
    }
}
