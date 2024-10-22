<?php

namespace Vendi\Theme;

class BaseSubComponent extends BaseComponent
{
    public function renderComponentWrapperStart(): bool
    {
        return true;
    }

    public function renderComponentWrapperEnd(): void
    {
        // NOOP
    }
}
