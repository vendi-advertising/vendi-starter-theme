<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\Traits\LinkColorSettingsTrait;
use Vendi\Theme\Traits\PrimaryTextColorSettingsTrait;

class BasicCopy extends BaseComponentWithPrimaryHeading
{
    use PrimaryTextColorSettingsTrait;
    use LinkColorSettingsTrait;

    public function __construct()
    {
        parent::__construct('component-basic-copy');
    }

    public function setComponentCssProperties(): void
    {
        if ($primary_text_color = $this->getPrimaryTextColor()) {
            $this->componentStyles->addCssProperty('--local-text-color', $primary_text_color);
        }

        if ($linkColor = $this->getPrimaryTextLinkColor($this->getPrimaryTextColor())) {
            $this->componentStyles->addCssProperty('--local-link-color', $linkColor);
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
