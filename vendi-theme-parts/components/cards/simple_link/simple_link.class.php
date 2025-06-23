<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponent;
use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;
use Vendi\Theme\Traits\IntroCopyTrait;

class SimpleLinkCard extends BaseComponent implements ColorSchemeAwareInterface
{
    use ColorSchemeTrait;
    use IntroCopyTrait;

    public function __construct()
    {
        parent::__construct('component-cards-simple-card');
        $this->addRootClass('always-full-width');
    }
}
