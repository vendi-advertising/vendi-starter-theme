<?php

namespace Vendi\Theme;

interface ComponentAwareOfGlobalStateInterface
{
    public function setGlobalState(array $state): void;
}
