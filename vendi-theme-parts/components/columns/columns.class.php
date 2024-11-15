<?php

namespace Vendi\ThemeParts\Component;

use Vendi\Theme\BaseComponent;

class Columns extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('component-columns');
    }

    public function setComponentCssProperties(): void
    {
        $this->componentStyles->addStyle('--local-justify-content', $this->getJustifyContent());
        $this->componentStyles->addStyle('--local-justify-items', $this->getJustifyItems());
        $this->componentStyles->addStyle('--local-align-items', $this->getAlignItems());
        $this->componentStyles->addStyle('--local-align-content', $this->getAlignContent());
        $this->componentStyles->addStyle('--local-column-gap', $this->getColumnGap());

        $number_of_columns = $this->getNumberOfColumns();

        $sizeRatios = [];
        $sizes      = explode('-', $this->getSubField(sprintf('column_size_%1$d_columns', $number_of_columns)));
        foreach ($sizes as $size) {
            $sizeRatios[] = $size . 'fr';
        }

        $this->componentStyles->addStyle('--local-column-size', implode(' ', $sizeRatios));
    }

    private function getJustifyContent(): string
    {
        return $this->getSubField('justify_content');
    }

    private function getJustifyItems(): string
    {
        return $this->getSubField('justify_items');
    }

    private function getAlignItems(): string
    {
        return $this->getSubField('align_items');
    }

    private function getAlignContent(): string
    {
        return $this->getSubField('align_content');
    }

    private function getColumnGap(): string
    {
        return $this->getSubField('column_gap') . 'rem';
    }

    public function getNumberOfColumns(): int
    {
        return $this->getSubFieldRangeInt('number_of_columns', 1, 4, 1);
    }

    protected function initComponent(): void
    {
        $this->addRootClass('columns-count-' . $this->getNumberOfColumns());
    }
}
