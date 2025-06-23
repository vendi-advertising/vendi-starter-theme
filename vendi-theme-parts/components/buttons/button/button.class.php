<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponent;
use Vendi\Theme\ComponentAwareOfGlobalStateInterface;
use Vendi\Theme\DTO\SimpleLink;
use Vendi\Theme\ThemeSettings\ButtonStyle;

class Button extends BaseComponent implements ComponentAwareOfGlobalStateInterface
{
    private ?array $globalState = null;

    private ?SimpleLink $link = null;

    private const string LINK_ARRAY_MODE_GLOBAL_STATE = 'global_state';
    private const string LINK_ARRAY_MODE_SUB_FIELD    = 'sub_field';

    public function __construct()
    {
        parent::__construct(
            'component-button',
            _supportsBackgroundVideo: false,
            includeRegionWrap: false,
            includeContentWrap: false,
        );
    }

    protected function getAdditionalRootAttributes(): array
    {
        if ($button_style = ($this->globalState['button_style'] ?? null)) {
            $this->addRootAttribute(ButtonStyle::getHtmlAttributeKey(), $button_style);
        }

        return parent::getAdditionalRootAttributes();
    }


    public function getLink(): ?SimpleLink
    {
        if ( ! $this->link) {
            $this->link = $this->tryCreateSimpleLink();
        }

        return $this->link;
    }

    protected function abortRender(): bool
    {
        if ( ! $this->getLink()) {
            return true;
        }

        return parent::abortRender();
    }


    public function setGlobalState(array $state): void
    {
        $this->globalState = $state;
    }

    public function getDisplayMode(): string
    {
        if ($this->getLinkArrayMode() === self::LINK_ARRAY_MODE_GLOBAL_STATE) {
            return $this->globalState['display_mode'] ?? 'button';
        }

        return $this->getSubField('display_mode_new');
    }

    public function getButtonSettingKind(?string $defaultValue = null, string $buttonSettingsSubFieldKey = 'button_settings'): ?string
    {
        return $this->getSubField($buttonSettingsSubFieldKey)['button_kind'] ?? $defaultValue;
    }

    public function getButtonSettingsStyle(?string $defaultValue = null, string $buttonSettingsSubFieldKey = 'button_settings'): ?string
    {
        return $this->getSubField($buttonSettingsSubFieldKey)['button_style'] ?? $defaultValue;
    }

    public function getButtonSettingsColor(?string $defaultValue = null, string $buttonSettingsSubFieldKey = 'button_settings'): ?string
    {
        return $this->getSubField($buttonSettingsSubFieldKey)['button_color'] ?? $defaultValue;
    }

    public function getButtonClasses(): array
    {
        $classes = ['call-to-action'];
        switch ($this->getDisplayMode()) {
            case 'button':
                $classes[] = 'call-to-action-button';
                $classes[] = $this->getButtonSettingKind();
                $classes[] = $this->getButtonSettingsStyle();
                $classes[] = $this->getButtonSettingsColor();
                break;
            case 'icon':
                $classes[] = 'call-to-action-icon';
                break;
            default:
                $classes[] = 'call-to-action-button';
                $classes[] = $this->getClassForDisplayMode();
                break;
        }

        if (in_array('none', $classes, true)) {
            unset($classes[array_search('none', $classes, true)]);
        }

        return array_filter($classes);
    }

    public function getClassForDisplayMode(): string
    {
        $class = $this->globalState['sub_field_for_display_mode'] ?? 'call_to_action_display_mode';

        return $this->getSubField($class);
    }

    private function getLinkArrayMode(): string
    {
        if (isset($this->globalState['link'])) {
            return self::LINK_ARRAY_MODE_GLOBAL_STATE;
        }

        return self::LINK_ARRAY_MODE_SUB_FIELD;
    }

    private function getGlobalStateValue(string $key, mixed $default = null)
    {
        return $this->globalState['link'] ?? $default;
    }

    private function getLinkArray()
    {
        return $this->getGlobalStateValue('link') ?? $this->getSubField($this->getGlobalStateValue('call_to_action_subfield_key')) ?? $this->getSubField('call_to_action');
    }

    public function tryCreateSimpleLink(): ?SimpleLink
    {
        $additionalAttributes = $this->globalState['additional_attributes'] ?? [];

        if ($aria_labelledby = ($this->globalState['aria_labelledby'] ?? null)) {
            $additionalAttributes['aria-labelledby'] = $aria_labelledby;
        }
        if ($simple_link_array = $this->globalState['simple_link_array'] ?? null) {
            return SimpleLink::createFromSimpleArray($simple_link_array, $this->globalState['simple_link_array_additional_classes'] ?? [], $additionalAttributes);
        }

        $htmlTagForLink = 'a';
        if ($this->getLinkArrayMode() === self::LINK_ARRAY_MODE_GLOBAL_STATE) {
            $htmlTagForLink = $this->globalState['html_tag_for_link'] ?? 'a';
        }

        return SimpleLink::tryCreate($this->getLinkArray(), additionalAttributes: $additionalAttributes, htmlTagForLink: $htmlTagForLink);
    }
}
