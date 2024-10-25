<?php

namespace Vendi\Theme\DTO;

interface LinkInterface
{
    public function toHtml(?string $htmlEncodedLinkContentsInsteadOfTitle = null, string|array $cssClasses = []): string;
}
