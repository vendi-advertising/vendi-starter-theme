<?php

namespace Vendi\Theme\Enums;

enum FigureImageConstraintEnum: string
{
    case none      = 'none';
    case maxWidth  = 'max-width';
    case maxHeight = 'max-height';
    case both      = 'both';
}
