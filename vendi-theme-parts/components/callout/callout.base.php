<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponent;

abstract class CalloutBase extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('component-callout');
    }

    public function setComponentCssProperties(): void
    {
        $this->componentStyles->addCssProperty('--local-text-color', $this->getSubField('text_color'));
    }

    public function getCopy(): ?string
    {
        return $this->getSubField('copy');
    }

    public function jsonSerialize(): array
    {
        $ret = parent::jsonSerialize();

        $ret['copy'] = $this->getCopy();
        if (have_rows('buttons')) {
            $ret['buttons'] = [];
            while (have_rows('buttons')) {
                the_row();
                $ret['buttons'][] = [
                    'call_to_action'              => $this->getSubField('call_to_action'),
                    'icon'                        => $this->getSubField('icon'),
                    'call_to_action_display_mode' => $this->getSubField('call_to_action_display_mode'),
                ];
            }
        }

        return $ret;
    }

}
