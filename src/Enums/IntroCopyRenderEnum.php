<?php

namespace Vendi\Theme\Enums;

enum IntroCopyRenderEnum: string
{
    case omit                  = 'omit';
    case render                = 'render';
    case aria_description_only = 'aria-description-only';
}
