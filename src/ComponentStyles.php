<?php

namespace Vendi\Theme;

use Stringable;

class ComponentStyles
{
    private array $container = [];
    private array $visibleOnlyContainer = [];

    private array $errors = [];

    public function addCssProperty(string $key, string $value): void
    {
        if ( ! $value) {
            $this->errors[] = 'Value for ' . $key . ' is empty';

            return;
        }
        $this->offsetSet($key, $value, ComponentStyleContainerEnum::DEFAULT_CONTAINER);
    }

    public function addStyle(string $key, string $value, ComponentStyleContainerEnum $containerType = ComponentStyleContainerEnum::DEFAULT_CONTAINER): void
    {
        $newValue = rtrim(trim($value), '; ');

        if ( ! $oldValue = $this->offsetGet($key, $containerType)) {
            $this->offsetSet($key, $newValue, $containerType);

            return;
        }

        if ( ! is_array($oldValue)) {
            $oldValue = [$oldValue];
        }
        $oldValue[] = $value;

        $newValue = $oldValue;

        $this->offsetSet($key, $newValue, $containerType);
    }

    public function addBackgroundImageUrl(string $trueImage, ?string $placeholderImage = null): void
    {
        $trueImage = "url('{$trueImage}')";
        if ($placeholderImage) {
            $placeholderImage = "url('{$placeholderImage}')";
        }
        $this->addBackgroundImage($trueImage, $placeholderImage);
    }

    public function addBackgroundImage(string $trueImage, ?string $placeholderImage = null): void
    {
        if ($placeholderImage) {
            $this->addStyle('background-image', $placeholderImage, ComponentStyleContainerEnum::DEFAULT_CONTAINER);
        } else {
            $this->addStyle('background-image', $trueImage, ComponentStyleContainerEnum::DEFAULT_CONTAINER);
        }

        $this->addStyle('background-image', $trueImage, ComponentStyleContainerEnum::VISIBLE_ONLY_CONTAINER);

    }

    private function offsetSet($offset, $value, ComponentStyleContainerEnum $containerType): void
    {
        $container = $this->getContainerVariableName($containerType);

        if (is_null($offset)) {
            $this->$container[] = $value;
        } else {
            $this->$container[$offset] = $value;
        }
    }

    private function offsetGet($offset, ComponentStyleContainerEnum $containerType): mixed
    {
        $container = $this->getContainerVariableName($containerType);

        return $this->$container[$offset] ?? null;
    }

    public function getDefaultStyleInformation(): string
    {
        return $this->renderStyleInformation(ComponentStyleContainerEnum::DEFAULT_CONTAINER);
    }

    public function getVisibleOnlyStyleInformation(): string
    {
        return $this->renderStyleInformation(ComponentStyleContainerEnum::VISIBLE_ONLY_CONTAINER);
    }

    private function getContainerVariableName(ComponentStyleContainerEnum $containerType): string
    {
        return match ($containerType) {
            ComponentStyleContainerEnum::VISIBLE_ONLY_CONTAINER => 'visibleOnlyContainer',
            ComponentStyleContainerEnum::DEFAULT_CONTAINER => 'container',
        };
    }

    private function renderStyleInformation(ComponentStyleContainerEnum $containerType): string
    {
        $container = $this->getContainerVariableName($containerType);

        $ret = '';
        foreach ($this->$container as $key => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            $ret .= $key . ': ' . $value . '; ';
        }

        if (count($this->errors)) {
            $ret .= '/* Errors: ' . PHP_EOL . implode(PHP_EOL, $this->errors) . PHP_EOL . ' */';
        }

        return $ret;
    }

}
