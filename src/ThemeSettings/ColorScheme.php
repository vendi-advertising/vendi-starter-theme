<?php

namespace Vendi\Theme\ThemeSettings;

/**
 * This could possibly be extended to include a preview such as demoed here:
 * https://codepen.io/Chris-Haas-the-builder/pen/LEERjoe?editors=1100
 */
readonly class ColorScheme extends AbstractNamedThemeSetting
{
    public const string DEFAULT_COLOR_SCHEME_KEY = '__default__';

    public function __construct(
        string $name,
        string $slug,
        public string $h1,
        public string $h2,
        public string $h3,
        public string $h4,
        public string $h5,
        public string $h6,
        public string $p,
        public string $a,
        public string $aHover,
        public string $aVisited,
        public string $aActive,
        public bool $isDefault,
    ) {
        parent::__construct(
            name: $name,
            slug: $slug,
        );
    }

    public static function fromArray(array $colorScheme): self
    {
        return new self(
            name: $colorScheme['color_scheme_name'],
            slug: $colorScheme['color_scheme_slug'],
            h1: $colorScheme['heading_tags']['h1'],
            h2: $colorScheme['heading_tags']['h2'],
            h3: $colorScheme['heading_tags']['h3'],
            h4: $colorScheme['heading_tags']['h4'],
            h5: $colorScheme['heading_tags']['h5'],
            h6: $colorScheme['heading_tags']['h6'],
            p: $colorScheme['body_copy_tags']['body_copy'],
            a: $colorScheme['link_tags']['link'],
            aHover: $colorScheme['link_tags']['link_hover'],
            aVisited: $colorScheme['link_tags']['link_visited'],
            aActive: $colorScheme['link_tags']['link_active'],
            isDefault: $colorScheme['is_default_color_scheme'] === 'yes',
        );
    }

    public function jsonSerialize(): array
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'h1'        => $this->h1,
                'h2'        => $this->h2,
                'h3'        => $this->h3,
                'h4'        => $this->h4,
                'h5'        => $this->h5,
                'h6'        => $this->h6,
                'p'         => $this->p,
                'a'         => $this->a,
                'aHover'    => $this->aHover,
                'aVisited'  => $this->aVisited,
                'aActive'   => $this->aActive,
                'isDefault' => $this->isDefault,
            ],
        );
    }

    public static function getHtmlAttributeKey(): string
    {
        return 'vendi-color-scheme';
    }

    protected function getSelectors(): array
    {
        $ret = parent::getSelectors();
        if ($this->isDefault) {
            $ret[] = self::DEFAULT_COLOR_SCHEME_KEY;
        }

        return $ret;
    }

    /** @noinspection CssInvalidHtmlTagReference */
    protected function getCssProperties(): string
    {
        return <<<CSS
            --color-scheme-color-h1: $this->h1;
            --color-scheme-color-h2: $this->h2;
            --color-scheme-color-h3: $this->h3;
            --color-scheme-color-h4: $this->h4;
            --color-scheme-color-h5: $this->h5;
            --color-scheme-color-h6: $this->h6;
            --color-scheme-color-p: $this->p;
            --color-scheme-color-a: $this->a;
            --color-scheme-color-a-hover: $this->aHover;
            --color-scheme-color-a-visited: $this->aVisited;
            --color-scheme-color-a-active: $this->aActive;
            CSS;
    }
}
