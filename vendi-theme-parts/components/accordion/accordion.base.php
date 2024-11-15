<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;

abstract class AccordionBase extends BaseComponentWithPrimaryHeading
{
    public function __construct()
    {
        parent::__construct('component-accordion');
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

    public function setComponentCssProperties(): void
    {
        $this->componentStyles->addCssProperty('--local-text-color', $this->getSubField('text_color'));
    }

    protected function abortRender(): bool
    {
        return ! $this->getAccordionItems() || ! is_array($this->getAccordionItems()) || ! count($this->getAccordionItems());
    }

    public function getAdditionalRootAttributes(): array
    {
        $ret = [
            'data-role' => 'accordion',
        ];

        if ('show' === $this->getSubField('expand_collapse_all')) {
            $ret['data-expand-collapse-available'] = null;
        }

        return $ret;
    }

    public function getAdditionalCopy(): ?string
    {
        return $this->getSubField('additional_copy');
    }

    public function jsonSerialize(): array
    {
        $ret = parent::jsonSerialize();

        $ret['intro_additional_copy'] = $this->getAdditionalCopy();
        $ret['expand_collapse_all']   = $this->getSubField('expand_collapse_all');
        $ret['accordion_items']       = $this->getAccordionItems();

        return $ret;
    }
}
