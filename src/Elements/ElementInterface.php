<?php

namespace Vendi\Theme\Elements;

interface ElementInterface
{
    public function render(bool $echo = false): ?string;
}
