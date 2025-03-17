<?php

namespace Vendi\Theme\Traits;

use Vendi\Theme\ComponentInterface;
use Vendi\Theme\ComponentStyles;

trait LinkColorSettingsTrait
{
    /*
     * ACF does not support conditions on cloned fields, so we have to place the link color
     * in a group that is set to render seamlessly. This allows us to have a dropdown menu
     * that conditionally shows/hides the link color choices. Ugly, but it works.
     */
    private const string ACF_FIELD_FOR_PRIMARY_LINK_COLOR_SETTINGS = 'primary_text_link_color_settings';
    private const string ACF_FIELD_FOR_GROUP_CONTAINING_LINK_COLOR = 'primary_text_link_color_group';
    private const string ACF_FIELD_FOR_LINK_COLOR                  = 'primary_text_link_color';

    public function getPrimaryTextLinkColor(?string $textColor = null, string $default = '#000000'): ?string
    {
        if ( ! $this instanceof ComponentInterface) {
            throw new \RuntimeException('This trait can only be used by classes that implement \Vendi\Theme\ComponentInterface');
        }

        return match ($this->getSubField(self::ACF_FIELD_FOR_PRIMARY_LINK_COLOR_SETTINGS)) {
            'custom' => $this->getSubField(self::ACF_FIELD_FOR_GROUP_CONTAINING_LINK_COLOR)[self::ACF_FIELD_FOR_LINK_COLOR] ?? null,
            default => $this->getLinkColorBasedOnTextColor($textColor, $default),
        };
    }

    private function getLinkColorBasedOnTextColor(?string $textColor = null, string $default = '#000000'): ?string
    {
        static $WHITE = '#ffffff';
        static $BLACK = '#000000';
        static $DARK_BLUE = '#003057';
        static $LIGHT_BLUE = '#0071ce';
        static $GOLD = '#ffb30f';
        static $GRAY_4 = '#f7f7f7';
        static $GRAY_3 = '#414042';
        static $GRAY_2 = '#707070';
        static $GRAY_1 = '#f2f3f4';

        static $textColorToLinkColorMap = [
            $WHITE      => $DARK_BLUE,
            $DARK_BLUE  => $GOLD,
            $LIGHT_BLUE => $GOLD,
            $GOLD       => $DARK_BLUE,
            $GRAY_4     => $GOLD,
            $GRAY_3     => $DARK_BLUE,
            $GRAY_2     => $DARK_BLUE,
            $GRAY_1     => $DARK_BLUE,
            $BLACK      => $LIGHT_BLUE,
            'var(--color-brand-dark-gray)' => $LIGHT_BLUE,
        ];

        if ( ! $textColor) {
            return $default;
        }

        $textColor = strtolower($textColor);

        return $textColorToLinkColorMap[$textColor] ?? $default;
    }

    public function setComponentCssPropertiesForLinkColorSettings(
        ComponentStyles $componentStyles,
        string $variableForLocalTextColor = '--local-text-color',
        string $variableForLocalLinkColor = '--local-link-color',
    ): void {
        if ($primary_text_color = $this->getPrimaryTextColor()) {
            $componentStyles->addCssProperty($variableForLocalTextColor, $primary_text_color);
        }

        if ($linkColor = $this->getPrimaryTextLinkColor()) {
            $componentStyles->addCssProperty($variableForLocalLinkColor, $linkColor);
        }
    }
}
