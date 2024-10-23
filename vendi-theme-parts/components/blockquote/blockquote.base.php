<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponent;

abstract class BlockquoteBase extends BaseComponent {
    public function __construct() {
        parent::__construct( 'component-blockquote' );
    }

    public function getCopy(): string {
        return $this->getSubField( 'copy' );
    }

    protected function abortRender(): bool {
        return ! $this->getCopy();
    }
}
