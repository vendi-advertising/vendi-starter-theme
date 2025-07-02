<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;

class Blockquote extends BaseComponentWithPrimaryHeading implements ColorSchemeAwareInterface
{
    use ColorSchemeTrait;

    public function __construct()
    {
        parent::__construct('component-blockquote');
    }

    protected function initComponent(): void
    {
        parent::initComponent();
        $this->setColorScheme();
    }

    public function getCopy(): string
    {
        return $this->getSubField('copy');
    }

    protected function abortRender(): bool
    {
        return ! $this->getCopy();
    }

}
