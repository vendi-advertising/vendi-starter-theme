<?php

namespace Vendi\Theme\Elements;

abstract class AbstractElement implements ElementInterface
{
    public function __construct(
        public array $classes = [],
    ) {}
}
