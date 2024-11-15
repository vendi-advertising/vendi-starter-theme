<?php

namespace Vendi\Theme;

abstract class BaseComponentWithPrimaryHeading extends BaseComponent implements PrimaryHeadingInterface
{
    public function getHeadingRender(): ?HeadingRenderEnum
    {
        return HeadingRenderEnum::tryFrom($this->getSubField('heading_render'));
    }

    protected function getAdditionalRootAttributes(): array
    {
        $ret = parent::getAdditionalRootAttributes();
        if ($this->getHeadingRender() === HeadingRenderEnum::aria_label_only) {
            $ret['aria-label-only'] = $this->getHeadingText();
        }

        return $ret;
    }

    public function jsonSerialize(): array
    {
        $ret = parent::jsonSerialize();

        $ret['header']                   = [];
        $ret['header']['text']           = $this->getHeadingText();
        $ret['header']['heading_render'] = $this->getHeadingRender()->value;
        if ($this->getHeadingRender() === HeadingRenderEnum::render) {
            $ret['header']['heading_level'] = $this->getSubField('heading_level');
            if ('h2' === $this->getSubField('heading_level')) {
                $ret['header']['heading_style'] = $this->getSubField('heading_style');
            }

            $ret['header']['text_wrap_style'] = $this->getSubField('text_wrap_style');
        }

        return $ret;
    }

    final public function shouldRenderHeaderTag(): bool
    {
        return $this->getHeadingRender() === HeadingRenderEnum::render;
    }

    public function getHeadingTag(): ?string
    {
        if ($this->shouldRenderHeaderTag()) {
            return $this->getSubField('heading_level');
        }

        return null;
    }

    public function getHeadingStyle(): ?string
    {
        if ($this->shouldRenderHeaderTag() && 'h2' === $this->getSubField('heading_level')) {
            return $this->getSubField('heading_style');
        }

        return null;
    }

    public function getHeadingTextWrapStyle(): ?string
    {
        if ($this->shouldRenderHeaderTag()) {
            return $this->getSubField('text_wrap_style');
        }

        return null;
    }
}
