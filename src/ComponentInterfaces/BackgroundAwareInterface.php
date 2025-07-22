<?php

namespace Vendi\Theme\ComponentInterfaces;

use Vendi\Theme\ComponentStyles;

interface BackgroundAwareInterface
{
    public function supportsBackgroundVideo(): bool;

    public function supportsPatternBackground(): bool;

    public function getBackgroundSettings(ComponentStyles $previousStyles, $key = 'backgrounds', $postId = false): void;
}
