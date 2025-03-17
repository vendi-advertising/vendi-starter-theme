<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\ComponentAwareOfGlobalStateInterface;
use Vendi\Theme\DTO\SimpleLink;
use Vendi\Theme\VendiComponent;

class Button extends VendiComponent implements ComponentAwareOfGlobalStateInterface
{
    private ?array $globalState = null;

    public function __construct()
    {
        parent::__construct('component-button');
    }

    public function setGlobalState(array $state): void
    {
        $this->globalState = $state;
    }

    public function getDisplayMode(): string
    {
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

        if (in_array('white-on-transparent', $classes, true)) {
            $classes[] = 'call-to-action-button';
            $classes[] = 'primary';
            $classes[] = 'outline';
            $classes[] = 'normal';
            $classes[] = 'dark';
            unset($classes[array_search('white-on-transparent', $classes, true)]);
        } elseif (in_array('white-on-blue', $classes, true)) {
            $classes[] = 'call-to-action-button';
            $classes[] = 'primary';
            $classes[] = 'reversed';
            $classes[] = 'dark';
            unset($classes[array_search('white-on-blue', $classes, true)]);
        }

        return array_filter($classes);
    }

    public function getClassForDisplayMode(): string
    {
        $class = $this->globalState['sub_field_for_display_mode'] ?? 'call_to_action_display_mode';

        return $this->getSubField($class);
    }

    public function tryCreateSimpleLink(): ?SimpleLink
    {
        if ($simple_link_array = $this->globalState['simple_link_array'] ?? null) {
            return SimpleLink::createFromSimpleArray($simple_link_array, $this->globalState['simple_link_array_additional_classes'] ?? []);
        }

        return SimpleLink::tryCreate($this->getSubField('call_to_action'));
    }
}
