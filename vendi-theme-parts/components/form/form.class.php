<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponent;

class Form extends BaseComponent {
    public function setComponentCssProperties(): void {
        $this->componentStyles->addStyle( '--local-text-color', get_sub_field( 'text_color' ) );
    }
}
