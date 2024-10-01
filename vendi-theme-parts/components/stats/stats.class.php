<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponent;

class Stats extends BaseComponent {
    public function __construct() {
        parent::__construct( 'component-stats' );
    }

    public function setComponentCssProperties(): void {
        $this->componentStyles->addStyle( '--local-text-color', get_sub_field( 'text_color' ) );
        $this->componentStyles->addStyle( '--local-line-color', get_sub_field( 'line_color' ) );
    }

    public function getStats() {
        return $this->getSubField( 'stats' );
    }

    protected function abortRender(): bool {
        return ! is_iterable( $this->getStats() ) || ! count( $this->getStats() );
    }

    public function getRootClasses(): array {
        $ret = parent::getRootClasses();

        $ret[] = 'stat-count-' . count( $this->getStats() );

        return $ret;
    }


}
