<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponent;

class Accordion extends BaseComponent {
    public function __construct() {
        parent::__construct( 'component-accordion' );
    }

    public function getNumberOfColumns(): int {
        return $this->getSubFieldAndCache( 'number_of_columns' );
    }

    public function getAccordionItems(): array {
        return $this->getSubFieldAndCache( 'accordion_items' );
    }

    public function setComponentCssProperties(): void {
        $this->componentStyles->addCssProperty( '--local-text-color', get_sub_field( 'text_color' ) );
    }

    protected function abortRender(): bool {
        return ! $this->getAccordionItems() || ! is_array( $this->getAccordionItems() ) || ! count( $this->getAccordionItems() );
    }

    public function getAdditionalRootAttributes(): array {
        $ret = [
            'data-role' => 'accordion',
        ];

        if ( 'show' === $this->getSubFieldAndCache( 'expand_collapse_all' ) ) {
            $ret['data-expand-collapse-available'] = null;
        }

        return $ret;
    }
}
