<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponent;

class Callout extends BaseComponent {
    public function __construct() {
        parent::__construct( 'component-callout' );
    }

    public function setComponentCssProperties(): void {
        $this->componentStyles->addCssProperty( '--local-text-color', get_sub_field( 'text_color' ) );
    }
}
