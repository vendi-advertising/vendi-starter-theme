<?php

namespace Vendi\Theme;

abstract class UtilityBase
{
    /*** @var UtilityBase[] */
    protected static array $instances = [];

    private function __construct()
    {
        // NOOP
    }

    /**
     * Normally we'd use DI for this, but to keep things simpler
     * we'll just use a singleton.
     *
     * @return static
     */
    final public static function get_instance(): UtilityBase
    {
        $static_class = static::class;
        if (!isset(self::$instances[$static_class])) {
            self::$instances[$static_class] = new static();
        }

        return self::$instances[$static_class];
    }
}
