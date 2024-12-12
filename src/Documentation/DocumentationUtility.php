<?php

namespace Vendi\Theme\Documentation;

use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;

class DocumentationUtility
{
    private static ?DocumentationUtility $instance = null;

    public static function getInstance(): self
    {
        if ( ! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {}

    public function getComponentGroups(): array
    {
        $components    = [];
        $componentRoot = Path::join(VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'components');
        $finder        = new Finder();

        foreach ($finder->files()->in($componentRoot)->directories()->sortByName()->depth(0) as $directory) {
            $component = new ComponentMeta($directory);
            $group     = $component->getComponentGroup();
            if ( ! isset($components[$group])) {
                $components[$group] = [];
            }
            $components[$group][] = $component;
        }

        return $components;
    }
}
