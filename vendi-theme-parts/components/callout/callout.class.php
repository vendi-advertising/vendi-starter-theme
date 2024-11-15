<?php

namespace Vendi\Theme\Component;

class Callout extends CalloutBase
{
    public function getHeadingText(): ?string
    {
        return $this->getSubField('header');
    }
}
