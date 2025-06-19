<?php

namespace Vendi\Theme\ThemeSettings;

use JsonSerializable;
use Stringable;

abstract readonly class AbstractNamedThemeSetting implements JsonSerializable, Stringable
{
    public function __construct(
        public string $name,
        public string $slug,
    ) {}

    abstract public static function getHtmlAttributeKey(): string;

    abstract protected function getCssProperties(): string;

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }

    protected function getSelectors(): array
    {
        return [$this->slug];
    }

    public function __toString(): string
    {
        $key                = static::getHtmlAttributeKey();
        $attributeSelectors = array_map(static fn($attr) => sprintf('[%1$s="%2$s"]', esc_attr($key), esc_attr($attr)), $this->getSelectors());
        $attributeSelector  = implode(', ', $attributeSelectors);
        $properties         = $this->getCssProperties();

        return <<<CSS
            :where($attributeSelector) {
                $properties
            }
            CSS;
    }

    public static function getThemeSettings(string $wordPressOptionName, string $className, bool $echo = false): ?string
    {
        /** @var AbstractNamedThemeSetting[] $vendiThemeSettings */
        $vendiThemeSettings = get_option($wordPressOptionName);
        if ( ! $vendiThemeSettings || ! is_array($vendiThemeSettings) || ! count($vendiThemeSettings)) {
            return null;
        }

        $css = '';
        foreach ($vendiThemeSettings as $vendiThemeSetting) {
            if ( ! $vendiThemeSetting instanceof $className) {
                continue;
            }
            $css .= $vendiThemeSetting->__toString();
        }

        $css = <<<CSS
            <style>
                $css
            </style>
            CSS;

        if ($echo) {
            echo $css;
        }

        return $css;
    }
}
