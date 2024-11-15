<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;

abstract class BlockquoteBase extends BaseComponentWithPrimaryHeading
{
    public function __construct()
    {
        parent::__construct('component-blockquote');
    }

    public function getCopy(): string
    {
        return $this->getSubField('copy');
    }

    protected function abortRender(): bool
    {
        return ! $this->getCopy();
    }

    public function setComponentCssProperties(): void
    {
        $this->componentStyles->addCssProperty('--local-text-color', $this->getSubField('text_color'));
    }
}
