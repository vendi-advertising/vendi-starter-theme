<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\Traits\LinkColorSettingsTrait;
use Vendi\Theme\Traits\PrimaryTextColorSettingsTrait;

class Blockquote extends BaseComponentWithPrimaryHeading
{
    use PrimaryTextColorSettingsTrait;
    use LinkColorSettingsTrait;

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
        if ($primary_text_color = $this->getPrimaryTextColor()) {
            $this->componentStyles->addCssProperty('--local-text-color', $primary_text_color);
        }

        if ($linkColor = $this->getPrimaryTextLinkColor()) {
            $this->componentStyles->addCssProperty('--local-link-color', $linkColor);
        }
    }
}
