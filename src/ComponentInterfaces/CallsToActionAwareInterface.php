<?php

namespace Vendi\Theme\ComponentInterfaces;

interface CallsToActionAwareInterface
{
    public function maybeRenderCallsToAction(string $subfieldName, string $wrapperClass): void;
}
