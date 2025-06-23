<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\ComponentInterfaces\CallsToActionAwareInterface;
use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;
use Vendi\Theme\ComponentInterfaces\IntroCopyInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;
use Vendi\Theme\Traits\CommonCallsToActionTrait;
use Vendi\Theme\Traits\IntroCopyTrait;

class Cards extends BaseComponentWithPrimaryHeading implements ColorSchemeAwareInterface, IntroCopyInterface, CallsToActionAwareInterface
{
    use ColorSchemeTrait;
    use IntroCopyTrait;
    use CommonCallsToActionTrait;

    public function __construct()
    {
        parent::__construct('component-cards');
    }
}
