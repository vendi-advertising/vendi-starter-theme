<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponent;

abstract class FormBase extends BaseComponent
{
    public function setComponentCssProperties(): void
    {
        $this->componentStyles->addStyle('--local-text-color', $this->getSubField('text_color'));
    }
}
