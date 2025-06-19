<?php

namespace Vendi\Theme\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class VendiComponent
{
    public function __construct(
        public readonly string $slug,
    ) {}
}
