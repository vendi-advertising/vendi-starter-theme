<?php

namespace Vendi\ThemeParts\Component;

use Vendi\Theme\BaseComponent;

class Columns extends BaseComponent {
    public function __construct() {
        parent::__construct( 'component-columns' );
    }

    public function setComponentCssProperties(): void {
        $this->componentStyles->addStyle( '--local-justify-content', $this->getJustifyContent() );
        $this->componentStyles->addStyle( '--local-justify-items', $this->getJustifyItems() );
        $this->componentStyles->addStyle( '--local-align-items', $this->getAlignItems() );
        $this->componentStyles->addStyle( '--local-align-content', $this->getAlignContent() );
        $this->componentStyles->addStyle( '--local-column-gap', $this->getColumnGap() );

        $number_of_columns = $this->getNumberOfColumns();

        $sizeRatios = [];
        $sizes      = explode( '-', get_sub_field( sprintf( 'column_size_%1$d_columns', $number_of_columns ) ) );
        foreach ( $sizes as $size ) {
            $sizeRatios[] = $size . 'fr';
        }

        $this->componentStyles->addStyle( '--local-column-size', implode( ' ', $sizeRatios ) );
    }

    private function getJustifyContent(): string {
        return $this->getSubFieldAndCache( 'justify_content' );
    }

    private function getJustifyItems(): string {
        return $this->getSubFieldAndCache( 'justify_items' );
    }

    private function getAlignItems(): string {
        return $this->getSubFieldAndCache( 'align_items' );
    }

    private function getAlignContent(): string {
        return $this->getSubFieldAndCache( 'align_content' );
    }

    private function getColumnGap(): string {
        return $this->getSubFieldAndCache( 'column_gap' ) . 'rem';
    }

    public function getNumberOfColumns(): int {
        return vendi_constrain_item_to_list( (int) get_sub_field( 'number_of_columns' ), [ 1, 2, 3, 4 ], 1 );
    }

    public function getAdditionalRootClasses(): array {

        return [
            'columns-count-' . $this->getNumberOfColumns(),
        ];
    }
}
