<?php

namespace Vendi\Theme\Traits;

use Vendi\Theme\VendiComponent;

trait ComponentGetterHelperTrait
{
    abstract public function getComponent(): VendiComponent;
}
