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
        public readonly array $additionalAttributes = [],
        public readonly string $htmlTagForLink = 'a',
    ) {}

    public static function tryCreate(mixed $field, array $additionalCssClasses = [], array $additionalAttributes = [], string $htmlTagForLink = 'a'): ?self
    {
        // Nothing we can do here
        if ( ! is_array($field)) {
            return null;
        }

        return self::createFromSimpleArray($field, $additionalCssClasses, $additionalAttributes, $htmlTagForLink);
    }

    public static function createFromSimpleArray(array $array, array $additionalCssClasses = [], array $additionalAttributes = [], string $htmlTagForLink = 'a'): self
    {
        return new self(
            url: $array['url'] ?? null,
            title: $array['title'] ?? null,
            target: $array['target'] ?? null,
            originalArray: $array, additionalCssClasses: $additionalCssClasses,
            additionalAttributes: $additionalAttributes,
            htmlTagForLink: $htmlTagForLink,
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


        $attributes = [];

        if ('input' !== $this->htmlTagForLink) {
            $url                = $this->url ?? '#';
            $attributes['href'] = esc_url($url);
            if ($this->target) {
                $attributes['target'] = $this->target;
            }
        }

        if ($cssClasses) {
            $attributes['class'] = implode(' ', $cssClasses);
        }

        foreach ($this->additionalAttributes as $key => $value) {
            $attributes[$key] = $value;
        }

        $finalAttributeString = array_reduce(
            array_keys($attributes),
            static fn($carry, $key) => $carry . ' ' . $key . '="' . esc_attr($attributes[$key]) . '"',
            '',
        );

        return sprintf(
            '<%3$s %1$s><span>%2$s</span></%3$s>',
            $finalAttributeString,
            $finalTitle,
            $this->htmlTagForLink,
        );
    }
}
