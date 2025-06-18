<?php

namespace Vendi\Theme;

enum HeadingRenderEnum: string
{
    case omit                = 'omit';
    case render              = 'render';
    case aria_label_only     = 'aria-label-only';
    case render_non_semantic = 'non-semantic';
}
