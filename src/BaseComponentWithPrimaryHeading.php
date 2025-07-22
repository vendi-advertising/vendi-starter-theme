<?php

namespace Vendi\Theme;

abstract class BaseComponentWithPrimaryHeading extends BaseComponent implements PrimaryHeadingInterface
{
    public function maybeRenderComponentHeading(): void
    {
        vendi_render_heading($this);
    }

    public function getAdditionalHeadingClasses(): array
    {
        $ret = [];
        if ($this->getHeadingRender() === HeadingRenderEnum::render_non_semantic) {
            $ret[] = $this->getSubField('heading_tag');
        }

        if ($this->getHeadingRender() === HeadingRenderEnum::aria_label_only) {
            $ret[] = 'visually-hidden';
        }

        return $ret;
    }

    public function getHeadingRender(): ?HeadingRenderEnum
    {
        return HeadingRenderEnum::tryFrom($this->getSubField('heading_render'));
    }

    protected function initComponent(): void
    {
        parent::initComponent();
        $this->initHeadingFontSize();
        $this->initHeadingLineHeight();
    }

    private function initHeadingFontSize(): void
    {
        if ( ! ($hr = $this->getHeadingRender()) || ($hr !== HeadingRenderEnum::render && $hr !== HeadingRenderEnum::render_non_semantic)) {
            return;
        }

        if ( ! $headingFontSize = $this->getHeadingFontSize()) {
            return;
        }

        $this->componentStyles->addCssProperty('--local-heading-font-size', $headingFontSize . 'rem');
    }

    //line height
    private function initHeadingLineHeight(): void
    {
        if ( ! ($hr = $this->getHeadingRender()) || ($hr !== HeadingRenderEnum::render && $hr !== HeadingRenderEnum::render_non_semantic)) {
            return;
        }

        if ( ! $headingLineHeight = $this->getHeadingLineHeight()) {
            return;
        }

        $this->componentStyles->addCssProperty('--local-heading-line-height', $headingLineHeight);
    }


    protected function getAdditionalRootAttributes(): array
    {
        $ret = parent::getAdditionalRootAttributes();
        if ($this->getHeadingRender() === HeadingRenderEnum::aria_label_only) {
            $ret['aria-label'] = $this->getHeadingText();
        }

        return $ret;
    }

    public function jsonSerialize(): array
    {
        $ret = parent::jsonSerialize();

        $ret['heading']                   = [];
        $ret['heading']['text']           = $this->getHeadingText();
        $ret['heading']['heading_render'] = $this->getHeadingRender()->value;
        if (($this->getHeadingRender() === HeadingRenderEnum::render) || ($this->getHeadingRender() === HeadingRenderEnum::render_non_semantic)) {
            $ret['heading']['heading_level'] = $this->getSubField('heading_level');
            if ('h2' === $this->getSubField('heading_level')) {
                $ret['heading']['heading_style'] = $this->getSubField('heading_style');
            }

            $ret['heading']['text_wrap_style'] = $this->getSubField('text_wrap_style');
        }

        return $ret;
    }

    public function shouldRenderHeadingTag(): bool
    {
        if (HeadingRenderEnum::omit === $this->getHeadingRender()) {
            return false;
        }

        if (HeadingRenderEnum::aria_label_only === $this->getHeadingRender()) {
            return false;
        }

        return true;
    }

    public function getHeadingTag(): ?string
    {
        if ($this->shouldRenderHeadingTag()) {
            if ($this->getHeadingRender() === HeadingRenderEnum::render_non_semantic) {
                return 'div';
            }

            return $this->getSubField('heading_tag');
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

    public function getHeadingFontSize(): ?float
    {
        if (($ret = $this->getSubField('heading_font_size')) && is_numeric($ret)) {
            return (float)$ret;
        }

        return null;
    }

    public function getHeadingLineHeight(): ?float
    {
        if (($ret = $this->getSubField('heading_line_height')) && is_numeric($ret)) {
            return (float)$ret;
        }

        return null;
    }
}
