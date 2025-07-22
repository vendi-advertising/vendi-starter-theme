<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;

class Accordion extends BaseComponentWithPrimaryHeading implements ColorSchemeAwareInterface
{
    use ColorSchemeTrait;

    public function __construct()
    {
        parent::__construct('component-accordion');
    }

    protected function initComponent(): void
    {
        parent::initComponent();
//        $this->setColorScheme();
    }

    public function getNumberOfColumns(): int
    {
        return $this->getSubField('number_of_columns');
    }

    public function getAccordionItems(): array
    {
        $ret = $this->getSubField('accordion_items');
        if ( ! is_array($ret)) {
            return [];
        }

        return $ret;
    }



    protected function abortRender(): bool
    {
        return ! $this->getAccordionItems() || ! is_array($this->getAccordionItems()) || ! count($this->getAccordionItems());
    }

    public function getAdditionalRootAttributes(): array
    {
        $ret              = parent::getAdditionalRootAttributes();
        $ret['data-role'] = 'accordion';

        if ('show' === $this->getSubField('expand_collapse_all')) {
            $ret['data-expand-collapse-available'] = null;
        }

        return $ret;
    }

    public function getAdditionalCopy(): ?string
    {
        return $this->getSubField('additional_copy');
    }

}
