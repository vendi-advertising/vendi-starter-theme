<?php

namespace Vendi\Theme;

interface PrimaryHeadingInterface
{
    public function getHeadingText(): ?string;

    public function getHeadingRender(): ?HeadingRenderEnum;

    public function getHeadingTag(): ?string;

    public function getHeadingStyle(): ?string;

    public function getHeadingTextWrapStyle(): ?string;

    public function getHeadingFontSize(): ?float;

    public function getHeadingLineHeight(): ?float;
}
