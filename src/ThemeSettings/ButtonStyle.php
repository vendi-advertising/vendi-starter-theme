<?php

namespace Vendi\Theme\ThemeSettings;

readonly class ButtonStyle extends AbstractNamedThemeSetting
{
    public function __construct(
        string $name,
        string $slug,
        public string $textColor,
        public string $backgroundColor,
        public string $borderColor,
        public string $hoverTextColor,
        public string $hoverBackgroundColor,
        public string $hoverBorderColor,
    ) {
        parent::__construct(
            name: $name,
            slug: $slug,
        );
    }

    public static function fromArray(array $buttonStyle): self
    {
        return new self(
            name: $buttonStyle['button_style_name'],
            slug: $buttonStyle['button_style_slug'],
            textColor: $buttonStyle['default_state']['text_color'],
            backgroundColor: $buttonStyle['default_state']['background_color'],
            borderColor: $buttonStyle['default_state']['border_color'],
            hoverTextColor: $buttonStyle['hover_state']['text_color'],
            hoverBackgroundColor: $buttonStyle['hover_state']['background_color'],
            hoverBorderColor: $buttonStyle['hover_state']['border_color'],
        );
    }

    public function jsonSerialize(): array
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'textColor'            => $this->textColor,
                'backgroundColor'      => $this->backgroundColor,
                'borderColor'          => $this->borderColor,
                'hoverTextColor'       => $this->hoverTextColor,
                'hoverBackgroundColor' => $this->hoverBackgroundColor,
                'hoverBorderColor'     => $this->hoverBorderColor,
            ],
        );
    }

    public static function getHtmlAttributeKey(): string
    {
        return 'vendi-button-style';
    }

    protected function getCssProperties(): string
    {
        /** @noinspection CssInvalidHtmlTagReference */
        return <<<CSS
            --button-style-color: $this->textColor;
            --button-style-background-color: $this->backgroundColor;
            --button-style-border-color: $this->borderColor;
            --button-style-hover-color: $this->hoverTextColor;
            --button-style-hover-background-color: $this->hoverBackgroundColor;
            --button-style-hover-border-color: $this->hoverBorderColor;
            CSS;
    }
}
