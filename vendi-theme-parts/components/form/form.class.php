<?php

namespace Vendi\Theme\Component;

class Form extends FormBase
{
    public function getHeadingText(): ?string
    {
        return $this->getSubField('header');
    }
}
