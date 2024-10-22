<?php

namespace Vendi\Theme;

class GenericComponent extends BaseComponent
{
    private $abortRenderFunction = null;
    private array $additionalRootClasses = [];
    private array $additionalRootAttributes = [];
    private ?string $keyForBackgrounds = null;

    public function setAbortRenderFunction(callable $func): void
    {
        $this->abortRenderFunction = $func;
    }

    protected function abortRender(): bool
    {
        if (!$this->abortRenderFunction) {
            return parent::abortRender();
        }

        return call_user_func($this->abortRenderFunction);
    }

    public function addRootClass(string $class): void
    {
        $this->additionalRootClasses[] = $class;
    }

    public function getRootClasses(): array
    {
        $ret = parent::getRootClasses();

        return array_merge($ret, $this->additionalRootClasses);
    }

    public function addRootAttribute(string $name, ?string $value = null): void
    {
        $this->additionalRootAttributes[$name] = $value;
    }

    public function getRootAttributes(): array
    {
        return $this->additionalRootAttributes;
    }

    public function setKeyForBackgrounds(string $key): void
    {
        $this->keyForBackgrounds = $key;
    }

    protected function getKeyForBackgrounds(): string
    {
        return $this->keyForBackgrounds ?? parent::getKeyForBackgrounds();
    }
}
