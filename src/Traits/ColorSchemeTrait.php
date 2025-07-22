<?php

namespace Vendi\Theme\Traits;

trait ColorSchemeTrait
{
    final public function setColorScheme(): void
    {
        if ($colorScheme = $this->getSubField('global-color-scheme')) {
            $this->addRootAttribute('vendi-color-scheme', $colorScheme);
        }
    }
}
