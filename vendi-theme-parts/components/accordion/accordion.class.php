<?php

namespace Vendi\Theme\Component;

class Accordion extends AccordionBase
{
    public function getHeadingText(): ?string
    {
        return $this->getSubField('intro_heading');
    }
}
