<?php

namespace Vendi\Theme\Component;

class BasicCopy extends BasicCopyBase
{
    public function getHeadingText(): ?string
    {
        return $this->getSubField('heading');
    }
}
