<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\ComponentInterfaces\CallsToActionAwareInterface;
use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;
use Vendi\Theme\Traits\CommonCallsToActionTrait;

class CenteredCallout extends BaseComponentWithPrimaryHeading implements ColorSchemeAwareInterface, CallsToActionAwareInterface
{
    use ColorSchemeTrait;
    use CommonCallsToActionTrait;

    public function __construct()
    {
        parent::__construct('component-centered-callout');
    }
}
