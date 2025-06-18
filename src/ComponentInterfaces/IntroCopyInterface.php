<?php

namespace Vendi\Theme\ComponentInterfaces;

use Vendi\Theme\Enums\IntroCopyRenderEnum;

interface IntroCopyInterface
{
    public function getIntroCopyText(): ?string;

    public function getIntroCopyTextRender(): ?IntroCopyRenderEnum;

    public function getIntroCopyTextTextWrapStyle(): ?string;

    public function maybeSetAriaDescription(): void;

    public function getIntroCopyAriaId(): string;
}
