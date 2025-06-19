<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\Traits\ColorSchemeTrait;

class BasicCopy extends BaseComponentWithPrimaryHeading
{
    use ColorSchemeTrait;

    public function __construct()
    {
        parent::__construct('component-basic-copy');
    }

    protected function initComponent(): void
    {
        parent::initComponent();
        $this->setColorScheme();
    }

    public function getCopy(): ?string
    {
        return $this->getSubField('copy');
    }

    public function jsonSerialize(): array
    {
        $ret = parent::jsonSerialize();

        $ret['copy'] = $this->getCopy();

        return $ret;
    }

}
