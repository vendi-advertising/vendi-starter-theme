<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponent;

abstract class BasicCopyBase extends BaseComponent
{

    public function __construct()
    {
        parent::__construct('component-basic-copy');
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
