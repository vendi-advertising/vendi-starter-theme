<?php

use JetBrains\PhpStorm\Deprecated;
use Symfony\Component\Filesystem\Path;
use Vendi\Theme\ComponentInterface;
use Vendi\Theme\ComponentUtility;

function vendi_load_component_v3(array|string $name, ?array $object_state = null, ?ComponentInterface $component = null): void
{
    ComponentUtility::get_instance()->loadComponent($name, $object_state, component: $component);
}

#[Deprecated(reason: 'This component does not have a direct replacement')]
function vendi_maybe_get_template_name(array|string $name, bool $check_existence = true, ?string $filename = null): ?string
{
    $localName = is_string($name) ? explode('/', $name) : $name;

    // Remove blanks, just in case
    $localName = array_filter($localName);

    $trueFileName = $filename ?? end($localName) . '.php';

    $componentDirectory = Path::join(VENDI_CUSTOM_THEME_PATH, VENDI_CUSTOM_THEME_TEMPLATE_FOLDER_NAME, ...$localName);

    if ( ! is_dir($componentDirectory)) {
        return null;
    }

    $componentFile = Path::join($componentDirectory, $trueFileName);

    if ($check_existence && is_readable($componentFile)) {
        return $componentFile;
    }

    return null;
}
