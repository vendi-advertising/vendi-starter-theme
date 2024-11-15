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

        $ret['heading']                   = [];
        $ret['heading']['text']           = $this->getHeadingText();
        $ret['heading']['heading_render'] = $this->getHeadingRender()->value;
        if ($this->getHeadingRender() === HeadingRenderEnum::render) {
            $ret['heading']['heading_level'] = $this->getSubField('heading_level');
            if ('h2' === $this->getSubField('heading_level')) {
                $ret['heading']['heading_style'] = $this->getSubField('heading_style');
            }

            $ret['heading']['text_wrap_style'] = $this->getSubField('text_wrap_style');
        }

        return $ret;
    }

    final public function shouldRenderHeadingTag(): bool
    {
        return $this->getHeadingRender() === HeadingRenderEnum::render;
    }

    public function getHeadingTag(): ?string
    {
        if ($this->shouldRenderHeadingTag()) {
            return $this->getSubField('heading_level');
        }

        return null;
    }

    public function getHeadingStyle(): ?string
    {
        if ($this->shouldRenderHeadingTag() && 'h2' === $this->getSubField('heading_level')) {
            return $this->getSubField('heading_style');
        }

        return null;
    }

    public function getHeadingTextWrapStyle(): ?string
    {
        if ($this->shouldRenderHeadingTag()) {
            return $this->getSubField('text_wrap_style');
        }

        return null;
    }

    public function getHeadingText(): ?string
    {
        return $this->getSubField('heading');
    }
}
