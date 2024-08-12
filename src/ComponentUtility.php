<?php

namespace Vendi\Theme;

final class ComponentUtility
{
    private static ?ComponentUtility $instance = null;

    private static array $registry = [];

    private function __construct()
    {
        // NOOP
    }

    /**
     * Normally we'd use DI for this, but to keep things simpler
     * we'll just use a singleton.
     *
     * @return self
     */
    public static function get_instance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get_next_id_for_component(string $componentName): int
    {
        if (!array_key_exists($componentName, self::$registry)) {
            self::$registry[$componentName] = [];
        }

        $nextId = count(self::$registry[$componentName]);

        self::$registry[$componentName][] = $nextId;

        return $nextId;
    }

    public function get_next_id_for_component_with_component_name(string $componentName): string
    {
        $id = $this->get_next_id_for_component($componentName);

        return sprintf('%1$s-%2$d', $componentName, $id);
    }
}
