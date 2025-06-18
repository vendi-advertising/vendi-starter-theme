<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\VendiComponent;

class AccordionItemBasicCopy extends VendiComponent
{
    public function __construct()
    {
        parent::__construct('accordion-item-basic-copy');
    }

    public function getCopy(): ?string
    {
        return $this->getSubField('copy');
    }
}
