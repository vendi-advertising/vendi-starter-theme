<?php

namespace Vendi\Theme\DTO;

class SimpleLink implements LinkInterface
{
    private function __construct(
        public readonly ?string $url,
        public readonly ?string $title,
        public readonly ?string $target,
        public readonly array $originalArray,
        public readonly array $additionalCssClasses = [],
    ) {}

    public static function tryCreate(mixed $field, array $additionalCssClasses = []): ?self
    {
        // Nothing we can do here
        if ( ! is_array($field)) {
            return null;
        }

        return self::createFromSimpleArray($field, $additionalCssClasses);
    }

    public static function createFromSimpleArray(array $array, array $additionalCssClasses = []): self
    {
        return new self(
            url: $array['url'] ?? null,
            title: $array['title'] ?? null,
            target: $array['target'] ?? null,
            originalArray: $array, additionalCssClasses: $additionalCssClasses,
        );
    }

    public function toHtml(
        ?string $htmlEncodedLinkContentsInsteadOfTitle = null,
        string|array $cssClasses = [],
        ?string $htmlEncodedTextBeforeTitle = null,
        ?string $htmlEncodedTextAfterTitle = null,
    ): string {
        if (is_string($cssClasses)) {
            $cssClasses = explode(' ', $cssClasses);
        }

        $cssClasses = array_merge($this->additionalCssClasses, $cssClasses);

        $title      = esc_html($this->title ?? '');
        $finalTitle = '' . $htmlEncodedTextBeforeTitle . ($htmlEncodedLinkContentsInsteadOfTitle ?? $title) . $htmlEncodedTextAfterTitle;

        $url = $this->url ?? '#';

        return sprintf(
            '<a href="%1$s"%2$s%3$s><span>%4$s</span></a>',
            esc_url($url),
            $this->target ? ' target="' . esc_attr($this->target) . '"' : '',
            $cssClasses ? ' class="' . esc_attr(implode(' ', $cssClasses)) . '"' : '',
            $finalTitle,
        );
    }
}
