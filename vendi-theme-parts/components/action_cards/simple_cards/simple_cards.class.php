<?php

namespace Vendi\Theme\Component\ActionCards;

use Vendi\Theme\VendiComponent;

class SimpleCard extends VendiComponent
{
    public function __construct()
    {
        parent::__construct('component-action-cards-simple-card');
        $this->addRootClass('sub-component-basic-copy');
    }
}
