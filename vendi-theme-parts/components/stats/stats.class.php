<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\ComponentInterfaces\CallsToActionAwareInterface;
use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;
use Vendi\Theme\ComponentInterfaces\IntroCopyInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;
use Vendi\Theme\Traits\CommonCallsToActionTrait;
use Vendi\Theme\Traits\IntroCopyTrait;

class Stats extends BaseComponentWithPrimaryHeading implements IntroCopyInterface, CallsToActionAwareInterface, ColorSchemeAwareInterface
{
    use IntroCopyTrait;
    use CommonCallsToActionTrait;
    use ColorSchemeTrait;

//    public function setComponentCssProperties(): void
//    {
//        $this->componentStyles->addStyle('--local-text-color', $this->getSubField('text_color'));
//        $this->componentStyles->addStyle('--local-line-color', $this->getSubField('line_color'));
//    }

    public function getStats()
    {
        return $this->getSubField('stats');
    }

    protected function abortRender(): bool
    {
        return ! is_iterable($this->getStats()) || ! count($this->getStats());
    }

    public function getRootClasses(): array
    {
        $ret = parent::getRootClasses();

        $ret[] = 'stat-count-' . count($this->getStats());

        return $ret;
    }
}
