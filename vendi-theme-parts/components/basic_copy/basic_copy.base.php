<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;

abstract class BasicCopyBase extends BaseComponentWithPrimaryHeading
{
    public function __construct()
    {
        parent::__construct('component-basic-copy');
    }

    public function setComponentCssProperties(): void
    {
        $this->componentStyles->addCssProperty('--local-text-color', $this->getSubField('text_color'));
        if ('custom' === $this->getSubField('link_color_settings')) {
            if ($link_color = $this->getSubField('link_color')['link_color'] ?? null) {
                $this->componentStyles->addCssProperty('--local-link-color', $link_color);
            }
        }
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
